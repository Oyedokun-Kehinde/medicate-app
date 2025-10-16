<?php
session_start();
require_once '../config/database.php';
require_once '../vendor/autoload.php'; // PHPMailer

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    // Get and sanitize input
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $user_type = trim($_POST['user_type'] ?? '');
    
    // Validation
    if (empty($name) || empty($email) || empty($password) || empty($user_type)) {
        throw new Exception('Please fill in all required fields');
    }
    
    if (strlen($name) < 3) {
        throw new Exception('Name must be at least 3 characters long');
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Please enter a valid email address');
    }
    
    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
        throw new Exception('Password must be at least 8 characters with 1 uppercase and 1 number');
    }
    
    if ($password !== $confirm_password) {
        throw new Exception('Passwords do not match');
    }
    
    if (!in_array($user_type, ['patient', 'doctor'])) {
        throw new Exception('Invalid user type');
    }
    
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        throw new Exception('This email is already registered.');
    }
    
    // Generate OTP (6-digit)
    $otp = rand(100000, 999999);
    $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Begin transaction
    $pdo->beginTransaction();
    
    // Insert into users table
    $stmt = $pdo->prepare("
        INSERT INTO users (name, email, password_hash, user_type, verification_token, verification_expires_at, email_verified, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, 0, NOW())
    ");
    $stmt->execute([$name, $email, $hashed_password, $user_type, $otp, $expires_at]);
    $user_id = $pdo->lastInsertId();
    
    // Create profile
    if ($user_type === 'patient') {
        $stmt = $pdo->prepare("INSERT INTO patient_profiles (user_id, created_at) VALUES (?, NOW())");
    } else {
        $stmt = $pdo->prepare("INSERT INTO doctor_profiles (user_id, status, created_at) VALUES (?, 'pending', NOW())");
    }
    $stmt->execute([$user_id]);
    
    $pdo->commit();
    
    // === SEND OTP EMAIL ===
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';     // Use your SMTP host
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your-email@gmail.com'; // Your email
        $mail->Password   = 'your-app-password';    // Gmail App Password
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('noreply@medicate.com', 'Medicate');
        $mail->addAddress($email, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Medicate Account';
        $mail->Body    = "
        <h2>Email Verification</h2>
        <p>Hello {$name},</p>
        <p>Your verification code is: <strong>{$otp}</strong></p>
        <p>This code expires in 1 hour.</p>
        <p>If you didn’t request this, please ignore this email.</p>
        ";

        $mail->send();
    } catch (Exception $e) {
        error_log("Email error: " . $mail->ErrorInfo);
        // Don’t fail registration if email fails — just log it
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Registration successful! Please check your email for the verification code.',
        'email' => $email
    ]);
    
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    // Get and sanitize input
    $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $user_type = trim(filter_input(INPUT_POST, 'user_type', FILTER_SANITIZE_STRING));
    
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
    
    if (strlen($password) < 8) {
        throw new Exception('Password must be at least 8 characters long');
    }
    
    if (!preg_match('/[A-Z]/', $password)) {
        throw new Exception('Password must contain at least one uppercase letter');
    }
    
    if (!preg_match('/[0-9]/', $password)) {
        throw new Exception('Password must contain at least one number');
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
        throw new Exception('This email is already registered. Please login or use a different email.');
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Generate verification token
    $verification_token = bin2hex(random_bytes(32));
    
    // Begin transaction
    $pdo->beginTransaction();
    
    // Insert into users table
    $stmt = $pdo->prepare("
        INSERT INTO users (name, email, password, user_type, verification_token, is_verified, created_at) 
        VALUES (?, ?, ?, ?, ?, 0, NOW())
    ");
    
    $stmt->execute([$name, $email, $hashed_password, $user_type, $verification_token]);
    $user_id = $pdo->lastInsertId();
    
    // Create profile table entry based on user type
    if ($user_type === 'patient') {
        $stmt = $pdo->prepare("
            INSERT INTO patient_profiles (user_id, created_at) 
            VALUES (?, NOW())
        ");
        $stmt->execute([$user_id]);
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO doctor_profiles (user_id, status, created_at) 
            VALUES (?, 'pending', NOW())
        ");
        $stmt->execute([$user_id]);
    }
    
    // Log activity
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    
    $stmt = $pdo->prepare("
        INSERT INTO activity_logs (user_id, action, description, ip_address, user_agent, created_at) 
        VALUES (?, 'registration', ?, ?, ?, NOW())
    ");
    $stmt->execute([
        $user_id,
        "New {$user_type} registered",
        $ip_address,
        $user_agent
    ]);
    
    // Commit transaction
    $pdo->commit();
    
    // Send verification email
    $verification_link = "http://" . $_SERVER['HTTP_HOST'] . "/public/verify-email.php?token=" . $verification_token;
    
    $to = $email;
    $subject = "Verify Your Medicate Account";
    $message = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: 'Quicksand', Arial, sans-serif; margin: 0; padding: 0; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
            .content { background: #f8f9fa; padding: 30px; border-radius: 0 0 8px 8px; }
            .button { display: inline-block; padding: 15px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; font-weight: 600; }
            .footer { text-align: center; margin-top: 20px; color: #6c757d; font-size: 14px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Welcome to Medicate!</h1>
            </div>
            <div class='content'>
                <h2>Hello {$name},</h2>
                <p>Thank you for registering with Medicate as a <strong>" . ucfirst($user_type) . "</strong>.</p>
                <p>Please verify your email address by clicking the button below:</p>
                <p style='text-align: center;'>
                    <a href='{$verification_link}' class='button'>Verify Email Address</a>
                </p>
                <p>Or copy and paste this link in your browser:</p>
                <p style='word-break: break-all; color: #667eea;'>{$verification_link}</p>
                " . ($user_type === 'doctor' ? "<p><strong>Note:</strong> After email verification, your doctor account will be reviewed by our admin team before activation.</p>" : "") . "
                <p>If you did not create this account, please ignore this email.</p>
                <div class='footer'>
                    <p>© 2025 Medicate. All rights reserved.</p>
                </div>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Medicate <noreply@medicate.com>" . "\r\n";
    
    // Send email (in production, use a proper email service)
    mail($to, $subject, $message, $headers);
    
    echo json_encode([
        'success' => true,
        'message' => 'Registration successful! Please check your email to verify your account.'
    ]);
    
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
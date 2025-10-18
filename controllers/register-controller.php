<?php
session_start();

// Load PHPMailer FIRST (before any other code)
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

// Set error handling
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Catch all errors and convert to JSON
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $errstr,
        'file' => $errfile,
        'line' => $errline
    ]);
    exit;
});

try {
    error_log("Register controller called");
    
    // Check if database config exists
    if (!file_exists('../config/database.php')) {
        throw new Exception('Database config file not found at: ' . realpath('../config'));
    }
    
    require_once '../config/database.php';
    error_log("Database loaded successfully");
    
    // Check if it's AJAX request
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
        throw new Exception('Invalid request: Not AJAX');
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method: ' . $_SERVER['REQUEST_METHOD']);
    }

    // Get and sanitize input
    $user_type = trim($_POST['user_type'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $password = $_POST['password_hash'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    error_log("Form data received - Email: $email, User Type: $user_type");

    // Validation
    if (empty($user_type) || !in_array($user_type, ['patient', 'doctor'])) {
        throw new Exception('Please select a valid user type');
    }

    if (empty($full_name)) {
        throw new Exception('Full name is required');
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Please enter a valid email address');
    }

    if (empty($phone)) {
        throw new Exception('Phone number is required');
    }

    if (empty($location)) {
        throw new Exception('Location is required');
    }

    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/\d/', $password)) {
        throw new Exception('Password must be at least 8 characters with 1 uppercase and 1 number');
    }

    if ($password !== $confirm_password) {
        throw new Exception('Passwords do not match');
    }

    // Check if email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        throw new Exception('This email is already registered');
    }

    error_log("Email validation passed");

    // Start transaction
    $pdo->beginTransaction();
    error_log("Transaction started");

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Generate 6-digit OTP
    $verification_token = sprintf("%06d", mt_rand(0, 999999));
    $verification_expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Insert into users table
    $stmt = $pdo->prepare("
        INSERT INTO users (user_type, email, password_hash, full_name, phone, status, email_verified, verification_token, verification_expires_at, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, 'active', 0, ?, ?, NOW(), NOW())
    ");
    $stmt->execute([$user_type, $email, $hashed_password, $full_name, $phone, $verification_token, $verification_expires_at]);
    $user_id = $pdo->lastInsertId();

    error_log("User created with ID: $user_id, OTP: $verification_token");

    if (!$user_id) {
        throw new Exception('Failed to create user account');
    }

    // Insert into patient_profiles or doctor_profiles
    if ($user_type === 'patient') {
        $stmt = $pdo->prepare("
            INSERT INTO patient_profiles (user_id, phone, address, city, created_at, updated_at) 
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->execute([$user_id, $phone, $location, $location]);
        error_log("Patient profile created");
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO doctor_profiles (user_id, phone, address, city, status, created_at, updated_at) 
            VALUES (?, ?, ?, ?, 'pending', NOW(), NOW())
        ");
        $stmt->execute([$user_id, $phone, $location, $location]);
        error_log("Doctor profile created");
    }

    // Send verification email using PHPMailer
    $mail = new PHPMailer(true);
    
    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'decimaltechy@gmail.com';        // CHANGE THIS
        $mail->Password = 'pelqixzchqlagxlf
';           // CHANGE THIS (Use App Password, not regular password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        // Email Settings
        $mail->setFrom('noreply@medicate.com', 'Medicate Healthcare');
        $mail->addAddress($email, $full_name);
        $mail->addReplyTo('info@medicate.com', 'Medicate Support');
        
        // Email Content
        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Email - Medicate Healthcare';
        
        // Different message for Patient vs Doctor
        $userTypeLabel = ucfirst($user_type);
        $dashboardInfo = ($user_type === 'doctor') 
            ? '<p><strong>Note:</strong> Your doctor account will be reviewed by our admin team before full activation.</p>' 
            : '<p>Once verified, you can access all patient features immediately.</p>';
        
        $mail->Body = "
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { font-family: 'Arial', sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: linear-gradient(135deg, #007bff, #0056b3); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                    .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                    .otp-box { background: white; border: 2px dashed #007bff; padding: 20px; text-align: center; margin: 20px 0; border-radius: 8px; }
                    .otp-code { font-size: 32px; font-weight: bold; color: #007bff; letter-spacing: 5px; }
                    .button { display: inline-block; padding: 12px 30px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                    .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>Welcome to Medicate!</h1>
                    </div>
                    <div class='content'>
                        <h2>Hello {$full_name},</h2>
                        <p>Thank you for registering as a <strong>{$userTypeLabel}</strong> with Medicate Healthcare.</p>
                        <p>To complete your registration, please verify your email address using the code below:</p>
                        
                        <div class='otp-box'>
                            <p style='margin: 0; color: #666;'>Your Verification Code</p>
                            <div class='otp-code'>{$verification_token}</div>
                        </div>
                        
                        <p><strong>This code will expire in 1 hour.</strong></p>
                        
                        {$dashboardInfo}
                        
                        <p>If you didn't create this account, please ignore this email.</p>
                        
                        <div style='text-align: center;'>
                            <a href='http://localhost/medicate-app/public/verify-email.php?email=" . urlencode($email) . "' class='button'>Verify Email Now</a>
                        </div>
                    </div>
                    <div class='footer'>
                        <p>© 2025 Medicate Healthcare. All rights reserved.</p>
                        <p>Need help? Contact us at info@medicate.com</p>
                    </div>
                </div>
            </body>
            </html>
        ";
        
        $mail->AltBody = "Welcome to Medicate!\n\nYour verification code is: {$verification_token}\n\nThis code will expire in 1 hour.\n\nIf you didn't create this account, please ignore this email.";
        
        $mail->send();
        error_log("Verification email sent successfully to: $email (User Type: $user_type)");
        
    } catch (PHPMailerException $e) {
        error_log("PHPMailer Error: " . $mail->ErrorInfo);
        // Rollback transaction if email fails
        $pdo->rollBack();
        throw new Exception('Failed to send verification email. Please try again later.');
    }

    // Commit transaction only if email was sent successfully
    $pdo->commit();
    error_log("Transaction committed successfully for $user_type: $email");

    echo json_encode([
        'success' => true,
        'message' => 'Registration successful! Please check your email for the verification code.',
        'email' => $email,
        'user_type' => $user_type
    ]);

} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    error_log("PDO Error: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
    
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    error_log("Error: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

exit;
?>
<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

// Error handling
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $errstr
    ]);
    exit;
});

try {
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
        throw new Exception('Invalid request');
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $email = trim($_POST['email'] ?? '');
    $otp = trim($_POST['otp'] ?? '');

    // Validation
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Please provide a valid email address');
    }

    if (empty($otp) || strlen($otp) !== 6 || !ctype_digit($otp)) {
        throw new Exception('Please enter a valid 6-digit verification code');
    }

    // Check if user exists and OTP is valid
    $stmt = $pdo->prepare("
        SELECT id, user_type, full_name, email_verified 
        FROM users 
        WHERE email = ? 
        AND verification_token = ? 
        AND verification_expires_at > NOW()
    ");
    $stmt->execute([$email, $otp]);
    $user = $stmt->fetch();

    if (!$user) {
        // Check if already verified
        $stmt = $pdo->prepare("SELECT email_verified FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $existingUser = $stmt->fetch();
        
        if ($existingUser && $existingUser['email_verified'] == 1) {
            throw new Exception('Email already verified. Please login.');
        }
        
        throw new Exception('Invalid or expired verification code. Please try again or request a new code.');
    }

    // Check if already verified
    if ($user['email_verified'] == 1) {
        throw new Exception('Email already verified. Please login.');
    }

    // Mark email as verified
    $stmt = $pdo->prepare("
        UPDATE users 
        SET email_verified = 1, 
            verification_token = NULL, 
            verification_expires_at = NULL,
            updated_at = NOW() 
        WHERE email = ?
    ");
    $stmt->execute([$email]);

    if ($stmt->rowCount() === 0) {
        throw new Exception('Failed to verify email. Please try again.');
    }

    error_log("Email verified successfully for: $email");

    // Determine redirect based on user type
    $redirect = 'login.php?verified=1';

    echo json_encode([
        'success' => true,
        'message' => 'Email verified successfully! You can now login.',
        'redirect' => $redirect,
        'user_type' => $user['user_type']
    ]);

} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Database error. Please try again later.'
    ]);
} catch (Exception $e) {
    error_log("Verification Error: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

exit;
?>
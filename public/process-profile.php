<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    header('Location: ../public/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../dashboard/patient/index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Get and validate form data
    $name = trim(filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_STRING));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $phone = trim(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING));
    $address = trim(filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING));
    $blood_group = filter_input(INPUT_POST, 'blood_group', FILTER_SANITIZE_STRING);
    $emergency_contact_phone = trim(filter_input(INPUT_POST, 'emergency_contact_phone', FILTER_SANITIZE_STRING));
    $medical_history = trim(filter_input(INPUT_POST, 'medical_history', FILTER_SANITIZE_STRING));
    $allergies = trim(filter_input(INPUT_POST, 'allergies', FILTER_SANITIZE_STRING));
    
    // Validation
    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
        throw new Exception('Please fill in all required fields');
    }
    
    if (strlen($name) < 3) {
        throw new Exception('Name must be at least 3 characters long');
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Please enter a valid email address');
    }
    
    // Check if email is already taken by another user
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->execute([$email, $user_id]);
    if ($stmt->fetch()) {
        throw new Exception('This email is already registered to another account');
    }
    
    // Begin transaction
    $pdo->beginTransaction();
    
    // Update users table
    $stmt = $pdo->prepare("
        UPDATE users 
        SET name = ?, email = ?, updated_at = NOW() 
        WHERE id = ?
    ");
    $stmt->execute([$name, $email, $user_id]);
    
    // Update patient_profiles table
    $stmt = $pdo->prepare("
        UPDATE patient_profiles 
        SET 
            phone = ?,
            address = ?,
            blood_group = ?,
            emergency_contact_phone = ?,
            medical_history = ?,
            allergies = ?,
            updated_at = NOW()
        WHERE user_id = ?
    ");
    
    $stmt->execute([
        $phone,
        $address,
        $blood_group ?: null,
        $emergency_contact_phone ?: null,
        $medical_history ?: null,
        $allergies ?: null,
        $user_id
    ]);
    
    // Log activity
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    
    $stmt = $pdo->prepare("
        INSERT INTO activity_logs (user_id, action, description, ip_address, user_agent, created_at) 
        VALUES (?, 'profile_update', ?, ?, ?, NOW())
    ");
    $stmt->execute([
        $user_id,
        "Patient updated profile information",
        $ip_address,
        $user_agent
    ]);
    
    // Commit transaction
    $pdo->commit();
    
    // Update session name if changed
    $_SESSION['user_name'] = $name;
    
    // Send notification email
    $to = $email;
    $subject = "Profile Updated Successfully - Medicate";
    $message = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: 'Quicksand', Arial, sans-serif; margin: 0; padding: 0; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #667eea; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
            .content { background: #f8f9fa; padding: 30px; }
            .footer { text-align: center; margin-top: 20px; color: #6c757d; font-size: 14px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Profile Updated</h2>
            </div>
            <div class='content'>
                <h3>Hello {$name},</h3>
                <p>Your profile information has been successfully updated.</p>
                <p>If you did not make this change, please contact our support team immediately.</p>
                <p style='text-align: center; margin-top: 30px;'>
                    <a href='http://{$_SERVER['HTTP_HOST']}/dashboard/patient/index.php' style='display: inline-block; padding: 12px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 5px;'>
                        View Dashboard
                    </a>
                </p>
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
    
    mail($to, $subject, $message, $headers);
    
    // Redirect with success message
    $_SESSION['success_message'] = 'Profile updated successfully!';
    header('Location: ../dashboard/patient/index.php?section=profile');
    exit;
    
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    // Redirect with error message
    $_SESSION['error_message'] = $e->getMessage();
    header('Location: ../dashboard/patient/index.php?section=profile');
    exit;
}
?>
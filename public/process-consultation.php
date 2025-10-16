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
    $service_id = filter_input(INPUT_POST, 'service_id', FILTER_VALIDATE_INT);
    $urgency = filter_input(INPUT_POST, 'urgency', FILTER_SANITIZE_STRING);
    $consultation_date = filter_input(INPUT_POST, 'consultation_date', FILTER_SANITIZE_STRING);
    $consultation_time = filter_input(INPUT_POST, 'consultation_time', FILTER_SANITIZE_STRING);
    $reason = filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_STRING);
    
    // Validation
    if (!$service_id || !$urgency || !$consultation_date || !$consultation_time || !$reason) {
        throw new Exception('Please fill in all required fields');
    }
    
    // Validate date is not in the past
    $consultationDateTime = strtotime($consultation_date . ' ' . $consultation_time);
    if ($consultationDateTime < time()) {
        throw new Exception('Consultation date and time cannot be in the past');
    }
    
    // Validate urgency
    if (!in_array($urgency, ['normal', 'urgent'])) {
        throw new Exception('Invalid urgency level');
    }
    
    // Begin transaction
    $pdo->beginTransaction();
    
    // Insert consultation request
    $stmt = $pdo->prepare("
        INSERT INTO consultations (
            patient_id, 
            service_id, 
            consultation_date, 
            consultation_time, 
            reason, 
            urgency,
            status, 
            created_at
        ) VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())
    ");
    
    $stmt->execute([
        $user_id,
        $service_id,
        $consultation_date,
        $consultation_time,
        $reason,
        $urgency
    ]);
    
    $consultation_id = $pdo->lastInsertId();
    
    // Log activity
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    
    $stmt = $pdo->prepare("
        INSERT INTO activity_logs (user_id, action, description, ip_address, user_agent, created_at) 
        VALUES (?, 'consultation_request', ?, ?, ?, NOW())
    ");
    $stmt->execute([
        $user_id,
        "Patient requested consultation #$consultation_id",
        $ip_address,
        $user_agent
    ]);
    
    // Commit transaction
    $pdo->commit();
    
    // Get user email for notification
    $stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    // Send confirmation email
    if ($user) {
        $to = $user['email'];
        $subject = "Consultation Request Confirmation - Medicate";
        $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: 'Quicksand', Arial, sans-serif; margin: 0; padding: 0; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #667eea; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
                .content { background: #f8f9fa; padding: 30px; }
                .info-box { background: white; padding: 15px; border-radius: 5px; margin: 15px 0; }
                .footer { text-align: center; margin-top: 20px; color: #6c757d; font-size: 14px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Consultation Request Received</h2>
                </div>
                <div class='content'>
                    <h3>Hello {$user['name']},</h3>
                    <p>We have received your consultation request. Here are the details:</p>
                    
                    <div class='info-box'>
                        <p><strong>Request ID:</strong> #{$consultation_id}</p>
                        <p><strong>Date:</strong> " . date('F d, Y', strtotime($consultation_date)) . "</p>
                        <p><strong>Time:</strong> " . date('h:i A', strtotime($consultation_time)) . "</p>
                        <p><strong>Urgency:</strong> " . ucfirst($urgency) . "</p>
                    </div>
                    
                    <p>Our team will review your request and get back to you shortly. You can track the status of your consultation in your dashboard.</p>
                    
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
    }
    
    // Redirect with success message
    $_SESSION['success_message'] = 'Consultation request submitted successfully! We will contact you shortly.';
    header('Location: ../dashboard/patient/index.php?section=appointments');
    exit;
    
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    // Redirect with error message
    $_SESSION['error_message'] = $e->getMessage();
    header('Location: ../dashboard/patient/index.php?section=consultation');
    exit;
}
?>
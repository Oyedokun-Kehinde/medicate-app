<?php
session_start();
require_once '../config/database.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

$email = $_GET['email'] ?? '';
$error = '';
$success = '';

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: register.php');
    exit;
}

try {
    // Check if user exists and is not verified
    $stmt = $pdo->prepare("SELECT id, full_name, email_verified FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        $error = 'Email not found. Please register first.';
    } elseif ($user['email_verified'] == 1) {
        $error = 'Email already verified. Please login.';
    } else {
        // Generate new OTP
        $otp = sprintf("%06d", mt_rand(0, 999999));
        $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Update database
        $stmt = $pdo->prepare("
            UPDATE users 
            SET verification_token = ?, 
                verification_expires_at = ?,
                updated_at = NOW()
            WHERE email = ? AND email_verified = 0
        ");
        $stmt->execute([$otp, $expires_at, $email]);

        if ($stmt->rowCount() === 0) {
            throw new Exception('Failed to update verification code.');
        }

        // Send email using PHPMailer
        $mail = new PHPMailer(true);
        
        try {
            // SMTP Configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'decimaltechy@gmail.com';        // YOUR EMAIL
            $mail->Password = 'pelqixzchqlagxlf';           // YOUR APP PASSWORD
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            
            // Email Settings
            $mail->setFrom('noreply@medicate.com', 'Medicate Healthcare');
            $mail->addAddress($email, $user['full_name']);
            
            // Email Content
            $mail->isHTML(true);
            $mail->Subject = 'New Verification Code - Medicate Healthcare';
            $mail->Body = "
                <!DOCTYPE html>
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                        .header { background: linear-gradient(135deg, #007bff, #0056b3); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                        .otp-box { background: white; border: 2px dashed #007bff; padding: 20px; text-align: center; margin: 20px 0; border-radius: 8px; }
                        .otp-code { font-size: 32px; font-weight: bold; color: #007bff; letter-spacing: 5px; }
                        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h1>New Verification Code</h1>
                        </div>
                        <div class='content'>
                            <h2>Hello {$user['full_name']},</h2>
                            <p>You requested a new verification code for your Medicate account.</p>
                            
                            <div class='otp-box'>
                                <p style='margin: 0; color: #666;'>Your New Verification Code</p>
                                <div class='otp-code'>{$otp}</div>
                            </div>
                            
                            <p><strong>This code will expire in 1 hour.</strong></p>
                            <p>If you didn't request this code, please ignore this email or contact support.</p>
                        </div>
                        <div class='footer'>
                            <p>© 2025 Medicate Healthcare. All rights reserved.</p>
                        </div>
                    </div>
                </body>
                </html>
            ";
            
            $mail->AltBody = "Hello {$user['full_name']},\n\nYour new verification code is: {$otp}\n\nThis code will expire in 1 hour.";
            
            $mail->send();
            $success = 'New verification code sent successfully! Please check your email.';
            error_log("Resent OTP successfully to: $email");
            
        } catch (PHPMailerException $e) {
            error_log("PHPMailer Error: " . $mail->ErrorInfo);
            throw new Exception('Failed to send email. Please try again later.');
        }
    }
    
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $error = 'Database error. Please try again later.';
} catch (Exception $e) {
    error_log("Resend OTP Error: " . $e->getMessage());
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resend Code — Medicate</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Quicksand', sans-serif;
        }
        .message-box {
            max-width: 500px;
            width: 90%;
            padding: 40px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .icon {
            font-size: 70px;
            margin-bottom: 20px;
        }
        .icon.success { color: #28a745; }
        .icon.error { color: #dc3545; }
        h3 { color: #333; margin-bottom: 15px; font-weight: 700; }
        p { color: #666; line-height: 1.8; }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            margin-top: 20px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 123, 255, 0.4);
            color: white;
        }
    </style>
</head>

<body>
    <div class="message-box">
        <?php if ($success): ?>
            <div class="icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3>Code Sent!</h3>
            <p><?php echo htmlspecialchars($success); ?></p>
            <a href="verify-email.php?email=<?php echo urlencode($email); ?>" class="btn">
                <i class="fas fa-arrow-right"></i> Go to Verification
            </a>
        <?php else: ?>
            <div class="icon error">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <h3>Oops!</h3>
            <p><?php echo htmlspecialchars($error); ?></p>
            <a href="register.php" class="btn">
                <i class="fas fa-arrow-left"></i> Back to Registration
            </a>
        <?php endif; ?>
    </div>

    <script src="assets/js/jquery.min.js"></script>
</body>
</html>
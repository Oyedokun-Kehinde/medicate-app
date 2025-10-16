<?php
session_start();
require_once '../config/database.php';

$email = $_GET['email'] ?? $_POST['email'] ?? '';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = $_POST['otp'] ?? '';
    
    if (empty($otp) || strlen($otp) !== 6) {
        $error = 'Please enter a 6-digit code';
    } else {
        $stmt = $pdo->prepare("
            SELECT id, user_type, name FROM users 
            WHERE email = ? AND verification_token = ? AND verification_expires_at > NOW() AND is_verified = 0
        ");
        $stmt->execute([$email, $otp]);
        $user = $stmt->fetch();

        if ($user) {
            // Update user as verified
            $stmt = $pdo->prepare("UPDATE users SET is_verified = 1, verification_token = NULL, verification_expires_at = NULL, updated_at = NOW() WHERE email = ?");
            $stmt->execute([$email]);

            $success = 'Email verified successfully!';
            
            // Auto-login after verification
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['user_name'] = $user['name'];
            
            // Redirect after 2 seconds
            $redirect = ($user['user_type'] === 'doctor') ? '../dashboard/doctor/index.php' : '../dashboard/patient/index.php';
            header("Refresh: 2; URL=$redirect");
        } else {
            $error = 'Invalid or expired code';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Email – Medicate</title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/register.css">
    <style>
        .verify-box {
            max-width: 500px;
            margin: 100px auto;
            padding: 40px;
            background: #f4f6f9;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
        }
        .verify-icon {
            font-size: 60px;
            margin-bottom: 20px;
        }
        .verify-icon.success { color: #28a745; }
        .verify-icon.error { color: #dc3545; }
        .otp-input {
            font-size: 24px;
            text-align: center;
            letter-spacing: 10px;
            font-weight: 600;
        }
        .resend-link {
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="verify-box">
            <?php if ($success): ?>
                <div class="verify-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 style="color: #28a745; margin-bottom: 15px;">Success!</h3>
                <p><?php echo $success; ?></p>
                <p style="color: #6c757d; font-size: 14px;">Redirecting to dashboard...</p>
            <?php else: ?>
                <div class="verify-icon" style="color: var(--primary-color);">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
                <h3 style="margin-bottom: 10px;">Verify Your Email</h3>
                <p style="color: #6c757d; margin-bottom: 30px;">
                    We sent a 6-digit code to<br>
                    <strong><?php echo htmlspecialchars($email); ?></strong>
                </p>

                <?php if ($error): ?>
                    <div class="alert alert-danger" style="margin-bottom: 20px;">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    <div class="form-group">
                        <input type="text" name="otp" class="form-control otp-input" 
                               placeholder="000000" maxlength="6" required autofocus 
                               pattern="[0-9]{6}" title="Enter 6-digit code">
                    </div>
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-check"></i> Verify & Continue
                    </button>
                </form>

                <div class="resend-link">
                    <p style="color: #6c757d;">Didn't receive the code?</p>
                    <a href="resend-otp.php?email=<?php echo urlencode($email); ?>" style="color: var(--primary-color); font-weight: 600;">
                        <i class="fas fa-redo"></i> Resend Code
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="assets/js/jquery.min.js"></script>
    <script>
        // Auto-format OTP input
        document.querySelector('.otp-input').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html>
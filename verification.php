

<?php
require_once 'config/session.php';
require_once 'classes/User.php';
require_once 'config/database.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get user_id from URL
$user_id = $_GET['user_id'] ?? '';

// Validate we have a user_id
if (empty($user_id) || !is_numeric($user_id)) {
    $error_message = "Invalid verification link. Please register again.";
    $show_error = true;
    $actual_code = null;
} else {
    $show_error = false;
    
    // Fetch the verification code FROM DATABASE
    try {
        $stmt = $pdo->prepare("SELECT verification_code, is_verified FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
        
        if (!$user) {
            $error_message = "User not found. Please register again.";
            $show_error = true;
            $actual_code = null;
        } elseif ($user['is_verified']) {
            // Already verified - redirect to login
            header("Location: login.php?msg=" . urlencode('Your account is already verified. Please login.'));
            exit();
        } else {
            $actual_code = $user['verification_code'];
        }
    } catch (Exception $e) {
        $error_message = "Database error: " . $e->getMessage();
        $show_error = true;
        $actual_code = null;
    }
}

// If user submits verification form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$show_error) {
    $entered_code = trim($_POST['verification_code'] ?? '');
    
    // Compare entered code with actual code from database
    if ($entered_code === (string)$actual_code) {
        // Mark user as verified
        try {
            $userObj = new User();
            $userObj->verifyUser($user_id);
            
            header("Location: login.php?msg=" . urlencode('Email verified successfully! You can now log in.'));
            exit();
        } catch (Exception $e) {
            $verification_error = "Verification failed. Please try again.";
        }
    } else {
        $verification_error = "Incorrect verification code. Please check and try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verification – Medicate</title>
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Main Styles -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/register.css">

    <!-- Icons -->
    <link rel="stylesheet" href="assets/fonts/font-awesome/css/all.min.css">
    
    <style>
        .verification-container {
            max-width: 600px;
            margin: 60px auto;
            padding: 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(0,0,0,0.1);
            text-align: center;
        }
        .success-icon {
            font-size: 64px;
            color: #28a745;
            margin-bottom: 20px;
        }
        .error-icon {
            font-size: 64px;
            color: #dc3545;
            margin-bottom: 20px;
        }
        .code-display {
            background: #e9f7ef;
            border: 2px dashed #28a745;
            padding: 30px;
            margin: 25px 0;
            font-size: 32px;
            letter-spacing: 8px;
            font-weight: bold;
            color: #155724;
            border-radius: 8px;
            user-select: all;
        }
        .code-input {
            font-size: 24px;
            letter-spacing: 8px;
            text-align: center;
            font-weight: bold;
            padding: 15px;
            margin: 20px 0;
        }
        .instructions {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
        }
        .instructions h5 {
            color: #856404;
            margin-bottom: 10px;
        }
        .instructions p {
            color: #856404;
            margin: 5px 0;
            font-size: 14px;
        }
        .error-box {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
            color: #721c24;
        }
        #snackbar {
            visibility: hidden;
            min-width: 250px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 8px;
            padding: 16px 24px;
            position: fixed;
            z-index: 9999;
            left: 50%;
            bottom: 30px;
            transform: translateX(-50%);
            font-size: 15px;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            opacity: 0;
            transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>

    <!-- Preloader -->
    <div id="pq-loading">
        <div id="pq-loading-center">
            <img src="assets/images/logo.png" class="img-fluid" alt="Medicate">
        </div>
    </div>

    <div class="verification-container">
        <?php if ($show_error): ?>
            <!-- Error State -->
            <div class="error-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <h2 style="color: #dc3545; margin-bottom: 20px;">Verification Error</h2>
            <div class="error-box">
                <p><?= htmlspecialchars($error_message) ?></p>
            </div>
            <a href="register.php" class="btn btn-primary">Back to Registration</a>
        <?php else: ?>
            <!-- Success State -->
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <h2 style="color: #28a745; margin-bottom: 20px;">Registration Successful!</h2>
            
            <p style="font-size: 16px; color: #666; margin-bottom: 25px;">
                Your account has been created. Below is your verification code:
            </p>

            <div class="code-display" id="verification-code-display">
                <?= htmlspecialchars($actual_code) ?>
            </div>

            <button onclick="copyCode()" class="btn btn-outline-success mb-4">
                <i class="fas fa-copy"></i> Copy Code
            </button>

            <?php if (isset($verification_error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($verification_error) ?></div>
            <?php endif; ?>

            <div class="instructions">
                <h5><i class="fas fa-info-circle"></i> Next Steps:</h5>
                <p><strong>1.</strong> Save this code for your records</p>
                <p><strong>2.</strong> Enter the code below to verify your email</p>
                <p><strong>3.</strong> Once verified, you can log in to your account</p>
            </div>

            <form method="POST" id="verificationForm">
                <div class="mb-3">
                    <label for="verification_code" class="form-label">
                        <strong>Enter Verification Code</strong>
                    </label>
                    <input 
                        type="text" 
                        class="form-control code-input" 
                        id="verification_code" 
                        name="verification_code" 
                        placeholder="000000"
                        maxlength="6"
                        pattern="\d{6}"
                        required
                        autocomplete="off"
                    >
                    <small class="text-muted">Enter the 6-digit code shown above</small>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                    <i class="fas fa-shield-alt"></i> Complete Verification
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="login.php" class="btn btn-link">Skip for now and login later</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Snackbar -->
    <div id="snackbar"></div>

    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/verification.js"></script>

    <!-- Preloader Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const loader = document.getElementById('pq-loading');
            if (loader) {
                setTimeout(() => {
                    loader.style.opacity = '0';
                    setTimeout(() => loader.remove(), 500);
                }, 800);
            }
        });

        // Copy code to clipboard
        function copyCode() {
            const code = document.getElementById('verification-code-display').textContent.trim();
            navigator.clipboard.writeText(code).then(() => {
                showSnackbar('Code copied to clipboard!', 'success');
            }).catch(() => {
                showSnackbar('Failed to copy code', 'error');
            });
        }

        // Snackbar function (inline for copy button)
        function showSnackbar(message, type = 'info') {
            const snackbar = $('#snackbar');
            const colors = {
                success: { bg: '#10b981', icon: 'fa-check-circle' },
                error: { bg: '#ef4444', icon: 'fa-exclamation-circle' },
                warning: { bg: '#f59e0b', icon: 'fa-exclamation-triangle' },
                info: { bg: '#3b82f6', icon: 'fa-info-circle' }
            };
            const color = colors[type] || colors.info;
            snackbar.html(`<i class="fas ${color.icon}" style="margin-right:8px;"></i>${message}`);
            snackbar.css({'background-color': color.bg, 'visibility': 'visible', 'opacity': '1'});
            setTimeout(() => snackbar.css({'visibility': 'hidden', 'opacity': '0'}), 3000);
        }
    </script>
</body>
</html>
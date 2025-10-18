<?php
$email = $_GET['email'] ?? '';
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: register.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Email — Medicate</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Quicksand', sans-serif;
        }
        .verify-box {
            max-width: 500px;
            width: 90%;
            padding: 40px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .verify-icon {
            font-size: 70px;
            margin-bottom: 20px;
            color: #007bff;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        .verify-icon.success {
            color: #28a745;
            animation: none;
        }
        h3 {
            color: #333;
            margin-bottom: 15px;
            font-weight: 700;
        }
        .email-display {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            margin: 20px 0;
            font-weight: 600;
            color: #007bff;
            word-break: break-all;
        }
        .otp-input {
            font-size: 28px;
            text-align: center;
            letter-spacing: 15px;
            font-weight: 700;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            width: 100%;
            transition: all 0.3s;
        }
        .otp-input:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
            outline: none;
        }
        .submit-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .submit-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 123, 255, 0.4);
        }
        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .resend-link {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #eee;
        }
        .resend-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        .resend-link a:hover {
            color: #0056b3;
        }
        .alert {
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            animation: slideDown 0.3s ease-out;
        }
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .info-text {
            color: #6c757d;
            font-size: 14px;
            margin: 15px 0;
        }
        .back-link {
            margin-top: 20px;
        }
        .back-link a {
            color: #6c757d;
            text-decoration: none;
            font-size: 14px;
        }
        .back-link a:hover {
            color: #007bff;
        }
    </style>
</head>

<body>
    <div class="verify-box">
        <div class="verify-icon">
            <i class="fas fa-envelope-open-text"></i>
        </div>
        <h3>Verify Your Email</h3>
        <p class="info-text">
            We sent a 6-digit verification code to:
        </p>
        <div class="email-display">
            <?php echo htmlspecialchars($email); ?>
        </div>

        <div id="alert-container"></div>

        <form id="verifyForm">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <div class="form-group">
                <input type="text" 
                       name="otp" 
                       id="otp" 
                       class="otp-input" 
                       placeholder="000000" 
                       maxlength="6" 
                       required 
                       autofocus 
                       pattern="[0-9]{6}" 
                       title="Enter 6-digit code">
            </div>
            <button type="submit" class="submit-btn" id="verifyBtn">
                <i class="fas fa-check-circle"></i>
                <span>Verify & Continue</span>
            </button>
        </form>

        <div class="resend-link">
            <p class="info-text">Didn't receive the code?</p>
            <a href="#" id="resendLink">
                <i class="fas fa-redo"></i> Resend Code
            </a>
        </div>

        <div class="back-link">
            <a href="register.php">
                <i class="fas fa-arrow-left"></i> Back to Registration
            </a>
        </div>
    </div>

    <script src="assets/js/jquery.min.js"></script>
    
    <script>
        // Auto-format OTP input (numbers only)
        document.getElementById('otp').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Show alert function
        function showAlert(message, type) {
            const alertContainer = document.getElementById('alert-container');
            alertContainer.innerHTML = `
                <div class="alert alert-${type}">
                    <i class="fas fa-${type === 'danger' ? 'exclamation-circle' : 'check-circle'}"></i>
                    ${message}
                </div>
            `;
            
            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 5000);
        }

        // Handle verification form submission
        document.getElementById('verifyForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = document.getElementById('verifyBtn');
            const originalHTML = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';

            fetch('../controllers/verify-email-controller.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Change icon to success
                    document.querySelector('.verify-icon').innerHTML = '<i class="fas fa-check-circle"></i>';
                    document.querySelector('.verify-icon').classList.add('success');
                    
                    showAlert(data.message, 'success');
                    
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2000);
                } else {
                    showAlert(data.message, 'danger');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalHTML;
                    
                    // Clear OTP input on error
                    document.getElementById('otp').value = '';
                    document.getElementById('otp').focus();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred. Please try again.', 'danger');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalHTML;
            });
        });

        // Handle resend code
        document.getElementById('resendLink').addEventListener('click', function(e) {
            e.preventDefault();
            
            const email = '<?php echo htmlspecialchars($email); ?>';
            const link = this;
            const originalHTML = link.innerHTML;
            
            link.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            link.style.pointerEvents = 'none';
            
            window.location.href = 'resend-otp.php?email=' + encodeURIComponent(email);
        });
    </script>
</body>
</html>
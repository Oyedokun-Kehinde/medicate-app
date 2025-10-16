<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register – Medicate</title>

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Quicksand:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Favicon Icon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Icons CSS -->
    <link rel="stylesheet" href="assets/fonts/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="assets/fonts/flaticon/flaticon.css">
    <link rel="stylesheet" href="assets/css/ionicons.min.css">
    <!-- Main Style CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Registration CSS -->
    <link rel="stylesheet" href="assets/css/register.css">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="assets/css/responsive.css">

    <style>
        .register-wrapper {
            max-width: 600px;
            margin: 0 auto;
        }
        .register-card {
            padding: 40px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        .required {
            color: #e74c3c;
        }
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        .form-control:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
        }
        .password-strength {
            height: 4px;
            background: #eee;
            border-radius: 2px;
            margin-top: 8px;
            overflow: hidden;
        }
        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: width 0.3s, background-color 0.3s;
        }
        .password-strength-text {
            font-size: 12px;
            margin-top: 5px;
            color: #666;
        }
        .user-type-tabs {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            justify-content: center;
        }
        .user-type-tab input[type="radio"] {
            display: none;
        }
        .user-type-label {
            padding: 12px 24px;
            border: 2px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
        }
        .user-type-tab input[type="radio"]:checked + .user-type-label {
            border-color: #007bff;
            background: #007bff;
            color: white;
        }
        .submit-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .submit-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,123,255,0.3);
        }
        .submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        .register-footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        .register-footer a {
            color: #007bff;
            text-decoration: none;
            font-weight: 600;
        }
        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .register-header h2 {
            font-size: 28px;
            margin-bottom: 10px;
            color: #333;
        }
        .register-header p {
            color: #666;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header id="pq-header" class="pq-header-default">
        <div class="pq-top-header">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="pq-header-contact">
                            <ul>
                                <li><a href="tel:+2348028134942"><i class="fas fa-phone"></i> +234 8028134942</a></li>
                                <li><a href="mailto:info@medicate.com"><i class="fas fa-envelope"></i> info@medicate.com</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="pq-header-auth">
                            <a href="login.php" class="auth-link">Login</a>
                            <a href="register.php" class="auth-link active">Register</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Banner -->
    <div class="pq-breadcrumb" style="background-image:url('assets/images/breadcrumb.jpg');">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="pq-breadcrumb-title">
                        <h2>Register</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Section -->
    <section class="register-section">
        <div class="container">
            <div class="register-wrapper">
                <div class="register-card">
                    <div class="register-header">
                        <h2>Create Your Account</h2>
                        <p>Join our community of patients and healthcare professionals</p>
                    </div>

                    <!-- User Type Tabs -->
                    <div class="user-type-tabs">
                        <div class="user-type-tab">
                            <input type="radio" id="tab-patient" name="user_type" value="patient" checked>
                            <label for="tab-patient" class="user-type-label">
                                <span>Patient</span>
                            </label>
                        </div>
                        <div class="user-type-tab">
                            <input type="radio" id="tab-doctor" name="user_type" value="doctor">
                            <label for="tab-doctor" class="user-type-label">
                                <span>Doctor</span>
                            </label>
                        </div>
                    </div>

                    <!-- Registration Form -->
                    <form id="registerForm" method="POST" action="../controllers/register-controller.php">
                        <input type="hidden" name="user_type" id="user_type" value="patient">

                        <div class="form-group">
                            <label for="full_name" class="form-label">Full Name <span class="required">*</span></label>
                            <input type="text" name="full_name" id="full_name" class="form-control"
                                placeholder="Enter your full name" required>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Email Address <span class="required">*</span></label>
                            <input type="email" name="email" id="email" class="form-control"
                                placeholder="Enter your email" required>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number <span class="required">*</span></label>
                            <input type="tel" name="phone" id="phone" class="form-control" placeholder="08012345678" required>
                        </div>

                        <div class="form-group">
                            <label for="location" class="form-label">Location <span class="required">*</span></label>
                            <input type="text" name="location" id="location" class="form-control"
                                placeholder="e.g: Akure" required>
                        </div>

                        <div class="form-group">
                            <label for="password_hash" class="form-label">Password <span class="required">*</span></label>
                            <input type="password" name="password_hash" id="password_hash" class="form-control"
                                placeholder="Min 8 chars, 1 uppercase, 1 number" required>
                            <div class="password-strength">
                                <div class="password-strength-bar" id="password-strength-bar"></div>
                            </div>
                            <div class="password-strength-text" id="password-strength-text"></div>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password" class="form-label">Confirm Password <span class="required">*</span></label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control"
                                placeholder="Re-enter your password" required>
                        </div>

                        <button type="submit" class="submit-btn">
                            <i class="fas fa-user-plus"></i>
                            <span>Create Account</span>
                        </button>
                    </form>

                    <div class="register-footer">
                        Already have an account? <a href="login.php">Login here</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- JS Files -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <!-- Snackbar Function -->
    <script>
        window.showSnackbar = function(message, type = 'info') {
            let snackbarContainer = document.getElementById('snackbar-container');
            
            if (!snackbarContainer) {
                snackbarContainer = document.createElement('div');
                snackbarContainer.id = 'snackbar-container';
                snackbarContainer.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    z-index: 9999;
                    max-width: 400px;
                `;
                document.body.appendChild(snackbarContainer);
            }
            
            const snackbar = document.createElement('div');
            snackbar.className = `snackbar snackbar-${type}`;
            snackbar.style.cssText = `
                padding: 16px 24px;
                margin-bottom: 10px;
                border-radius: 4px;
                font-size: 14px;
                font-weight: 500;
                box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                animation: slideIn 0.3s ease-out;
                word-wrap: break-word;
            `;
            
            if (type === 'success') {
                snackbar.style.backgroundColor = '#27ae60';
                snackbar.style.color = '#fff';
            } else if (type === 'error') {
                snackbar.style.backgroundColor = '#e74c3c';
                snackbar.style.color = '#fff';
            } else {
                snackbar.style.backgroundColor = '#3498db';
                snackbar.style.color = '#fff';
            }
            
            snackbar.textContent = message;
            snackbarContainer.appendChild(snackbar);
            
            setTimeout(() => {
                snackbar.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => {
                    snackbar.remove();
                }, 300);
            }, 5000);
        };

        if (!document.getElementById('snackbar-styles')) {
            const style = document.createElement('style');
            style.id = 'snackbar-styles';
            style.textContent = `
                @keyframes slideIn {
                    from {
                        transform: translateX(400px);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                
                @keyframes slideOut {
                    from {
                        transform: translateX(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateX(400px);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }
    </script>

    <!-- Password Strength Indicator -->
    <script>
        document.getElementById('password_hash').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('password-strength-bar');
            const strengthText = document.getElementById('password-strength-text');
            
            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            const strengthPercentage = (strength / 4) * 100;
            strengthBar.style.width = strengthPercentage + '%';
            
            if (strength === 0) {
                strengthText.textContent = '';
                strengthBar.style.backgroundColor = '#ccc';
            } else if (strength === 1) {
                strengthText.textContent = 'Weak';
                strengthBar.style.backgroundColor = '#e74c3c';
            } else if (strength === 2) {
                strengthText.textContent = 'Fair';
                strengthBar.style.backgroundColor = '#f39c12';
            } else if (strength === 3) {
                strengthText.textContent = 'Good';
                strengthBar.style.backgroundColor = '#3498db';
            } else {
                strengthText.textContent = 'Strong';
                strengthBar.style.backgroundColor = '#27ae60';
            }
        });
    </script>

    <!-- Form Submission -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('registerForm');
            const userTypeRadios = document.querySelectorAll('input[name="user_type"]');

            userTypeRadios.forEach(radio => {
                radio.addEventListener('change', function () {
                    document.getElementById('user_type').value = this.value;
                });
            });

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const password = document.getElementById('password_hash').value;
                const confirmPassword = document.getElementById('confirm_password').value;
                
                if (password !== confirmPassword) {
                    window.showSnackbar('Passwords do not match', 'error');
                    return;
                }

                const formData = new FormData(form);
                const submitBtn = form.querySelector('.submit-btn');
                const originalHTML = submitBtn.innerHTML;

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Account...';

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.showSnackbar(data.message, 'success');
                        setTimeout(() => {
                            window.location.href = 'login.php?registered=1';
                        }, 2000);
                    } else {
                        window.showSnackbar(data.message || 'Registration failed. Please try again.', 'error');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalHTML;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.showSnackbar('An error occurred. Please try again.', 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalHTML;
                });
            });
        });
    </script>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login – Medicate</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">

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
    <!-- Registration CSS (reuse for login) -->
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
        .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .form-row label {
            margin: 0;
        }
        .form-row a {
            color: #007bff;
            text-decoration: none;
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
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
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
                            <a href="login.php" class="auth-link active">Login</a>
                            <a href="register.php" class="auth-link">Register</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pq-bottom-header pq-has-sticky">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <nav class="navbar navbar-expand-lg navbar-light">
                            <a class="navbar-brand" href="index.php">
                                <img class="img-fluid logo" src="assets/images/logo.png" alt="medicate">
                            </a>
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                                <i class="fas fa-bars"></i>
                            </button>
                        </nav>
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
                        <h2>Login</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Section -->
    <section class="register-section">
        <div class="container">
            <div class="register-wrapper">
                <div class="register-card">
                    <div class="register-header">
                        <h2>Welcome Back</h2>
                        <p>Login to access your account</p>
                    </div>

                    <?php if (isset($_GET['registered'])): ?>
                    <div class="alert alert-success">
                        Registration successful! Please login with your credentials.
                    </div>
                    <?php endif; ?>

                    <!-- Login Form -->
                    <form id="loginForm" method="POST" action="../controllers/login-controller.php">
                        <!-- Email -->
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address <span class="required">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required autofocus>
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label for="password" class="form-label">Password <span class="required">*</span></label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="form-row">
                            <label>
                                <input type="checkbox" name="remember" value="1"> Remember me
                            </label>
                            <a href="forgot-password.php">Forgot Password?</a>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Login</span>
                        </button>
                    </form>

                    <div class="register-footer">
                        Don't have an account? <a href="register.php">Register here</a>
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

    <!-- Login Form Handler -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            if (!form) {
                console.warn('Form #loginForm not found');
                return;
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const submitBtn = form.querySelector('.submit-btn');
                const originalHTML = submitBtn.innerHTML;

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';

                const formData = new FormData(this);

                fetch('../controllers/login-controller.php', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.showSnackbar(data.message || 'Login successful!', 'success');
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 1500);
                    } else {
                        window.showSnackbar(data.message || 'Login failed', 'error');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalHTML;
                    }
                })
                .catch(error => {
                    console.error('Login error:', error);
                    window.showSnackbar('An error occurred. Please try again.', 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalHTML;
                });
            });
        });
    </script>
</body>

</html>
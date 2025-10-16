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

    <!-- Fonts and Icons -->
    <link rel="stylesheet" type="text/css" href="assets/rev/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css">
    <link rel="stylesheet" type="text/css" href="assets/rev/fonts/font-awesome/css/font-awesome.css">

    <!-- REVOLUTION STYLE SHEETS -->
    <link rel="stylesheet" type="text/css" href="assets/rev/css/rs6.css">

    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">

    <!-- Progressbar CSS -->
    <link rel="stylesheet" href="assets/css/progressbar.css">

    <!-- Animation CSS -->
    <link rel="stylesheet" href="assets/css/animations.min.css">

    <!-- Magnific Popup CSS -->
    <link rel="stylesheet" href="assets/css/magnific-popup.min.css">

    <!-- Icons CSS -->
    <link rel="stylesheet" href="assets/fonts/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="assets/fonts/flaticon/flaticon.css">
    <link rel="stylesheet" href="assets/css/ionicons.min.css">
    <link rel="stylesheet" href="assets/fonts/themify-icons/themify-icons.css">

    <!-- Main Style CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Registration CSS (reused for login) -->
    <link rel="stylesheet" href="assets/css/register.css">

    <!-- Responsive CSS -->
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body>
    <!-- Loading -->
    <div id="pq-loading">
        <div id="pq-loading-center">
            <img src="assets/images/logo.png" class="img-fluid" alt="loading">
        </div>
    </div>

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
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <div id="pq-menu-contain" class="pq-menu-contain">
                                    <ul id="pq-main-menu" class="navbar-nav ml-auto">
                                        <li class="menu-item"><a href="index.php">Home</a></li>
                                        <li class="menu-item"><a href="about.php">About Us</a></li>
                                        <li class="menu-item"><a href="services.php">Services</a></li>
                                        <li class="menu-item"><a href="specialists.php">Specialists</a></li>
                                        <li class="menu-item"><a href="case-study.php">Case Studies</a></li>
                                        <li class="menu-item"><a href="blog.php">Blog</a></li>
                                        <li class="menu-item"><a href="faqs.php">FAQs</a></li>
                                        <li class="menu-item"><a href="contact.php">Contact Us</a></li>
                                    </ul>
                                </div>
                            </div>
                            <a href="consultation.php" class="pq-button">
                                <div class="pq-button-block">
                                    <span class="pq-button-text">Consultation</span>
                                    <i class="ion ion-plus-round"></i>
                                </div>
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
                    <nav aria-label="breadcrumb">
                        <div class="pq-breadcrumb-title">
                            <h2>Login</h2>
                        </div>
                        <div class="pq-breadcrumb-container mt-2">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home mr-2"></i>Home</a></li>
                                <li class="breadcrumb-item active">Login</li>
                            </ol>
                        </div>
                    </nav>
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
                        <p>Sign in to access your dashboard and appointments</p>
                    </div>

                    <!-- Login Form -->
                    <form id="loginForm" method="POST" action="../controllers/LoginController.php">
                        <div class="form-group mb-3">
                            <label for="login" class="form-label">Email or Phone</label>
                            <input type="text" name="login" id="login" class="form-control" placeholder="Enter email or phone" required>
                            <div class="error-message text-danger mt-1"></div>
                        </div>
                        <div class="form-group mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                            <div class="error-message text-danger mt-1"></div>
                        </div>

                        <button type="submit" class="pq-button w-100">
                            <div class="pq-button-block">
                                <span class="pq-button-text">Sign In</span>
                            </div>
                        </button>
                    </form>

                    <p class="text-center mt-4">
                        No account? <a href="register.php">Register here</a>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="pq-footer">
        <div class="pq-footer-style-1">
            <div class="pq-subscribe align-items-center">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-12">
                            <div class="pq-subscribe-bg">
                                <div class="row align-items-center">
                                    <div class="col-lg-5">
                                        <div class="pq-subscribe-block">
                                            <img src="assets/images/Subscribe.png" class="pq-subscribe-img img-fluid" alt="medicate-subscribe-image">
                                            <div class="pq-subscribe-details">
                                                <h5>Latest Updates Subscribe To Our Newsletter</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-7 align-self-center">
                                        <div class="pq-subscribe-from">
                                            <form class="form">
                                                <div class="form-fields">
                                                    <input class="w-100 pq-bg-transparent" type="email" name="EMAIL" placeholder="Enter Your Email" required>
                                                    <input type="submit" value="Sign up">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pq-footer-top">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="pq-footer-block">
                                <img src="assets/images/footer_logo.png" class="pq-footer-logo img-fluid" alt="medicate-footer-logo">
                                <p>It helps designers plan out where the content will sit, the content to be written and approved.</p>
                                <div class="pq-footer-social">
                                    <ul>
                                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                        <li><a href="#"><i class="fab fa-google-plus-g"></i></a></li>
                                        <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                        <li><a href="#"><i class="fab fa-pinterest"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="pq-footer-block">
                                <h4 class="footer-title">Quick Links</h4>
                                <div class="menu-useful-links-container">
                                    <ul class="menu">
                                        <li><a href="about.php">About Us</a></li>
                                        <li><a href="contact.php">Contact Us</a></li>
                                        <li><a href="services.php">Our Services</a></li>
                                        <li><a href="specialists.php">Specialists</a></li>
                                        <li><a href="faqs.php">FAQ</a></li>
                                        <li><a href="consultation.php">Appointment</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="pq-footer-block">
                                <h4 class="footer-title">Recent Posts</h4>
                                <div class="pq-footer-recent-post">
                                    <div class="pq-footer-recent-post-media">
                                        <a href="blog-single.php"><img src="assets/images/footer-image/1.jpg" alt=""></a>
                                    </div>
                                    <div class="pq-footer-recent-post-info">
                                        <a href="blog-single.php" class="pq-post-date"><i class="far fa-calendar-alt"></i>December <span>12</span>, 2021</a>
                                        <h6><a href="blog-single.php">Get the Exercise Limited Mobility</a></h6>
                                    </div>
                                </div>
                                <div class="pq-footer-recent-post">
                                    <div class="pq-footer-recent-post-media">
                                        <a href="blog-single.php"><img src="assets/images/footer-image/2.jpg" alt=""></a>
                                    </div>
                                    <div class="pq-footer-recent-post-info">
                                        <a href="blog-single.php" class="pq-post-date"><i class="far fa-calendar-alt"></i>December <span>12</span>, 2021</a>
                                        <h6><a href="blog-single.php">Transfusion strategy and heart surgery</a></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="pq-footer-block">
                                <h4 class="footer-title">Contact Us</h4>
                                <ul class="pq-contact">
                                    <li><a href="tel:+2348028134942"><i class="fas fa-phone"></i><span>+234 8028134942</span></a></li>
                                    <li><a href="mailto:info@medicate.com"><i class="fas fa-envelope"></i><span>info@medicate.com</span></a></li>
                                    <li><i class="fas fa-map-marker"></i><span>Medicate Lab, S5/808B, Oba Adesida Road, Akure, Ondo State</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pq-copyright-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <span class="pq-copyright">Copyright 2025 Medicate All Rights Reserved</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back To Top -->
    <div id="back-to-top">
        <a class="topbtn" id="top" href="#top"><i class="ion-ios-arrow-up"></i></a>
    </div>

    <!-- JS Files -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/wow.min.js"></script>
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <script src="assets/rev/js/rbtools.min.js"></script>
    <script src="assets/rev/js/rs6.min.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/login.js"></script>
    </script>
</body>
</html>
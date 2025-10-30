<?php
require_once 'config/session.php';
require_once 'classes/User.php';
require_once 'config/helpers.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

preventLogin();

//Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $user_type = $_POST['user_type'] ?? '';
    
    // Validate input
    if (!in_array($user_type, ['patient', 'doctor'])) {
        $error = urlencode('Please select a valid account type.');
        header("Location: register.php?error=$error");
        exit();
    } else {
        $user = new User();
        $result = $user->register($email, $password, $user_type);

        if ($result['success']) {
            $msg = urlencode('Registration successful! You can now log in.');
            header("Location: login.php?msg=$msg");
            exit();
        } else {
            $error = urlencode($result['message']);
            header("Location: register.php?error=$error");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register – Medicate</title>
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Main Styles -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/register.css">

    <!-- Icons -->
    <link rel="stylesheet" href="assets/fonts/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="assets/fonts/flaticon/flaticon.css">
    <link rel="stylesheet" href="assets/css/ionicons.min.css">
    <link rel="stylesheet" href="assets/fonts/themify-icons/themify-icons.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>

<body>

    <!-- Preloader -->
    <div id="pq-loading">
        <div id="pq-loading-center">
            <img src="assets/images/logo.png" class="img-fluid" alt="Medicate">
        </div>
    </div>

    <!-- Header Start -->
    <header id="pq-header" class="pq-header-default">
        <div class="pq-top-header">
            <div class="container">
                <div class="row flex-row-reverse">
                    <div class="col-md-6 text-right">
                        <div class="pq-header-social text-right">
                            <ul>
                                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="#"><i class="fab fa-google-plus-g"></i></a></li>
                                <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                <li><a href="#"><i class="fab fa-pinterest"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="pq-header-contact">
                            <ul>
                                <li>
                                    <a href="tel:+2348028134942"><i class="fas fa-phone"></i>
                                        <span> +234 8028134942</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="mailto:info@medicate.com"><i class="fas fa-envelope"></i>
                                        <span>info@medicate.com</span>
                                    </a>
                                </li>
                            </ul>
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
                                <img class="img-fluid logo" src="assets/images/logo.png" alt="Medicate">
                            </a>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <div id="pq-menu-contain" class="pq-menu-contain">
                                    <ul id="pq-main-menu" class="navbar-nav ml-auto">
                                        <li class="menu-item"><a href="index.php">Home</a></li>
                                        <li class="menu-item"><a href="about.php">About Us</a></li>
                                        <li class="menu-item">
                                            <a href="services.php">Services</a>
                                            <i class="fa fa-chevron-down pq-submenu-icon"></i>
                                            <ul class="sub-menu">
                                                <li><a href="services/angioplasty.php">Angioplasty</a></li>
                                                <li><a href="services/cardiology.php">Cardiology</a></li>
                                                <li><a href="services/dental.php">Dental</a></li>
                                                <li><a href="services/endocrinology.php">Endocrinology</a></li>
                                                <li><a href="services/eye-care.php">Eye Care</a></li>
                                                <li><a href="services/neurology.php">Neurology</a></li>
                                                <li><a href="services/orthopaedics.php">Orthopaedics</a></li>
                                                <li><a href="services/rmi.php">RMI</a></li>
                                            </ul>
                                        </li>
                                        <li class="menu-item"><a href="specialists.php">Specialists</a></li>
                                        <li class="menu-item"><a href="case-study.php">Case Studies</a></li>
                                        <li class="menu-item"><a href="blog.php">Blog</a></li>
                                        <li class="menu-item"><a href="faqs.php">FAQs</a></li>
                                        <li class="menu-item"><a href="contact.php">Contact Us</a></li>
                                    </ul>
                                </div>
                            </div>
                            <a href="<?php echo $getStartedUrl; ?>" class="pq-button">
                                <div class="pq-button-block">
                                    <span class="pq-button-text"><?php echo getGetStartedButtonText(); ?></span>
                                    <i class="ion ion-plus-round"></i>
                                </div>
                            </a>
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                aria-expanded="false" aria-label="Toggle navigation">
                                <i class="fas fa-bars"></i>
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Header End -->

    <!-- Breadcrumb -->
    <div class="pq-breadcrumb" style="background-image:url('assets/images/breadcrumb.jpg');">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                        <div class="pq-breadcrumb-title">
                            <h2>Register</h2>
                        </div>
                        <div class="pq-breadcrumb-container mt-2">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home mr-2"></i>Home</a>
                                </li>
                                <li class="breadcrumb-item active">Register</li>
                            </ol>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7 col-lg-6">
                    <div class="auth-container">
                        <h2 class="text-center mb-4">Create Your Account</h2>

                        <form method="POST" id="registerForm">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required
                                    minlength="8">
                                <div id="password-strength" class="password-strength"></div>
                                <!-- Rules will be injected here by JS -->
                            </div>

                            <div class="mb-4">
                                <label for="user_type" class="form-label">I am registering as:</label>
                                <select class="form-control" id="user_type" name="user_type" required>
                                    <option value="">-- Select --</option>
                                    <option value="patient">Patient</option>
                                    <option value="doctor">Doctor</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2">Register</button>
                        </form>

                        <div class="text-center mt-4 footer-link">
                            Already have an account? <a href="login.php">Log in</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Snackbar -->
    <div id="snackbar"></div>

    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/register.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/progressbar.js"></script>
    <script src="assets/js/isotope.pkgd.min.js"></script>
    <script src="assets/js/jquery.countTo.min.js"></script>
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <script src="assets/js/wow.min.js"></script>
    <script src="assets/rev/js/rbtools.min.js"></script>
    <script src="assets/rev/js/rs6.min.js"></script>



    <!-- Preloader Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const loader = document.getElementById('pq-loading');
            if (loader) {
                setTimeout(() => {
                    loader.style.opacity = '0';
                    setTimeout(() => loader.remove(), 500);
                }, 800);
            }
        });
    </script>
</body>

</html>
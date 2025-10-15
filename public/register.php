<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register – Medicate</title>

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

    <!-- Custom Register CSS -->
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
                <div class="row flex-row-reverse">
                    <div class="col-md-6 text-right">
                        <div class="pq-header-auth text-right d-inline-block">
                            <ul class="d-inline-flex align-items-center" style="margin: 0; padding: 0; list-style: none; gap: 20px;">
                                <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                                <li><a href="register.php" class="active"><i class="fas fa-user-plus"></i> Register</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="pq-header-contact">
                            <ul>
                                <li><a href="tel:+2348028134942"><i class="fas fa-phone"></i> +234 8028134942</a></li>
                                <li><a href="mailto:info@medicate.com"><i class="fas fa-envelope"></i> info@medicate.com</a></li>
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
                            <h2>Register</h2>
                        </div>
                        <div class="pq-breadcrumb-container mt-2">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home mr-2"></i>Home</a></li>
                                <li class="breadcrumb-item active">Register</li>
                            </ol>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Section -->
    <section class="register-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="register-card">
                        <div class="register-header text-center">
                            <h2>Create Your Account</h2>
                            <p>Join our community of patients and healthcare professionals</p>
                        </div>

                        <!-- User Type Dropdown -->
                        <div class="form-group mb-4">
                            <label for="user_type_select" class="form-label d-block">I am a:</label>
                            <select name="user_type" id="user_type_select" class="form-control reg-dropdown" required>
                                <option value="patient" selected>Patient</option>
                                <option value="doctor">Doctor</option>
                            </select>
                        </div>

                        <!-- Registration Form -->
                        <form id="registerForm" method="POST" action="auth/register.php">
                            <!-- Common Fields -->
                            <div class="form-group mb-3">
                                <label for="full_name" class="form-label">Full Name</label>
                                <input type="text" name="full_name" id="full_name" class="form-control" placeholder="Enter your full name" required>
                                <div class="error-message text-danger mt-1"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
                                <div class="error-message text-danger mt-1"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <div class="input-group">
                                    <!-- <span class="input-group-text">+234</span> -->
                                    <input type="tel" name="phone" id="phone" class="form-control" placeholder="Enter your phone number" required>
                                </div>
                                <div class="error-message text-danger mt-1"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select name="gender" id="gender" class="form-control" required>
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                                <div class="error-message text-danger mt-1"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="location" class="form-label">Location (City, State)</label>
                                <input type="text" name="location" id="location" class="form-control" placeholder="e.g., Akure, Ondo State" required>
                                <div class="error-message text-danger mt-1"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <!-- <span class="input-group-text"><i class="fas fa-lock"></i></span> -->
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Min 8 chars, 1 uppercase, 1 number" required>
                                </div>
                                <!-- <div class="password-hint mt-1">
                                    <i class="fas fa-lock me-2"></i> Min 8 chars, 1 uppercase, 1 number
                                </div> -->
                                <div class="error-message text-danger mt-1"></div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <!-- <span class="input-group-text"><i class="fas fa-lock"></i></span> -->
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Re-enter your password" required>
                                </div>
                                <div class="error-message text-danger mt-1"></div>
                            </div>

                            <!-- Doctor-Specific Fields -->
                            <div id="doctorFields" class="doctor-fields" style="display:none;">
                                <h4 class="mb-3">Professional Details</h4>
                                <div class="form-group mb-3">
                                    <label for="medical_license" class="form-label">Medical License Number</label>
                                    <input type="text" name="medical_license" id="medical_license" class="form-control" placeholder="Enter your medical license number" required>
                                    <div class="error-message text-danger mt-1"></div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="specialization" class="form-label">Specialization</label>
                                    <input type="text" name="specialization" id="specialization" class="form-control" placeholder="e.g., Cardiology, Neurology" required>
                                    <div class="error-message text-danger mt-1"></div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="years_of_experience" class="form-label">Years of Experience</label>
                                    <select name="years_of_experience" id="years_of_experience" class="form-control" required>
                                        <option value="">Select Years of Experience</option>
                                        <?php for ($i = 1; $i <= 30; $i++): ?>
                                            <option value="<?= $i ?>"><?= $i ?> year<?= $i > 1 ? 's' : '' ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <div class="error-message text-danger mt-1"></div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="bio" class="form-label">Bio / About (Optional)</label>
                                    <textarea name="bio" id="bio" class="form-control" placeholder="Tell us about yourself..."></textarea>
                                </div>
                            </div>

                            <button type="submit" class="pq-button w-100">
                                <div class="pq-button-block">
                                    <span class="pq-button-text">Register</span>
                                </div>
                            </button>
                        </form>

                        <p class="text-center mt-4">
                            Already have an account? <a href="login.php">Login here</a>
                        </p>
                    </div>
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
                                            <form id="form" class="form">
                                                <div class="form-fields">
                                                    <input class="w-100 pq-bg-transparent" type="email" name="EMAIL" placeholder="Enter Your Email" required="">
                                                    <input class="" type="submit" value="Sign up">
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
                        <div class="col-xl-3  col-md-6">
                            <div class="pq-footer-block">
                                <h4 class="footer-title">Our Courses</h4>
                                <div class="menu-useful-links-container">
                                    <ul id="menu-useful-links" class="menu">
                                        <li><a href="about.php">About Us</a></li>
                                        <li><a href="contact.php">Contact Us</a></li>
                                        <li><a href="services.php">Our Services</a></li>
                                        <li><a href="our-process.html">Our Process</a></li>
                                        <li><a href="specialists.php">Doctors 1</a></li>
                                        <li><a href="faq.php">FAQ</a></li>
                                        <li><a href="our-doctor.html">Doctors 2</a></li>
                                        <li><a href="case-study.php">Departments</a></li>
                                        <li><a href="consultation.php">Appointment </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3  col-md-6">
                            <div class="pq-footer-block">
                                <h4 class="footer-title">Recent Posts</h4>
                                <div class="pq-footer-recent-post">
                                    <div class="pq-footer-recent-post-media">
                                        <a href="blog-single.php"> <img src="assets/images/footer-image/1.jpg" alt=""></a>
                                    </div>
                                    <div class="pq-footer-recent-post-info">
                                        <a href="blog-single.php" class="pq-post-date"> <i class="far fa-calendar-alt"></i>December <span>12</span>, 2021 </a>
                                        <h6><a href="blog-single.php">Get the Exercise Limited Mobility</a></h6>
                                    </div>
                                </div>
                                <div class="pq-footer-recent-post">
                                    <div class="pq-footer-recent-post-media">
                                        <a href="blog-single.php"> <img src="assets/images/footer-image/2.jpg" alt=""></a>
                                    </div>
                                    <div class="pq-footer-recent-post-info">
                                        <a href="blog-single.php" class="pq-post-date"> <i class="far fa-calendar-alt"></i>December <span>12</span>, 2021 </a>
                                        <h6><a href="blog-single.php">Transfusion strategy and heart surgery</a></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3  col-md-6">
                            <div class="pq-footer-block">
                                <h4 class="footer-title">Contact Us</h4>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <ul class="pq-contact">
                                            <li> <a href="tel:+2348028134942"><i class="fas fa-phone"></i>
                                                    <span> +234 8028134942</span>
                                                </a> </li>
                                            <li> <a href="mailto:info@medicate.com"><i class="fas fa-envelope"></i><span>info@medicate.com</span></a>
                                            </li>
                                            <li> <i class="fas fa-map-marker"></i> <span>
                                                    Medicate Lab, S5/808B, Oba Adesida Raod, Akure, Ondo State </span> </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pq-copyright-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center "> <span class="pq-copyright"> Copyright 2022 medicate All Rights Reserved</span> </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!--Back To Top start-->
    <div id="back-to-top">
        <a class="topbtn" id="top" href="#top"> <i class="ion-ios-arrow-up"></i> </a>
    </div>
    <!--Back To Top End-->

    <!-- JS Files -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/progressbar.js"></script>
    <script src="assets/js/isotope.pkgd.min.js"></script>
    <script src="assets/js/jquery.countTo.min.js"></script>
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <script src="assets/js/wow.min.js"></script>
    <script src="assets/rev/js/rbtools.min.js"></script>
    <script src="assets/rev/js/rs6.min.js"></script>
    <script src="assets/js/rev-custom.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/register.js"></script>
</body>
</html>
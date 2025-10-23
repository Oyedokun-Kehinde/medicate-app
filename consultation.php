<?php
session_start();
require_once 'config/helpers.php';
require_once 'config/database.php';

// Fetch all services for dropdown
try {
    $stmt = $pdo->query("SELECT id, name FROM services ORDER BY name ASC");
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $services = [];
}

// Fetch all doctors for dropdown
try {
    $stmt = $pdo->query("
        SELECT u.id, dp.full_name, dp.specialization 
        FROM users u 
        JOIN doctor_profiles dp ON u.id = dp.user_id 
        WHERE u.user_type = 'doctor' AND u.is_verified = 1
        ORDER BY dp.full_name ASC
    ");
    $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $doctors = [];
}

$getStartedUrl = getGetStartedUrl();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Book Consultation – Medicate</title>

    <!-- Main Style CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
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
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="assets/css/responsive.css">
    
    <style>
        .form-error {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
            display: block;
        }
        .form-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .registration-prompt {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            text-align: center;
        }
        .registration-prompt h5 {
            color: white;
            margin-bottom: 15px;
        }
        .registration-prompt p {
            margin-bottom: 15px;
        }
        .registration-prompt .pq-button {
            margin: 0 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .required-field::after {
            content: " *";
            color: #dc3545;
        }
    </style>
</head>

<body>
    <!--loading start-->
    <div id="pq-loading">
        <div id="pq-loading-center">
            <img src="assets/images/logo.png" class="img-fluid" alt="loading">
        </div>
    </div>
    <!--loading End-->

    <!--=================================
    header start-->
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
                                    <a href="mailto:info@medicate.com"><i
                                            class="fas fa-envelope"></i><span>info@medicate.com</span></a>
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
                                <img class="img-fluid logo" src="assets/images/logo.png" alt="medicate">
                            </a>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <div id="pq-menu-contain" class="pq-menu-contain">
                                    <ul id="pq-main-menu" class="navbar-nav ml-auto">
                                        <li class="menu-item">
                                            <a href="index.php">Home</a>
                                        </li>
                                        <li class="menu-item">
                                            <a href="about.php">About Us</a>
                                        </li>
                                        <li class="menu-item">
                                            <a href="services.php">Services</a><i
                                                class="fa fa-chevron-down pq-submenu-icon"></i>
                                            <ul class="sub-menu">
                                                <li class="menu-item">
                                                    <a href="services/angioplasty.php">Angioplasty</a>
                                                </li>
                                                <li class="menu-item">
                                                    <a href="services/cardiology.php">Cardiology</a>
                                                </li>
                                                <li class="menu-item">
                                                    <a href="services/dental.php">Dental</a>
                                                </li>
                                                <li class="menu-item">
                                                    <a href="services/endocrinology.php">Endocrinology</a>
                                                </li>
                                                <li class="menu-item">
                                                    <a href="services/eye-care.php">Eye Care</a>
                                                </li>
                                                <li class="menu-item">
                                                    <a href="services/neurology.php">Neurology</a>
                                                </li>
                                                <li class="menu-item">
                                                    <a href="services/orthopaedics.php">Orthopaedics</a>
                                                </li>
                                                <li class="menu-item">
                                                    <a href="services/rmi.php">RMI</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="menu-item">
                                            <a href="specialists.php">Specialists</a>
                                        </li>
                                        <li class="menu-item">
                                            <a href="case-study.php">Case Studies</a>
                                        </li>
                                        <li class="menu-item">
                                            <a href="blog.php">Blog</a>
                                        </li>
                                        <li class="menu-item">
                                            <a href="faqs.php">FAQs</a>
                                        </li>
                                        <li class="menu-item">
                                            <a href="contact.php">Contact Us</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="pq-menu-search-block">
                                <a href="javascript:void(0)" id="pq-seacrh-btn"><i class="ti-search"></i></a>
                                <div class="pq-search-form">
                                    <form role="search" method="get" class="search-form" action="">
                                        <label>
                                            <span class="screen-reader-text">Search for:</span>
                                            <input type="search" class="search-field" placeholder="Enter a search term"
                                                value="" name="s">
                                        </label>
                                        <button type="submit" class="search-submit"><span
                                                class="screen-reader-text">Search</span></button>
                                    </form>
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
    <!--Header End-->

    <!--=================================
    Banner start-->
    <div class="pq-breadcrumb" style="background-image:url('assets/images/breadcrumb.jpg');">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                        <div class="pq-breadcrumb-title">
                            <h2>Book Consultation</h2>
                        </div>
                        <div class="pq-breadcrumb-container mt-2">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php"><i
                                            class="fas fa-home mr-2"></i>Home</a></li>
                                <li class="breadcrumb-item active">Consultation</li>
                            </ol>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!--=================================
    Banner end-->

    <!--=================================
    appointment-form start-->
    <div class="appoinment-form appointment-page pq-pt-130 pb-0">
        <div class="container">
            <div class="row">
                <div class="col-xl-6">
                    <div class="appoinment-box">
                        <!-- Success Message Area -->
                        <div id="form-messages"></div>

                        <form id="consultation-form" class="pq-applyform" method="post" action="submit-consultation.php">
                            <div class="pq-appointment">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="pq-appointment-title required-field">Select Service/Department</label>
                                            <select name="service_id" id="service_id" class="form-select" required>
                                                <option value="">-- Select Department --</option>
                                                <?php foreach ($services as $service): ?>
                                                    <option value="<?php echo $service['id']; ?>">
                                                        <?php echo htmlspecialchars($service['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="form-error" id="error-service_id"></span>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="pq-appointment-title">Choose Doctor (Optional)</label>
                                            <select name="doctor_id" id="doctor_id" class="form-select">
                                                <option value="" selected >Select Specialist </option>
                                                <?php foreach ($doctors as $doctor): ?>
                                                    <option value="<?php echo $doctor['id']; ?>">
                                                        <?php echo htmlspecialchars($doctor['full_name']); ?>
                                                        <?php if ($doctor['specialization']): ?>
                                                            - <?php echo htmlspecialchars($doctor['specialization']); ?>
                                                        <?php endif; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="form-error" id="error-doctor_id"></span>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input type="text" id="full_name" name="full_name"
                                                class="form-control pq-bg-white" placeholder="Your Full Name *" required>
                                            <span class="form-error" id="error-full_name"></span>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <input type="tel" id="phone" name="phone" class="form-control pq-bg-white"
                                                placeholder="Phone Number *" required>
                                            <span class="form-error" id="error-phone"></span>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <input type="email" id="email" name="email" class="form-control pq-bg-white"
                                                placeholder="Email Address *" required>
                                            <span class="form-error" id="error-email"></span>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <input type="date" id="consultation_date" name="consultation_date"
                                                class="form-control pq-bg-white" 
                                                min="<?php echo date('Y-m-d'); ?>" required>
                                            <span class="form-error" id="error-consultation_date"></span>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <input type="time" id="consultation_time" name="consultation_time"
                                                class="form-control pq-bg-white" placeholder="Preferred Time *" required>
                                            <span class="form-error" id="error-consultation_time"></span>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <textarea cols="40" id="message" name="message" rows="5"
                                                class="form-control pq-bg-white"
                                                placeholder="Tell us about your health concern... *" required></textarea>
                                            <span class="form-error" id="error-message"></span>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <button type="submit" class="pq-button form-btn" id="submit-btn">
                                            <div class="pq-button-block">
                                                <span class="pq-button-text me-0">SUBMIT REQUEST</span>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="appointment-img text-center">
                        <img src="assets/images/appoinment.png" alt="consultation" class="img-a img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--=================================
    appointment-form end-->

    <!--=================================
    contact-healthcare start-->
    <section class="contact-us pq-bg-primary-dark pq-bg-img-1">
        <div class="container">
            <div class="row">
                <div class="col-xl-7">
                    <h6 class="pq-title">A great place of medical hospital center & health care</h6>
                </div>
                <div class="col-xl-5 d-xl-flex justify-content-xl-end align-items-center text-left mt-xl-0 mt-4">
                    <a class="pq-button pq-button-flat" href="contact.php">
                        <div class="pq-button-block">
                            <span class="pq-button-text">Contact Us</span>
                            <i class="ion ion-plus-round"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!--=================================
    contact-healthcare end-->

     <!-- =========================
     Footer start
     ============================== -->

    <footer id="pq-footer">
        <div class="pq-footer-style-1">
            <div class="pq-subscribe align-items-center">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-12">
                            <div class="pq-subscribe-bg">
                                <div class="row align-items-center">
                                    <div class="col-lg-5">
                                        <div class="pq-subscribe-block"> <img src="assets/images/Subscribe.png"
                                                class="pq-subscribe-img img-fluid" alt="medicate-subscribe-image">
                                            <div class="pq-subscribe-details">
                                                <h5>Latest Updates Subscribe To Our Newsletter</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-7 align-self-center">
                                        <div class="pq-subscribe-from">
                                            <form id="form" class="form">
                                                <div class="form-fields">
                                                    <input class="w-100 pq-bg-transparent" type="email" name="EMAIL"
                                                        placeholder="Enter Your Email" required="">
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
                                <img src="assets/images/footer_logo.png" class="pq-footer-logo img-fluid"
                                    alt="medicate-footer-logo">
                                <p>It helps designers plan out where the content will sit, the content to be written and
                                    approved.</p>

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
                                        <li><a href="specialists.php">Services</a></li>
                                        <li><a href="faq.php">FAQ</a></li>
                                        <li><a href="our-doctor.html">FAQs</a></li>
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
                                        <a href="blog-single.php"> <img src="assets/images/footer-image/1.jpg"
                                                alt=""></a>
                                    </div>
                                    <div class="pq-footer-recent-post-info">
                                        <a href="blog-single.php" class="pq-post-date"> <i
                                                class="far fa-calendar-alt"></i>December <span>12</span>, 2021 </a>
                                        <h6><a href="blog-single.php">Get the Exercise Limited
                                                Mobility</a>
                                        </h6>
                                    </div>
                                </div>
                                <div class="pq-footer-recent-post">
                                    <div class="pq-footer-recent-post-media">
                                        <a href="blog-single.php"> <img src="assets/images/footer-image/2.jpg"
                                                alt=""></a>
                                    </div>
                                    <div class="pq-footer-recent-post-info">
                                        <a href="blog-single.php" class="pq-post-date"> <i
                                                class="far fa-calendar-alt"></i>December <span>12</span>, 2021 </a>
                                        <h6><a href="blog-single.php">Transfusion strategy and
                                                heart
                                                surgery</a></h6>
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
                                            <li> <a href="mailto:info@medicate.com"><i
                                                        class="fas fa-envelope"></i><span>info@medicate.com</span></a>
                                            </li>
                                            <li> <i class="fas fa-map-marker"></i> <span>
                                                    Medicate Lab, S5/808B, Oba Adesida Road, Akure, Ondo State </span>
                                            </li>
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
                        <div class="col-md-12 text-center "> <span class="pq-copyright"> Copyright 2022 medicate All
                                Rights
                                Reserved</span> </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--Footer End-->

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

    <script>
        jQuery(window).on('load', function (e) {
            jQuery(".pq-applyform .form-btn").click(function () {
                var first_name = jQuery('#first-name').val();
                var doctor_name = jQuery('#doctor-name').val();
                var disease_name = jQuery('#disease-name').val();
                var email = jQuery('#e-mail').val();

                var result;

                jQuery('.pq-applyform .pq-message').remove();
                jQuery('.pq-applyform .pq-thank-you-message').remove();

                if (first_name == '' || first_name == undefined) {
                    jQuery("<span class='pq-name-error-message pq-message'>Please fill the field</span>").insertAfter('.pq-applyform .name-field');
                    result = false;
                }
                else {
                    jQuery('.pq-name-error-message').remove();
                    result = true;
                }

                if (email == '' || email == undefined) {
                    jQuery("<span class='pq-email-error-message pq-message'>Please fill the field</span>").insertAfter('.pq-applyform .e-mail-field');
                    result = false;
                }
                else {
                    jQuery('.pq-email-error-message').remove();
                    result = true;
                }

                if (doctor_name == '' || doctor_name == undefined) {
                    jQuery("<span class='pq-doctor-name-error-message pq-message'>Please fill the field</span>").insertAfter('.pq-applyform .doctor-name-field');
                    result = false;
                }
                else {
                    jQuery('.pq-doctor-name-error-message').remove();
                    result = true;
                }

                if (disease_name == '' || disease_name == undefined) {
                    jQuery("<span class='pq-disease-name-error-message pq-message'>Please fill the field</span>").insertAfter('.pq-applyform #disease-name');
                    result = false;
                }
                else {
                    jQuery('.pq-disease-name-error-message').remove();
                    result = true;
                }

                if (result == true) {
                    var email = jQuery("#email").text();
                    event.preventDefault();
                    jQuery.ajax({
                        type: "POST",
                        url: "mail.php",
                        data: { 'email': email },
                        success: function () {
                            jQuery("<span class='pq-thank-you-message pq-text-white ms-5'> Thank You For Filling The form</span>").insertAfter('.pq-applyform .pq-button');
                        }
                    });
                }
            });
        });
    </script>
</body>

<script>'undefined' === typeof _trfq || (window._trfq = []); 'undefined' === typeof _trfd && (window._trfd = []), _trfd.push({ 'tccl.baseHost': 'secureserver.net' }, { 'ap': 'cpbh-mt' }, { 'server': 'sg2plmcpnl492384' }, { 'dcenter': 'sg2' }, { 'cp_id': '9858662' }, { 'cp_cache': '' }, { 'cp_cl': '8' })  </script>
<script src='../../../../img1.wsimg.com/signals/js/
      
</html>
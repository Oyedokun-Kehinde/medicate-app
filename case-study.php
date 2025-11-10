<?php
session_start();
require_once 'config/helpers.php';
$getStartedUrl = getGetStartedUrl();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Case Studies – Medicate </title>

    <!-- Main Style CSS -->
    <link rel="stylesheet" href="assets/css/style.css">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

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
    <header id="pq-header" class="pq-header-default ">
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
                        <div class="pq-header-contact ">
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
                                        <li class="menu-item ">
                                            <a href="index.php">Home</a>
                                        </li>
                                        <li class="menu-item">
                                            <a href="about.php">About Us </a>
                                        </li>
                                        <li class="menu-item">
                                            <a href="services.php">Services</a><i
                                                class="fa fa-chevron-down pq-submenu-icon"></i>
                                            <ul class="sub-menu">
                                                <li class="menu-item ">
                                                    <a href="services/angioplasty.php">Angioplasty </a>
                                                </li>
                                                <li class="menu-item ">
                                                    <a href="services/cardiology.php">Cardiology</a>
                                                </li>
                                                <li class="menu-item ">
                                                    <a href="services/dental.php">Dental </a>
                                                </li>
                                                <li class="menu-item">
                                                    <a href="services/endocrinology.php">Endocrinology</a>
                                                </li>
                                                <li class="menu-item ">
                                                    <a href="services/eye-care.php">Eye Care </a>
                                                </li>
                                                <li class="menu-item ">
                                                    <a href="services/neurology.php">Neurology </a>
                                                </li>
                                                <li class="menu-item ">
                                                    <a href="services/orthopaedics.php">Orthopaedics </a>
                                                </li>
                                                <li class="menu-item">
                                                    <a href="services/rmi.php">RMI </a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="menu-item ">
                                            <a href="specialists.php">Specialists </a>
                                        </li>
                                        <li class="menu-item current-menu-item">
                                            <a href="case-study.php">Case Studies </a>
                                        </li>
                                        <li class="menu-item ">
                                            <a href="blog.php">Blog</a>
                                        </li>
                                        <li class="menu-item ">
                                            <a href="faqs.php">FAQs </a>
                                        </li>
                                        <li class="menu-item ">
                                            <a href="contact.php">Contact Us</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="pq-menu-search-block">
                                <a href="javascript:void(0)" id="pq-seacrh-btn"><i class="ti-search"></i></a>
                                <div class="pq-search-form">
                                    <form role="search" method="get" class="search-form" action="search-results.php">
                                        <label>
                                            <span class="screen-reader-text"> Search for:</span>
                                            <input type="search" class="search-field" placeholder="Enter a search term"
                                                value="" name="s">
                                        </label>
                                        <button type="submit" class="search-submit"><span
                                                class="screen-reader-text">Search</span></button>
                                    </form>
                                </div>
                            </div>
                            <a href="<?php echo $getStartedUrl; ?>" class="pq-button pq-cta-button">
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
    <!--Header End -->

    <!--=================================
         Banner start-->
    <div class="pq-breadcrumb" style="background-image:url('assets/images/breadcrumb.jpg');">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                        <div class="pq-breadcrumb-title">
                            <h2>Case Study </h2>
                        </div>
                        <div class="pq-breadcrumb-container mt-2">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home mr-2"></i>Home</a>
                                </li>
                                <li class="breadcrumb-item active">Case Study </li>
                            </ol>
                        </div>
                    </nav>
                </div>
                <div class="col-lg-4">
                    <div class="pq-breadcrumb-img text-right wow fadeInRight"></div>
                </div>
            </div>
        </div>
    </div>
    <!--=================================
         Banner end-->

    <!--=================================
         porfolio start-->
    <section class="portfolio pq-pb-210">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pq-grid-container">
                        <div class="pq-filters b74eca6">
                            <div class="filters pq-filter-button-group">
                                <ul>
                                    <li class="active pq-filter-btn" data-filter="*">All</li>
                                    <li class="pq-filter-btn" data-filter=".30">Clinic</li>
                                    <li class="pq-filter-btn" data-filter=".36">Family</li>
                                    <li class="pq-filter-btn" data-filter=".32">Laboratory</li>
                                    <li class="pq-filter-btn" data-filter=".33">Pediatrics</li>
                                    <li class="pq-filter-btn" data-filter=".34">Therapy</li>
                                </ul>
                            </div>
                        </div>
                        <div class="pq-masonry " data-next_items="3" data-initial_items="6">
                            <div class="grid-sizer pq-col-3"></div>
                            <div class="pq-masonry-item pq-filter-items  ipq-lg-6 30 ">
                                <div class="pq-portfoliobox pq-style-1">
                                    <div class="pq-portfolio-img"> <img src="assets/images/portfolio/slider/1.jpg"
                                            class="img-fluid" alt="">
                                        <a href=case-study-details.php">
                                            <div class="pq-portfolio-icon"><i class="ion ion-plus-round"></i></div>
                                        </a>
                                    </div>
                                    <div class="pq-portfolio-info"> <span>Clinic</span>
                                        <h5><a href="case-study-details.php">Home Visit</a></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="pq-masonry-item pq-filter-items  ipq-lg-3 36 ">
                                <div class="pq-portfoliobox pq-style-1">
                                    <div class="pq-portfolio-img"> <img src="assets/images/portfolio/slider/2.jpg"
                                            class="img-fluid" alt="">
                                        <a href="case-study-details.php">
                                            <div class="pq-portfolio-icon"><i class="ion ion-plus-round"></i></div>
                                        </a>
                                    </div>
                                    <div class="pq-portfolio-info"> <span>Family</span>
                                        <h5><a href="case-study-details.php">Investigations</a></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="pq-masonry-item pq-filter-items  ipq-lg-3 33 ">
                                <div class="pq-portfoliobox pq-style-1">
                                    <div class="pq-portfolio-img"> <img src="assets/images/portfolio/slider/3.jpg"
                                            class="img-fluid" alt="">
                                        <a href="case-study-details.php">
                                            <div class="pq-portfolio-icon"><i class="ion ion-plus-round"></i></div>
                                        </a>
                                    </div>
                                    <div class="pq-portfolio-info"> <span>Pediatrics</span>
                                        <h5><a href="case-study-details.php">Surgical</a></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="pq-masonry-item pq-filter-items  ipq-lg-6 30  36  32  33  34 ">
                                <div class="pq-portfoliobox pq-style-1">
                                    <div class="pq-portfolio-img"> <img src="assets/images/portfolio/slider/4.jpg"
                                            class="img-fluid" alt="">
                                        <a href="case-study-details.php">
                                            <div class="pq-portfolio-icon"><i class="ion ion-plus-round"></i></div>
                                        </a>
                                    </div>
                                    <div class="pq-portfolio-info"> <span>Clinic</span>
                                        <h5><a href="case-study-details.php">Pediatrics Care</a></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="pq-masonry-item pq-filter-items  ipq-lg-3 30  36  33 ">
                                <div class="pq-portfoliobox pq-style-1">
                                    <div class="pq-portfolio-img"> <img src="assets/images/portfolio/slider/5.jpg"
                                            class="img-fluid" alt="">
                                        <a href="case-study-details.php">
                                            <div class="pq-portfolio-icon"><i class="ion ion-plus-round"></i></div>
                                        </a>
                                    </div>
                                    <div class="pq-portfolio-info"> <span>Clinic</span>
                                        <h5><a href="case-study-details.php">Cardiology</a></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="pq-masonry-item pq-filter-items  ipq-lg-3 33  34 ">
                                <div class="pq-portfoliobox pq-style-1">
                                    <div class="pq-portfolio-img"><img src="assets/images/portfolio/slider/6.jpg"
                                            class="img-fluid" alt="">
                                        <a href="case-study-details.php">
                                            <div class="pq-portfolio-icon"><i class="ion ion-plus-round"></i></div>
                                        </a>
                                    </div>
                                    <div class="pq-portfolio-info"> <span>Pediatrics</span>
                                        <h5><a href="case-study-details.php">Treatments</a></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="pq-masonry-item pq-filter-items  ipq-lg-6 36  32  34 ">
                                <div class="pq-portfoliobox pq-style-1">
                                    <div class="pq-portfolio-img"><img src="assets/images/portfolio/slider/7.jpg"
                                            class="img-fluid" alt="">
                                        <a href="case-study-details.php">
                                            <div class="pq-portfolio-icon"><i class="ion ion-plus-round"></i></div>
                                        </a>
                                    </div>
                                    <div class="pq-portfolio-info"> <span>Family</span>
                                        <h5><a href="case-study-details.php">Quality Therapy</a></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="pq-masonry-item pq-filter-items  ipq-lg-3 32  33  34 ">
                                <div class="pq-portfoliobox pq-style-1">
                                    <div class="pq-portfolio-img"> <img src="assets/images/portfolio/slider/8.jpg"
                                            class="img-fluid" alt="">
                                        <a href="case-study-details.php">
                                            <div class="pq-portfolio-icon"><i class="ion ion-plus-round"></i></div>
                                        </a>
                                    </div>
                                    <div class="pq-portfolio-info"> <span>Laboratory</span>
                                        <h5><a href="case-study-details.php">Orthodontics</a></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="pq-masonry-item pq-filter-items  ipq-lg-3 30  36  33  34 ">
                                <div class="pq-portfoliobox pq-style-1">
                                    <div class="pq-portfolio-img"><img src="assets/images/portfolio/slider/9.jpg"
                                            class="img-fluid" alt="">
                                        <a href="case-study.php">
                                            <div class="pq-portfolio-icon"><i class="ion ion-plus-round"></i></div>
                                        </a>
                                    </div>
                                    <div class="pq-portfolio-info"> <span>Clinic</span>
                                        <h5><a href="case-study-details.php">Management</a></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pq-btn-load-container text-center">
                        <a id="showMore" class="pq-button" href="consultation.php">
                            <div class="pq-button-block"> <span class="pq-button-text">Load More</span> <i
                                    class="ion ion-plus-round"></i> </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--=================================
         porfolio end
     ================================= -->


    <!--=================================
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
                            <div class="pq-footer-block"> <img src="assets/images/footer_logo.png"
                                    class="pq-footer-logo img-fluid" alt="medicate-footer-logo">
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
                                        <li><a href="about-us.html">About Us</a></li>
                                        <li><a href="contact.php">Contact Us</a></li>
                                        <li><a href="services.php">Our Services</a></li>
                                        <li><a href="our-process.html">Our Process</a></li>
                                        <li><a href="doctor-1.html">Services</a></li>
                                        <li><a href="faq.html">FAQ</a></li>
                                        <li><a href="our-doctor.html">FAQs</a></li>
                                        <li><a href="case-study.php">Departments</a></li>
                                        <li><a href="consultation.php">Events</a></li>
                                        <li><a href="our-plan.html">Member</a></li>
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
                                                Mobility</a></h6>
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
                                                heart surgery</a></h6>
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
                                                    <span>+234 8028134942</span>
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
                                Rights Reserved</span> </div>
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
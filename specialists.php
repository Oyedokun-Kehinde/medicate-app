<?php
session_start();
require_once 'config/helpers.php';
$getStartedUrl = getGetStartedUrl();
?>

<!doctype html>
<html lang="en">

<head>
   <!-- Required meta tags -->
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <link rel="shortcut icon" href="images/favicon.ico">
   <title> Specialists - Medicate </title>

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
                           <a href="tel:+23480281492"><i class="fas fa-phone"></i>
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
                              <li class="menu-item ">
                                 <a href="about.php">About Us </a>
                              </li>
                              <li class="menu-item ">
                                 <a href="services.php">Services</a><i class="fa fa-chevron-down pq-submenu-icon"></i>
                                 <ul class="sub-menu">
                                    <li class="menu-item ">
                                       <a href="../public/services/angioplasty.php">Angioplasty </a>
                                    </li>
                                    <li class="menu-item ">
                                       <a href="../public/services/cardiology">Cardiology</a>
                                    </li>
                                    <li class="menu-item ">
                                       <a href="../public/services/dental.php">Dental </a>
                                    </li>
                                    <li class="menu-item">
                                       <a href="../public/services/endocrinology.php">Endocrinology</a>
                                    </li>
                                    <li class="menu-item ">
                                       <a href="../public/services/eye-care.php">Eye Care </a>
                                    </li>
                                    <li class="menu-item ">
                                       <a href="../public/services/neurology.php">Neurology </a>
                                    </li>
                                    <li class="menu-item ">
                                       <a href="../public/services/orthopaedics.php">Orthopaedics </a>
                                    </li>
                                    <li class="menu-item">
                                       <a href="../public/services/rmi.php">RMI </a>
                                    </li>
                                 </ul>
                              </li>
                              <li class="menu-item current-menu-item">
                                 <a href="specialists.php">Specialists </a>
                              </li>
                              <li class="menu-item ">
                                 <a href="blog.php">Blog</a>
                              </li>
                              <li class="menu-item ">
                                 <a href="case-study.php">Case Studies </a>
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
                                 <input type="search" class="search-field" placeholder="Enter a search term" value=""
                                    name="s">
                              </label>
                              <button type="submit" class="search-submit"><span
                                    class="screen-reader-text">Search</span></button>
                           </form>
                        </div>
                     </div>
                     <a href="consultation.php" class="pq-button">
                        <div class="pq-button-block">
                           <span class="pq-button-text">Consultation </span>
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
                     <h2> Our Specialists </h2>
                  </div>
                  <div class="pq-breadcrumb-container mt-2">
                     <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home mr-2"></i>Home</a></li>
                        <li class="breadcrumb-item active">Specialists</li>
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
         our-team start-->
   <section class="our-team pq-pb-100">
      <div class="container">
         <div class="row">
            <div class="col-lg-12">
               <div class="pq-section pq-style-1 text-center"> <span class="pq-section-sub-title">Our Doctors</span>
                  <h5 class="pq-section-title">Meet Our Specialists</h5>
               </div>
            </div>
         </div>
         <div class="row justify-content-center ">
            <div class="col-xl-4 col-md-6 col-sm-12">
               <div class="pq-team pq-team-style-1 pb-lg-5 ">
                  <div class="pq-team-box pq-style-1">
                     <div class="pq-team-img"> <img src="assets/images/team/2.jpg" class="img-fluid" alt="team-image">
                        <div class="pq-team-social">
                           <ul>
                              <li>
                                 <a class="facebook" href="#" target="_blank"> <span class="sr-only">Facebook</span> <i
                                       class="fab fa-facebook"></i> </a>
                              </li>
                              <li>
                                 <a class="twitter" href="#" target="_blank"> <span class="sr-only">Twitter</span> <i
                                       class="fab fa-twitter"></i> </a>
                              </li>
                              <li>
                                 <a class="google-plus" href="#" target="_blank"> <span
                                       class="sr-only">Google-plus</span> <i class="fab fa-google-plus"></i> </a>
                              </li>
                           </ul>
                        </div>
                     </div>
                     <div class="pq-team-info">
                        <h5 class="pq-member-name">
                           <a href="specialist-single.php">
                              Naidan Smith </a>
                        </h5> <span class="pq-team-designation">Pediatric Clinic</span>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-xl-4 col-md-6  col-sm-12 mt-lg-0 mt-md-0 mt-4">
               <div class="pq-team-box pq-style-1">
                  <div class="pq-team-img"> <img src="assets/images/team/5.jpg" class="img-fluid" alt="team-image">
                     <div class="pq-team-social">
                        <ul>
                           <li>
                              <a class="facebook" href="#" target="_blank"> <span class="sr-only">Facebook</span> <i
                                    class="fab fa-facebook"></i> </a>
                           </li>
                           <li>
                              <a class="twitter" href="#" target="_blank"> <span class="sr-only">Twitter</span> <i
                                    class="fab fa-twitter"></i> </a>
                           </li>
                           <li>
                              <a class="google-plus" href="#" target="_blank"> <span class="sr-only">Google-plus</span>
                                 <i class="fab fa-google-plus"></i> </a>
                           </li>
                        </ul>
                     </div>
                  </div>
                  <div class="pq-team-info">
                     <h5 class="pq-member-name">
                        <a href="specialist-single.php">
                           Danial Frankie</a>
                     </h5> <span class="pq-team-designation"> Doctor</span>
                  </div>
               </div>
            </div>
            <div class="col-xl-4 col-md-6 col-sm-12 mt-lg-0 mt-4">
               <div class="pq-team-box pq-style-1">
                  <div class="pq-team-img"> <img src="assets/images/team/4.jpg" class="img-fluid" alt="team-image">
                     <div class="pq-team-social">
                        <ul>
                           <li>
                              <a class="facebook" href="#" target="_blank"> <span class="sr-only">Facebook</span> <i
                                    class="fab fa-facebook"></i> </a>
                           </li>
                           <li>
                              <a class="twitter" href="#" target="_blank"> <span class="sr-only">Twitter</span> <i
                                    class="fab fa-twitter"></i> </a>
                           </li>
                           <li>
                              <a class="google-plus" href="#" target="_blank"> <span class="sr-only">Google-plus</span>
                                 <i class="fab fa-google-plus"></i> </a>
                           </li>
                        </ul>
                     </div>
                  </div>
                  <div class="pq-team-info">
                     <h5 class="pq-member-name">
                        <a href="specialist-single.php">
                           Jason Roy</a>
                     </h5> <span class="pq-team-designation"> Neurology</span>
                  </div>
               </div>
            </div>
         </div>
         <div class="row justify-content-center">
            <div class="col-xl-4 col-md-6 col-sm-12 mt-lg-0 mt-md-0 mt-4">
               <div class="pq-team-box pq-style-1">
                  <div class="pq-team-img"> <img src="assets/images/team/4.jpg" class="img-fluid" alt="team-image">
                     <div class="pq-team-social">
                        <ul>
                           <li>
                              <a class="facebook" href="#" target="_blank"> <span class="sr-only">Facebook</span> <i
                                    class="fab fa-facebook"></i> </a>
                           </li>
                           <li>
                              <a class="twitter" href="#" target="_blank"> <span class="sr-only">Twitter</span> <i
                                    class="fab fa-twitter"></i> </a>
                           </li>
                           <li>
                              <a class="google-plus" href="#" target="_blank"> <span class="sr-only">Google-plus</span>
                                 <i class="fab fa-google-plus"></i> </a>
                           </li>
                        </ul>
                     </div>
                  </div>
                  <div class="pq-team-info">
                     <h5 class="pq-member-name">
                        <a href="specialist-single.php">
                           Jason Roy</a>
                     </h5> <span class="pq-team-designation"> Gynecology</span>
                  </div>
               </div>
            </div>
            <div class="col-xl-4 col-md-6 col-sm-12  mt-lg-0 mt-md-0 mt-4">
               <div class="pq-team-box pq-style-1">
                  <div class="pq-team-img"> <img src="assets/images/team/1.jpg" class="img-fluid" alt="team-image">
                     <div class="pq-team-social">
                        <ul>
                           <li>
                              <a class="facebook" href="#" target="_blank"> <span class="sr-only">Facebook</span> <i
                                    class="fab fa-facebook"></i> </a>
                           </li>
                           <li>
                              <a class="twitter" href="#" target="_blank"> <span class="sr-only">Twitter</span> <i
                                    class="fab fa-twitter"></i> </a>
                           </li>
                           <li>
                              <a class="google-plus" href="#" target="_blank"> <span class="sr-only">Google-plus</span>
                                 <i class="fab fa-google-plus"></i> </a>
                           </li>
                        </ul>
                     </div>
                  </div>
                  <div class="pq-team-info">
                     <h5 class="pq-member-name">
                        <a href="specialist-single.php">
                           Naidan Smith</a>
                     </h5> <span class="pq-team-designation"> Gynecology</span>
                  </div>
               </div>
            </div>
            <div class="col-xl-4 col-md-6 col-sm-12 mt-lg-0 mt-4">
               <div class="pq-team-box pq-style-1">
                  <div class="pq-team-img"> <img src="assets/images/team/5.jpg" class="img-fluid" alt="team-image">
                     <div class="pq-team-social">
                        <ul>
                           <li>
                              <a class="facebook" href="#" target="_blank"> <span class="sr-only">Facebook</span> <i
                                    class="fab fa-facebook"></i> </a>
                           </li>
                           <li>
                              <a class="twitter" href="#" target="_blank"> <span class="sr-only">Twitter</span> <i
                                    class="fab fa-twitter"></i> </a>
                           </li>
                           <li>
                              <a class="google-plus" href="#" target="_blank"> <span class="sr-only">Google-plus</span>
                                 <i class="fab fa-google-plus"></i> </a>
                           </li>
                        </ul>
                     </div>
                  </div>
                  <div class="pq-team-info">
                     <h5 class="pq-member-name">
                        <a href="specialist-single.php">
                           Rihana Roy</a>
                     </h5> <span class="pq-team-designation"> Gynecology</span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!--=================================
         our-team end-->


   <!--=================================
         portfolio start-->
   <section class="project pq-bg-grey">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-12">
               <div class="pq-section pq-style-1 text-center"> <span class="pq-section-sub-title">EXPLORE RECENT
                     PROJECTS</span>
                  <h5 class="pq-section-title">Watch Latest Our Work</h5>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-lg-12 px-lg-4">
               <div class="pq-portfoliobox pq-portfoliobox-style-1">
                  <div class="owl-carousel owl-loaded owl-drag" data-dots="true" data-nav="false" data-desk_num="4"
                     data-lap_num="3" data-tab_num="2" data-mob_num="1" data-mob_sm="1" data-autoplay="true"
                     data-loop="true" data-margin="30">
                     <div class="item">
                        <div class="pq-portfoliobox pq-style-1">
                           <div class="pq-portfolio-block">
                              <div class="pq-portfolio-img  "> <img src="assets/images/portfolio/slider/1.jpg"
                                    class="img-fluid" alt="">
                                 <a href="case-study.php">
                                    <div class="pq-portfolio-icon"><i class="fas fa-plus"></i></div>
                                 </a>
                              </div>
                              <div class="pq-portfolio-info">
                                 <div class="pq-portfolio-link"> <a href="case-study.php">Clinic</a> </div>
                                 <h5><a href="case-studies.php">Home Visit</a></h5>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="item">
                        <div class="pq-portfoliobox pq-style-1">
                           <div class="pq-portfolio-block">
                              <div class="pq-portfolio-img  "> <img src="assets/images/portfolio/slider/2.jpg"
                                    class="img-fluid" alt="">
                                 <a href="case-study.php">
                                    <div class="pq-portfolio-icon"><i class="fas fa-plus"></i></div>
                                 </a>
                              </div>
                              <div class="pq-portfolio-info">
                                 <div class="pq-portfolio-link"> <a href="case-study.php">Family</a> </div>
                                 <h5><a href="case-study.php">Investigations</a>
                                 </h5>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="item">
                        <div class="pq-portfoliobox pq-style-1">
                           <div class="pq-portfolio-block">
                              <div class="pq-portfolio-img  "> <img src="assets/images/portfolio/slider/3.jpg"
                                    class="img-fluid" alt="">
                                 <a href="case-study.php">
                                    <div class="pq-portfolio-icon"><i class="fas fa-plus"></i></div>
                                 </a>
                              </div>
                              <div class="pq-portfolio-info">
                                 <div class="pq-portfolio-link"> <a href="case-study.php">Pediatrics</a> </div>
                                 <h5><a href="case-study.php">Surgical</a>
                                 </h5>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="item">
                        <div class="pq-portfoliobox pq-style-1">
                           <div class="pq-portfolio-block">
                              <div class="pq-portfolio-img  "> <img src="assets/images/portfolio/slider/4.jpg"
                                    class="img-fluid" alt="">
                                 <a href="case-study.php">
                                    <div class="pq-portfolio-icon"><i class="fas fa-plus"></i></div>
                                 </a>
                              </div>
                              <div class="pq-portfolio-info">
                                 <div class="pq-portfolio-link"> <a href="case-study.php">Clinic</a> </div>
                                 <h5><a href="case-study.php">Pediatrics
                                       Care</a>
                                 </h5>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="item">
                        <div class="pq-portfoliobox pq-style-1">
                           <div class="pq-portfolio-block">
                              <div class="pq-portfolio-img  "> <img src="assets/images/portfolio/slider/5.jpg"
                                    class="img-fluid" alt="">
                                 <a href="case-study.php">
                                    <div class="pq-portfolio-icon"><i class="fas fa-plus"></i></div>
                                 </a>
                              </div>
                              <div class="pq-portfolio-info">
                                 <div class="pq-portfolio-link"> <a href="case-study.php">Clinic</a> </div>
                                 <h5><a href="case-study.php">Cardiology</a>
                                 </h5>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="item">
                        <div class="pq-portfoliobox pq-style-1">
                           <div class="pq-portfolio-block">
                              <div class="pq-portfolio-img  "> <img src="assets/images/portfolio/slider/6.jpg"
                                    class="img-fluid" alt="">
                                 <a href="case-study.php">
                                    <div class="pq-portfolio-icon"><i class="fas fa-plus"></i></div>
                                 </a>
                              </div>
                              <div class="pq-portfolio-info">
                                 <div class="pq-portfolio-link"> <a href="case-study.php">Pediatrics</a> </div>
                                 <h5><a href="case-study.php">Treatments</a>
                                 </h5>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="item">
                        <div class="pq-portfoliobox pq-style-1">
                           <div class="pq-portfolio-block">
                              <div class="pq-portfolio-img  "> <img src="assets/images/portfolio/slider/7.jpg"
                                    class="img-fluid" alt="">
                                 <a href="case-study.php">
                                    <div class="pq-portfolio-icon"><i class="fas fa-plus"></i></div>
                                 </a>
                              </div>
                              <div class="pq-portfolio-info">
                                 <div class="pq-portfolio-link"> <a href="case-study.php">Family</a> </div>
                                 <h5><a href="case-study.php">Quality
                                       Therapy</a>
                                 </h5>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="item">
                        <div class="pq-portfoliobox pq-style-1">
                           <div class="pq-portfolio-block">
                              <div class="pq-portfolio-img  "> <img src="assets/images/portfolio/slider/8.jpg"
                                    class="img-fluid" alt="">
                                 <a href="case-study.php">
                                    <div class="pq-portfolio-icon"><i class="fas fa-plus"></i></div>
                                 </a>
                              </div>
                              <div class="pq-portfolio-info">
                                 <div class="pq-portfolio-link"> <a href="case-study.php">Laboratory</a> </div>
                                 <h5><a href="case-study.php">Orthodontics</a>
                                 </h5>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="item">
                        <div class="pq-portfoliobox pq-style-1">
                           <div class="pq-portfolio-block">
                              <div class="pq-portfolio-img  "> <img src="assets/images/portfolio/slider/9.jpg"
                                    class="img-fluid" alt="">
                                 <a href="case-study.php">
                                    <div class="pq-portfolio-icon"><i class="fas fa-plus"></i></div>
                                 </a>
                              </div>
                              <div class="pq-portfolio-info">
                                 <div class="pq-portfolio-link"> <a href="case-study.php">Clinic</a> </div>
                                 <h5><a href="case-study.php">Management</a>
                                 </h5>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!--=================================
         portfolio end-->




   <!--=================================
         tab start-->
   <section class="about pq-pb-210">
      <div class="container">
         <div class="row">
            <div class="col-lg-12">
               <div class="pq-section pq-style-1 text-center"> <span class="pq-section-sub-title">WHAT ABOUT US</span>
                  <h5 class="pq-section-title">We Provide Various Directions</h5>
               </div>
            </div>
         </div>
         <div class="col-lg-12">
            <div class="pq-tabs-1">
               <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist"> <a
                     class="pq-tabs nav-item nav-link active" id="nav-home-0" data-bs-toggle="tab" href="#nav-0"
                     role="tab" aria-controls="nav-home-0" aria-selected="true"><i
                        class=" flaticon-medical-doctor"></i><span>Angioplasty</span></a> <a
                     class="pq-tabs nav-item nav-link " id="nav-home-1" data-bs-toggle="tab" href="#nav-1" role="tab"
                     aria-controls="nav-home-1" aria-selected="true"><i
                        class=" flaticon-doctor"></i><span>Neurology</span></a> <a class="pq-tabs nav-item nav-link "
                     id="nav-home-2" data-bs-toggle="tab" href="#nav-2" role="tab" aria-controls="nav-home-2"
                     aria-selected="true"><i class=" flaticon-care"></i><span>Eye Care</span></a> <a
                     class="pq-tabs nav-item nav-link " id="nav-home-3" data-bs-toggle="tab" href="#nav-3" role="tab"
                     aria-controls="nav-home-3" aria-selected="true"><i
                        class=" flaticon-examination"></i><span>Cardiology</span></a> <a
                     class="pq-tabs nav-item nav-link " id="nav-home-4" data-bs-toggle="tab" href="#nav-4" role="tab"
                     aria-controls="nav-home-4" aria-selected="true"><i
                        class=" flaticon-doctor-1"></i><span>Orthopaedics</span></a> </div>
               <div class="tab-content text-" id="nav-tabContent">
                  <div class="tab-pane fade show  active" id="nav-0" role="tabpanel" aria-labelledby="nav-home-0">
                     <div class="row align-items-center">
                        <div class="col-lg-6"> <img src="assets/images/tab/1.jpg" class="pq-full-width img-fluid"
                              alt="seo-image"> </div>
                        <div class="col-lg-6">
                           <div class="pq-tab-info">
                              <h2>Reason To Reject Folowing Drawbacks service</h2>
                              <p>It is a long established fact that a reader will be distracted by the readable content
                                 of a page.</p>
                              <ul>
                                 <li>Aliquam fermentum eros vestibulum, viverra erat rutrum, tincidunt felis.</li>
                                 <li>It is a long established fact that a reader simply dummy text its layout.</li>
                                 <li>Etiam iaculis dui at lectus commodo, at placerat enim vulputate.</li>
                                 <li>Vestibulum at est non mi porta convallis non nec nisl.</li>
                                 <li>Aliquam fermentum eros vestibulum, viverra erat rutrum, tincidunt felis.</li>
                                 <li>It is a long established fact that a reader simply dummy text its layout.</li>
                              </ul>
                              <a class="pq-button pq-button-flat mt-5" href="about-us.html">
                                 <div class="pq-button-block"> <span class="pq-button-text">Read More</span> <i
                                       class="ion ion-plus-round"></i> </div>
                              </a>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane fade show  " id="nav-1" role="tabpanel" aria-labelledby="nav-home-1">
                     <div class="row flex-row-reverse align-items-center">
                        <div class="col-lg-6"> <img src="assets/images/tab/2.jpg" class="pq-full-width img-fluid"
                              alt="seo-image"> </div>
                        <div class="col-lg-6">
                           <div class="pq-tab-info">
                              <h2>Together we can Achieve more things service</h2>
                              <p>It is a long established fact that a reader will be distracted by the readable content
                                 of a page.</p>
                              <ul>
                                 <li>It is a long established fact that a reader simply dummy text its layout.</li>
                                 <li>Etiam iaculis dui at lectus commodo, at placerat enim vulputate.</li>
                                 <li>Vestibulum at est non mi porta convallis non nec nisl.</li>
                                 <li>Aliquam fermentum eros vestibulum, viverra erat rutrum, tincidunt felis.</li>
                                 <li>It is a long established fact that a reader simply dummy text its layout.</li>
                                 <li>There are many variations of passages of Lorem Ipsum available</li>
                                 <li>The generated Lorem Ipsum is therefore always free from repetition</li>
                              </ul>
                              <a class="pq-button pq-button-flat mt-5" href="about-us.html">
                                 <div class="pq-button-block"> <span class="pq-button-text">Read More</span> <i
                                       class="ion ion-plus-round"></i> </div>
                              </a>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane fade show  " id="nav-2" role="tabpanel" aria-labelledby="nav-home-2">
                     <div class="row align-items-center">
                        <div class="col-lg-6"> <img src="assets/images/tab/3.jpg" class="pq-full-width img-fluid"
                              alt="seo-image"> </div>
                        <div class="col-lg-6">
                           <div class="pq-tab-info">
                              <h2>Looking for professinal &amp; trusted Service</h2>
                              <p>It is a long established fact that a reader will be distracted by the readable content
                                 of a page.</p>
                              <ul>
                                 <li>Aliquam fermentum eros vestibulum, viverra erat rutrum, tincidunt felis.</li>
                                 <li>It is a long established fact that a reader simply dummy text its layout.</li>
                                 <li>Etiam iaculis dui at lectus commodo, at placerat enim vulputate.</li>
                                 <li>Vestibulum at est non mi porta convallis non nec nisl.</li>
                                 <li>Aliquam fermentum eros vestibulum, viverra erat rutrum, tincidunt felis.</li>
                                 <li>It is a long established fact that a reader simply dummy text its layout.</li>
                              </ul>
                              <a class="pq-button pq-button-flat mt-5" href="about-us.html">
                                 <div class="pq-button-block"> <span class="pq-button-text">Read More</span> <i
                                       class="ion ion-plus-round"></i> </div>
                              </a>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane fade show  " id="nav-3" role="tabpanel" aria-labelledby="nav-home-3">
                     <div class="row flex-row-reverse align-items-center">
                        <div class="col-lg-6"> <img src="assets/images/tab/4.jpg" class="pq-full-width img-fluid"
                              alt="seo-image"> </div>
                        <div class="col-lg-6">
                           <div class="pq-tab-info">
                              <h2>We Take Care Of Your Healthy Health service</h2>
                              <p>It is a long established fact that a reader will be distracted by the readable content
                                 of a page.</p>
                              <ul>
                                 <li>Aliquam fermentum eros vestibulum, viverra erat rutrum, tincidunt felis.</li>
                                 <li>It is a long established fact that a reader simply dummy text its layout.</li>
                                 <li>Etiam iaculis dui at lectus commodo, at placerat enim vulputate.</li>
                                 <li>Vestibulum at est non mi porta convallis non nec nisl.</li>
                                 <li>Aliquam fermentum eros vestibulum, viverra erat rutrum, tincidunt felis.</li>
                                 <li>It is a long established fact that a reader simply dummy text its layout.</li>
                              </ul>
                              <a class="pq-button pq-button-flat mt-5" href="about-us.html">
                                 <div class="pq-button-block"> <span class="pq-button-text">Read More</span> <i
                                       class="ion ion-plus-round"></i> </div>
                              </a>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane fade show  " id="nav-4" role="tabpanel" aria-labelledby="nav-home-4">
                     <div class="row align-items-center">
                        <div class="col-lg-6"> <img src="assets/images/tab/5.jpg" class="pq-full-width img-fluid"
                              alt="seo-image"> </div>
                        <div class="col-lg-6">
                           <div class="pq-tab-info">
                              <h2> Choose The Best For Your Health service</h2>
                              <p>It is a long established fact that a reader will be distracted by the readable content
                                 of a page.</p>
                              <ul>
                                 <li>Aliquam fermentum eros vestibulum, viverra erat rutrum, tincidunt felis.</li>
                                 <li>It is a long established fact that a reader simply dummy text its layout.</li>
                                 <li>Etiam iaculis dui at lectus commodo, at placerat enim vulputate.</li>
                                 <li>Vestibulum at est non mi porta convallis non nec nisl.</li>
                                 <li>Aliquam fermentum eros vestibulum, viverra erat rutrum, tincidunt felis.</li>
                                 <li>It is a long established fact that a reader simply dummy text its layout.</li>
                              </ul>
                              <a class="pq-button pq-button-flat mt-5" href="about-us.html">
                                 <div class="pq-button-block"> <span class="pq-button-text">Read More</span> <i
                                       class="ion ion-plus-round"></i> </div>
                              </a>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!--=================================
         tab end-->
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
                              <a href="blog-single.php"> <img src="assets/images/footer-image/1.jpg" alt=""></a>
                           </div>
                           <div class="pq-footer-recent-post-info">
                              <a href="blog-single.php" class="pq-post-date"> <i
                                    class="far fa-calendar-alt"></i>December <span>12</span>, 2021 </a>
                              <h6><a href="blog-single.php">Get the Exercise Limited Mobility</a></h6>
                           </div>
                        </div>
                        <div class="pq-footer-recent-post">
                           <div class="pq-footer-recent-post-media">
                              <a href="blog-single.php"> <img src="assets/images/footer-image/2.jpg" alt=""></a>
                           </div>
                           <div class="pq-footer-recent-post-info">
                              <a href="blog-single.php" class="pq-post-date"> <i
                                    class="far fa-calendar-alt"></i>December <span>12</span>, 2021 </a>
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
                                 <li> <a href="tel:+23480281492"><i class="fas fa-phone"></i>
                                       <span>+234 8028134942</span>
                                    </a> </li>
                                 <li> <a href="mailto:info@medicate.com"><i
                                          class="fas fa-envelope"></i><span>info@medicate.com</span></a> </li>
                                 <li> <i class="fas fa-map-marker"></i> <span>
                                       Medicate Lab, S5/808B, Oba Adesida Road, Akure, Ondo State </span> </li>
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
                  <div class="col-md-12 text-center "> <span class="pq-copyright"> &copy; 2025 - Medicate. All Rights
                        Reserved.</span> </div>
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
<?php
session_start();
require_once '../config/helpers.php';  // Note: ../ to go up one level
$getStartedUrl = getGetStartedUrl();
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title> Cardiology Service – Medicate </title>

   <!-- Main Style CSS -->
   <link rel="stylesheet" href="../assets/css/style.css">
   <!-- Favicon Icon -->
   <link rel="shortcut icon" href="../assets/images/favicon.ico">
   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
   <!-- Fonts and Icons -->
   <link rel="stylesheet" type="text/css" href="../assets/rev/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css">
   <link rel="stylesheet" type="text/css" href="../assets/rev/fonts/font-awesome/css/font-awesome.css">
   <!-- REVOLUTION STYLE SHEETS -->
   <link rel="stylesheet" type="text/css" href="../assets/rev/css/rs6.css">
   <!-- Owl Carousel CSS -->
   <link rel="stylesheet" href="../assets/css/owl.carousel.min.css">
   <!-- Progressbar CSS -->
   <link rel="stylesheet" href="../assets/css/progressbar.css">
   <!-- Animation CSS -->
   <link rel="stylesheet" href="../assets/css/animations.min.css">
   <!-- Magnific Popup CSS -->
   <link rel="stylesheet" href="../assets/css/magnific-popup.min.css">
   <!-- Icons CSS -->
   <link rel="stylesheet" href="../assets/fonts/font-awesome/css/all.min.css">
   <link rel="stylesheet" href="../assets/fonts/flaticon/flaticon.css">
   <link rel="stylesheet" href="../assets/css/ionicons.min.css">
   <link rel="stylesheet" href="../assets/fonts/themify-icons/themify-icons.css">

   <!-- Responsive CSS -->
   <link rel="stylesheet" href="../assets/css/responsive.css">
</head>

<body>
   <!--loading start-->
   <div id="pq-loading">
      <div id="pq-loading-center">
         <img src="../assets/images/logo.png" class="img-fluid" alt="loading">
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
                     <a class="navbar-brand" href="../index.php">
                        <img class="img-fluid logo" src="../assets/images/logo.png" alt="medicate">
                     </a>
                     <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <div id="pq-menu-contain" class="pq-menu-contain">
                           <ul id="pq-main-menu" class="navbar-nav ml-auto">
                              <li class="menu-item ">
                                 <a href="../index.php">Home</a>
                              </li>
                              <li class="menu-item ">
                                 <a href="../about.php">About Us </a>
                              </li>
                              <li class="menu-item current-menu-item">
                                 <a href="../services.php">Services</a><i
                                    class="fa fa-chevron-down pq-submenu-icon"></i>
                                 <ul class="sub-menu">
                                    <li class="menu-item ">
                                       <a href="angioplasty.php">Angioplasty </a>
                                    </li>
                                    <li class="menu-item current-menu-item">
                                       <a href="cardiology.php">Cardiology</a>
                                    </li>
                                    <li class="menu-item ">
                                       <a href="dental.php">Dental </a>
                                    </li>
                                    <li class="menu-item">
                                       <a href="endocrinology.php">Endocrinology</a>
                                    </li>
                                    <li class="menu-item ">
                                       <a href="eye-care.php">Eye Care </a>
                                    </li>
                                    <li class="menu-item ">
                                       <a href="neurology.php">Neurology </a>
                                    </li>
                                    <li class="menu-item ">
                                       <a href="orthopaedics.php">Orthopaedics </a>
                                    </li>
                                    <li class="menu-item">
                                       <a href="rmi.php">RMI </a>
                                    </li>
                                 </ul>
                              </li>
                              <li class="menu-item ">
                                 <a href="../specialists.php">Specialists </a>
                              </li>
                              <li class="menu-item ">
                                 <a href="../blog.php">Blog</a>
                              </li>
                              <li class="menu-item ">
                                 <a href="case-study.php">Case Studies </a>
                              </li>
                              <li class="menu-item ">
                                 <a href="../faqs.php">FAQs </a>
                              </li>
                              <li class="menu-item ">
                                 <a href="../contact.php">Contact Us</a>
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
                     <<a href="<?php echo $getStartedUrl; ?>" class="pq-button pq-cta-button">
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
   <div class="pq-breadcrumb" style="background-image:url('../assets/images/breadcrumb.jpg');">
      <div class="container">
         <div class="row align-items-center">
            <div class="col-lg-12">
               <nav aria-label="breadcrumb">
                  <div class="pq-breadcrumb-title">
                     <h2>Cardiology </h2>
                  </div>
                  <div class="pq-breadcrumb-container mt-2">
                     <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home mr-2"></i>Home</a></li>
                        <li class="breadcrumb-item active">Cardiology </li>
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
         all services start-->
   <section class="all-services pq-pb-210">
      <div class="container">
         <div class="row">
            <div class="col-lg-4">
               <div class="pq-widget">
                  <div class="pq-widget pq-widget-port p-0">
                     <div class="menu-service-menu-container">
                        <ul id="menu-service-menu" class="menu">
                           <li> <a href="angioplasty.php" aria-current="page">Angioplasty Services</a> </li>
                           <li class=" current-menu-item"><a href="cardiology.php">Cardiology Services </a>
                           </li>
                           <li><a href="dental.php">Dental Services</a> </li>
                           <li><a href="endocrinology.php">Endocrinology Services</a> </li>
                           <li> <a href="eye-care.php">Eye Care Services</a> </li>
                           <li> <a href="neurology.php">Neurology Services</a> </li>
                           <li> <a href="orthopaedics.php">Orthopaedics Services</a> </li>
                           <li> <a href="rmi.php">RMI Services</a> </li>
                        </ul>
                     </div>
                  </div>
               </div>
               <div id="media_image">
                  <a href="../contact.php"> <img src="../assets/images/call-img.jpg" alt="" class="rounded img-fluid">
                  </a>
               </div>
            </div>
            <div class="col-lg-8 ps-3 mt-4 mt-lg-0">
               <div class="pq-menu-content">
                  <div class="pq-rhs-img text-center"> <img src="../assets/images/service-single.jpg" alt=""
                        class="rounded img-fluid"> </div>
                  <div class="pq-section-title-box pq-section-title-style-2 pt-4">
                     <h4 class="pq-section-title">Professional Medical Service Of Cardiology</h4>
                     <div class="pq-section-description">
                        <p>Medicate is a long established fact that a reader will be distracted by the readable content
                           of a page when looking at its layout. Lorem Ipsum is simply dummy text of the printing and
                           typesetting industry. Lorem Ipsum has been the industry’s standard dummy text ever since </p>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-lg-6">
                        <ul class="pq-portfolio-list-check">
                           <li><i aria-hidden="true" class="ion ion-checkmark"></i><span>Aliquam tincidunt nisi vitae
                                 nulla molestie</span></li>
                           <li><i aria-hidden="true" class="ion ion-checkmark"></i><span>Praesent convallis odio
                                 elementum tortor.</span></li>
                           <li><i aria-hidden="true" class="ion ion-checkmark"></i><span>Nunc molestie ex at nisi
                                 vehicula pellentesque.</span></li>
                           <li><i aria-hidden="true" class="ion ion-checkmark"></i><span>Cras id purus faucibus, euismod
                                 metus.</span></li>
                           <li><i aria-hidden="true" class="ion ion-checkmark"></i><span>Aenean et libero tincidunt,
                                 auctor urna.</span></li>
                        </ul>
                     </div>
                     <div class="col-lg-6 p-lg-0">
                        <ul class="pq-portfolio-list-check">
                           <li><i aria-hidden="true" class="ion ion-checkmark"></i><span>There are many variations of
                                 passages of Lorem</span></li>
                           <li><i aria-hidden="true" class="ion ion-checkmark"></i><span>Ipsum available, but the
                                 majority have suffered.</span></li>
                           <li><i aria-hidden="true" class="ion ion-checkmark"></i><span>Alteration in some form, by
                                 injected humour.</span></li>
                           <li><i aria-hidden="true" class="ion ion-checkmark"></i><span>Randomised words which don't
                                 look.</span></li>
                           <li><i aria-hidden="true" class="ion ion-checkmark"></i><span>Slightly believable. If you are
                                 going..</span></li>
                        </ul>
                     </div>
                     <div class="row">
                        <div class="pq-section-title-box pq-section-title-style-2 pt-2">
                           <h4 class="pq-section-title">Advantage Dental Care</h4>
                           <div class="pq-section-description"> Medicate is a long established fact that a reader will
                              be distracted by the readable content of a page when looking at its layout. Lorem Ipsum is
                              simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                              industry’s standard dummy text ever since </div>
                        </div>
                     </div>
                     <div class="row mt-4">
                        <div class="col-lg-6 col-md-12"> <img src="../assets/images/services/1.jpg" class="img-fluid"
                              alt=""> </div>
                        <div class="col-lg-6 col-md-12 mt-lg-0 mt-4"> <img src="../assets/images/services/2.jpg"
                              class="img-fluid" alt=""> </div>
                     </div>
                     <div class="row">
                        <div class="pq-section-title-box pq-section-title-style-2 pt-4">
                           <h4 class="pq-section-title">Health Tips & Info</h4>
                           <div class="pq-section-description">
                              <p>Medicate is a long established fact that a reader will be distracted by the readable
                                 content of a page when looking at its layout. Lorem Ipsum is simply dummy text of the
                                 printing and typesetting industry. </p>
                           </div>
                        </div>
                        <div class="pq-accordion-block ">
                           <div class="pq-accordion-box  1">
                              <div class="pq-ad-title">
                                 <h6 class="ad-title-text">
                                    Where can I go to provide a sample for testing?
                                    <i aria-hidden="true" class="ion ion-ios-arrow-down active"></i><i
                                       aria-hidden="true" class="ion ion-ios-arrow-up inactive"></i>
                                 </h6>
                              </div>
                              <div class="pq-accordion-details" style="display: none;">
                                 <p class="pq-detail-text"> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut
                                    elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo. </p>
                              </div>
                           </div>
                           <div class="pq-accordion-box   2">
                              <div class="pq-ad-title">
                                 <h6 class="ad-title-text">
                                    What happens to my sample once I have provided it?
                                    <i aria-hidden="true" class="ion ion-ios-arrow-down active"></i><i
                                       aria-hidden="true" class="ion ion-ios-arrow-up inactive"></i>
                                 </h6>
                              </div>
                              <div class="pq-accordion-details" style="display: none;">
                                 <p class="pq-detail-text"> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut
                                    elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo. </p>
                              </div>
                           </div>
                           <div class="pq-accordion-box   3 ">
                              <div class="pq-ad-title">
                                 <h6 class="ad-title-text">
                                    What will laboratory testing cost me?
                                    <i aria-hidden="true" class="ion ion-ios-arrow-down active"></i><i
                                       aria-hidden="true" class="ion ion-ios-arrow-up inactive"></i>
                                 </h6>
                              </div>
                              <div class="pq-accordion-details" style="display: block;">
                                 <p class="pq-detail-text"> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut
                                    elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo. </p>
                              </div>
                           </div>
                           <div class="pq-accordion-box   4 ">
                              <div class="pq-ad-title">
                                 <h6 class="ad-title-text">
                                    Using Innovative Technology
                                    <i aria-hidden="true" class="ion ion-ios-arrow-down active"></i><i
                                       aria-hidden="true" class="ion ion-ios-arrow-up inactive"></i>
                                 </h6>
                              </div>
                              <div class="pq-accordion-details" style="display: block;">
                                 <p class="pq-detail-text"> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut
                                    elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo. </p>
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
         all services end-->



   <!--=================================
         our-blog start-->
   <section class="pq-blog  pq-pb-210">
      <div class="container">
         <div class="row">
            <div class="col-lg-12">
               <div class="pq-section pq-style-1 text-center"> <span class="pq-section-sub-title">our blog</span>
                  <h5 class="pq-section-title">See Our Latest Blog</h5>
               </div>
            </div>
            <div class="col-lg-12">
               <div class="owl-carousel owl-theme" data-dots="false" data-nav="false" data-desk_num="3" data-lap_num="3"
                  data-tab_num="2" data-mob_num="1" data-mob_sm="1" data-autoplay="true" data-loop="true"
                  data-margin="30">
                  <div class="item">
                     <div class="pq-blog-post pq-style-1 pq-bg-grey">
                        <div class="pq-post-media"> <img src="../assets/images/blog/1.jpg" class="img-fluid"
                              alt="images">
                           <div class="pq-post-date">
                              <a href="blog-single.php"> <span>December 5, 2021</span></a>
                           </div>
                        </div>
                        <div class="pq-blog-contain">
                           <div class="pq-post-meta">
                              <ul>
                                 <li class="pq-post-author"><i class="fa fa-user"></i>Medicate Admin</li>
                                 <li class="pq-post-comment"> <a href="blog-single.php"><i class="fa fa-comments"></i>
                                       0 Comments</a> </li>
                              </ul>
                           </div>
                           <h5 class="pq-blog-title"><a href="blog-single.php">Get the Exercise Limited Mobility</a>
                           </h5>
                           <div class="pq-blog-info">
                              <p>It is a long established fact that a reader will be distracted by the readable content
                                 of a page when looking at its layout.</p>
                           </div>
                           <a href="blog-single.php" class="pq-button pq-button-link">
                              <div class="pq-button-block"> <span class="pq-button-text">Read More</span> <i
                                    class="ion ion-plus-round"></i> </div>
                           </a>
                        </div>
                     </div>
                  </div>
                  <div class="item">
                     <div class="pq-blog-post pq-style-1 pq-bg-grey">
                        <div class="pq-post-media"> <img src="../assets/images/blog/2.jpg" class="img-fluid"
                              alt="images">
                           <div class="pq-post-date">
                              <a href="blog-single.php"> <span>December 5, 2021</span></a>
                           </div>
                        </div>
                        <div class="pq-blog-contain">
                           <div class="pq-post-meta">
                              <ul>
                                 <li class="pq-post-author"><i class="fa fa-user"></i>Medicate Admin</li>
                                 <li class="pq-post-comment"> <a href="blog-single.php"><i class="fa fa-comments"></i>
                                       0 Comments</a> </li>
                              </ul>
                           </div>
                           <h5 class="pq-blog-title"><a href="blog-single.php">Transfusion strategy and heart
                                 surgery</a></h5>
                           <div class="pq-blog-info">
                              <p>It is a long established fact that a reader will be distracted by the readable content
                                 of a page when looking at its layout.</p>
                           </div>
                           <a href="blog-single.php" class="pq-button pq-button-link">
                              <div class="pq-button-block"> <span class="pq-button-text">Read More</span> <i
                                    class="ion ion-plus-round"></i> </div>
                           </a>
                        </div>
                     </div>
                  </div>
                  <div class="item">
                     <div class="pq-blog-post pq-style-1 pq-bg-grey">
                        <div class="pq-post-media"> <img src="../assets/images/blog/3.jpg" class="img-fluid"
                              alt="images">
                           <div class="pq-post-date">
                              <a href="blog-single.php"> <span>December 5, 2021</span></a>
                           </div>
                        </div>
                        <div class="pq-blog-contain">
                           <div class="pq-post-meta">
                              <ul>
                                 <li class="pq-post-author"><i class="fa fa-user"></i>Medicate Admin</li>
                                 <li class="pq-post-comment"> <a href="blog-single.php"><i class="fa fa-comments"></i>
                                       0 Comments</a> </li>
                              </ul>
                           </div>
                           <h5 class="pq-blog-title"><a href="blog-single.php">Latest Equipment for the Heart
                                 Treatment</a></h5>
                           <div class="pq-blog-info">
                              <p>It is a long established fact that a reader will be distracted by the readable content
                                 of a page when looking at its layout.</p>
                           </div>
                           <a href="blog-single.php" class="pq-button pq-button-link">
                              <div class="pq-button-block"> <span class="pq-button-text">Read More</span> <i
                                    class="ion ion-plus-round"></i> </div>
                           </a>
                        </div>
                     </div>
                  </div>
                  <div class="item">
                     <div class="pq-blog-post pq-style-1 pq-bg-grey">
                        <div class="pq-post-media"> <img src="../assets/images/blog/4.jpg" class="img-fluid"
                              alt="images">
                           <div class="pq-post-date">
                              <a href="blog-single.php"> <span>December 5, 2021</span></a>
                           </div>
                        </div>
                        <div class="pq-blog-contain">
                           <div class="pq-post-meta">
                              <ul>
                                 <li class="pq-post-author"><i class="fa fa-user"></i>Medicate Admin</li>
                                 <li class="pq-post-comment"> <a href="blog-single.php"><i class="fa fa-comments"></i>
                                       0 Comments</a> </li>
                              </ul>
                           </div>
                           <h5 class="pq-blog-title"><a href="blog-single.php">What is Future of Blood Pressure
                                 Monitoring?</a></h5>
                           <div class="pq-blog-info">
                              <p>It is a long established fact that a reader will be distracted by the readable content
                                 of a page when looking at its layout.</p>
                           </div>
                           <a href="blog-single.php" class="pq-button pq-button-link">
                              <div class="pq-button-block"> <span class="pq-button-text">Read More</span> <i
                                    class="ion ion-plus-round"></i> </div>
                           </a>
                        </div>
                     </div>
                  </div>
                  <div class="item">
                     <div class="pq-blog-post pq-style-1 pq-bg-grey">
                        <div class="pq-post-media"> <img src="../assets/images/blog/5.jpg" class="img-fluid"
                              alt="images">
                           <div class="pq-post-date">
                              <a href="blog-single.php"> <span>December 5, 2021</span></a>
                           </div>
                        </div>
                        <div class="pq-blog-contain">
                           <div class="pq-post-meta">
                              <ul>
                                 <li class="pq-post-author"><i class="fa fa-user"></i>Medicate Admin</li>
                                 <li class="pq-post-comment"> <a href="blog-single.php"><i class="fa fa-comments"></i>
                                       0 Comments</a> </li>
                              </ul>
                           </div>
                           <h5 class="pq-blog-title"><a href="blog-single.php">Goals Setting the people Heart is
                                 Healthy</a></h5>
                           <div class="pq-blog-info">
                              <p>It is a long established fact that a reader will be distracted by the readable content
                                 of a page when looking at its layout.</p>
                           </div>
                           <a href="blog-single.php" class="pq-button pq-button-link">
                              <div class="pq-button-block"> <span class="pq-button-text">Read More</span> <i
                                    class="ion ion-plus-round"></i> </div>
                           </a>
                        </div>
                     </div>
                  </div>
                  <div class="item">
                     <div class="pq-blog-post pq-style-1 pq-bg-grey">
                        <div class="pq-post-media"> <img src="../assets/images/blog/6.jpg" class="img-fluid"
                              alt="images">
                           <div class="pq-post-date">
                              <a href="blog-single.php"> <span>December 5, 2021</span></a>
                           </div>
                        </div>
                        <div class="pq-blog-contain">
                           <div class="pq-post-meta">
                              <ul>
                                 <li class="pq-post-author"><i class="fa fa-user"></i>Medicate Admin</li>
                                 <li class="pq-post-comment"> <a href="blog-single.php"><i class="fa fa-comments"></i>
                                       0 Comments</a> </li>
                              </ul>
                           </div>
                           <h5 class="pq-blog-title"><a href="blog-single.php">For Examination of kids get Special
                                 offers</a></h5>
                           <div class="pq-blog-info">
                              <p>It is a long established fact that a reader will be distracted by the readable content
                                 of a page when looking at its layout.</p>
                           </div>
                           <a href="blog-single.php" class="pq-button pq-button-link">
                              <div class="pq-button-block"> <span class="pq-button-text">Read More</span> <i
                                    class="ion ion-plus-round"></i> </div>
                           </a>
                        </div>
                     </div>
                  </div>
                  <div class="item">
                     <div class="pq-blog-post pq-style-1 pq-bg-grey">
                        <div class="pq-post-media"> <img src="../assets/images/blog/7.jpg" class="img-fluid"
                              alt="images">
                           <div class="pq-post-date">
                              <a href="blog-single.php"> <span>December 5, 2021</span></a>
                           </div>
                        </div>
                        <div class="pq-blog-contain">
                           <div class="pq-post-meta">
                              <ul>
                                 <li class="pq-post-author"><i class="fa fa-user"></i>Medicate Admin</li>
                                 <li class="pq-post-comment"> <a href="blog-single.php"><i class="fa fa-comments"></i>
                                       0 Comments</a> </li>
                              </ul>
                           </div>
                           <h5 class="pq-blog-title"><a href="blog-single.php">Heart Failure Treatment: High Blood
                                 Pressure</a></h5>
                           <div class="pq-blog-info">
                              <p>It is a long established fact that a reader will be distracted by the readable content
                                 of a page when looking at its layout.</p>
                           </div>
                           <a href="blog-single.php" class="pq-button pq-button-link">
                              <div class="pq-button-block"> <span class="pq-button-text">Read More</span> <i
                                    class="ion ion-plus-round"></i> </div>
                           </a>
                        </div>
                     </div>
                  </div>
                  <div class="item">
                     <div class="pq-blog-post pq-style-1 pq-bg-grey">
                        <div class="pq-post-media"> <img src="../assets/images/blog/8.jpg" class="img-fluid"
                              alt="images">
                           <div class="pq-post-date">
                              <a href="blog-single.php"> <span>December 5, 2021</span></a>
                           </div>
                        </div>
                        <div class="pq-blog-contain">
                           <div class="pq-post-meta">
                              <ul>
                                 <li class="pq-post-author"><i class="fa fa-user"></i>Medicate Admin</li>
                                 <li class="pq-post-comment"> <a href="blog-single.php"><i class="fa fa-comments"></i>
                                       0 Comments</a> </li>
                              </ul>
                           </div>
                           <h5 class="pq-blog-title"><a href="blog-single.php">Hard content we decide ourselves a
                                 intently</a></h5>
                           <div class="pq-blog-info">
                              <p>It is a long established fact that a reader will be distracted by the readable content
                                 of a page when looking at its layout.</p>
                           </div>
                           <a href="blog-single.php" class="pq-button pq-button-link">
                              <div class="pq-button-block"> <span class="pq-button-text">Read More</span> <i
                                    class="ion ion-plus-round"></i> </div>
                           </a>
                        </div>
                     </div>
                  </div>
                  <div class="item">
                     <div class="pq-blog-post pq-style-1 pq-bg-grey">
                        <div class="pq-post-media"> <img src="../assets/images/blog/9.jpg" class="img-fluid"
                              alt="images">
                           <div class="pq-post-date">
                              <a href="blog-single.php"> <span>December 5, 2021</span></a>
                           </div>
                        </div>
                        <div class="pq-blog-contain">
                           <div class="pq-post-meta">
                              <ul>
                                 <li class="pq-post-author"><i class="fa fa-user"></i>Medicate Admin</li>
                                 <li class="pq-post-comment"> <a href="blog-single.php"><i class="fa fa-comments"></i>
                                       0 Comments</a> </li>
                              </ul>
                           </div>
                           <h5 class="pq-blog-title"><a href="blog-single.php">Is Running Really Good for the Heart?</a>
                           </h5>
                           <div class="pq-blog-info">
                              <p>It is a long established fact that a reader will be distracted by the readable content
                                 of a page when looking at its layout.</p>
                           </div>
                           <a href="blog-single.php" class="pq-button pq-button-link">
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
   </section>
   <!--=================================
         our-blog end-->

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
                              <div class="pq-subscribe-block"> <img src="../assets/images/Subscribe.png"
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
                     <div class="pq-footer-block"> <img src="../assets/images/footer_logo.png"
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
                              <li><a href="our-services.html">Our Services</a></li>
                              <li><a href="our-process.html">Our Process</a></li>
                              <li><a href="doctor-1.html">Doctors 1</a></li>
                              <li><a href="faq.html">FAQ</a></li>
                              <li><a href="our-doctor.html">Doctors 2</a></li>
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
                              <a href="blog-single.php"> <img src="../assets/images/footer-image/1.jpg" alt=""></a>
                           </div>
                           <div class="pq-footer-recent-post-info">
                              <a href="blog-single.php" class="pq-post-date"> <i
                                    class="far fa-calendar-alt"></i>December <span>12</span>, 2021 </a>
                              <h6><a href="blog-single.php">Get the Exercise Limited Mobility</a></h6>
                           </div>
                        </div>
                        <div class="pq-footer-recent-post">
                           <div class="pq-footer-recent-post-media">
                              <a href="blog-single.php"> <img src="../assets/images/footer-image/2.jpg" alt=""></a>
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
                                 <li> <a href="tel:+2348028134942"><i class="fas fa-phone"></i>
                                       <span>+234 8028134942</span>
                                    </a> </li>
                                 <li> <a href="mailto:info@medicate.com"><i
                                          class="fas fa-envelope"></i><span>info@medicate.com</span></a> </li>
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
   <script src="../assets/js/jquery.min.js"></script>
   <script src="../assets/js/bootstrap.min.js"></script>
   <script src="../assets/js/owl.carousel.min.js"></script>
   <script src="../assets/js/progressbar.js"></script>
   <script src="../assets/js/isotope.pkgd.min.js"></script>
   <script src="../assets/js/jquery.countTo.min.js"></script>
   <script src="../assets/js/jquery.magnific-popup.min.js"></script>
   <script src="../assets/js/wow.min.js"></script>
   <script src="../assets/rev/js/rbtools.min.js"></script>
   <script src="../assets/rev/js/rs6.min.js"></script>
   <script src="../assets/js/rev-custom.js"></script>
   <script src="../assets/js/custom.js"></script>

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
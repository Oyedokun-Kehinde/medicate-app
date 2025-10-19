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
   <title> Contact – Hospital Management System </title>

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
                              <li class="menu-item ">
                                 <a href="services.php">Services</a><i class="fa fa-chevron-down pq-submenu-icon"></i>
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
                              <li class="menu-item ">
                                 <a href="case-study.php">Case Studies </a>
                              </li>
                              <li class="menu-item ">
                                 <a href="blog.php">Blog</a>
                              </li>
                              <li class="menu-item ">
                                 <a href="faqs.php">FAQs </a>
                              </li>
                              <li class="menu-item current-menu-item">
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
                     <h2>Contact Us</h2>
                  </div>
                  <div class="pq-breadcrumb-container mt-2">
                     <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home mr-2"></i>Home</a></li>
                        <li class="breadcrumb-item active">Contact Us</li>
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
         conatct-us start-->
   <section class="pq-contact-us">
      <div class="container">
         <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 col-sm-12">
               <div class="pq-contact-box">
                  <div class="pq-contact-box-icon">
                     <div class="icon animation-grow"> <i aria-hidden="true" class="ion ion-location"></i> </div>
                  </div>
                  <div class="pq-contact-box-info">
                     <h4 class="pq-contact-box-title">
                        Visit our Hospital
                     </h4>
                     <p class="pq-contact-box-description"> Medicate Lab, S5/808B, Oba Adesida Raod, Akure, Ondo State
                     </p>
                  </div>
               </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 mt-lg-0 mt-md-0 mt-4">
               <div class="pq-contact-box">
                  <div class="pq-contact-box-icon">
                     <div class="icon animation-grow"> <i aria-hidden="true" class="ion ion-ios-telephone"></i> </div>
                  </div>
                  <div class="pq-contact-box-info">
                     <h4 class="pq-contact-box-title">
                        Give us a call
                     </h4>
                     <p class="pq-contact-box-description"> +234 8028134942
                        <br>+234 8144745225
                     </p>
                  </div>
               </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 mt-lg-0 mt-4">
               <div class="pq-contact-box">
                  <div class="pq-contact-box-icon">
                     <div class="icon animation-grow"> <i aria-hidden="true" class="ion ion-email-unread"></i> </div>
                  </div>
                  <div class="pq-contact-box-info">
                     <h4 class="pq-contact-box-title">
                        Send an email
                     </h4>
                     <p class="pq-contact-box-description"> info@medicate.com
                        <br>info2@peacefulthemes.com
                     </p>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!--=================================
         contact-us end-->
   <!--=================================
         get-in-touch start-->
   <section class="get-in-touch p-0">
      <div class="container">
         <div class="row">
            <div class="col-lg-8 col-md-8  pq-form-box">
               <div class="form-container">
                  <div class="pq-section pq-style-1 text-center p-0"> <span class="pq-section-sub-title">contact
                        us</span>
                     <h5 class="pq-section-title">Get in touch with us</h5>
                  </div>
               </div>
               <div class="pq-applyform-whitebg text-start">
                  <form id="contactForm" class="pq-applyform">
                     <div class="row">
                        <div class="col-lg-6 col-md-6">
                           <input type="text" id="name" name="name" class="name-field" 
                              placeholder="Enter Your Name" required>
                           <span class="error-message text-danger" id="nameError"></span>
                        </div>
                        
                        <div class="col-lg-6 col-md-6">
                           <input type="email" id="email" name="email" class="e-mail-field" 
                              placeholder="Enter Your Email" required>
                           <span class="error-message text-danger" id="emailError"></span>
                        </div>
                        
                        <div class="col-lg-12 col-md-12">
                           <input type="text" id="subject" name="subject" class="subject-field" 
                              placeholder="Subject" required>
                           <span class="error-message text-danger" id="subjectError"></span>
                        </div>
                        
                        <div class="col-lg-12 col-md-12">
                           <textarea name="message" id="message" cols="40" rows="10" 
                              placeholder="Write Your Message" required></textarea>
                           <span class="error-message text-danger" id="messageError"></span>
                        </div>
                        
                        <div class="col-lg-12 col-md-12">
                           <button type="submit" class="pq-button form-btn">
                              <div class="pq-button-block">
                                 <span class="pq-button-text me-0">send message</span>
                              </div>
                           </button>
                           <div id="successMessage" class="alert alert-success mt-3" style="display: none;"></div>
                           <div id="errorMessage" class="alert alert-danger mt-3" style="display: none;"></div>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!--=================================
         get-in-touch end-->
   <!--=================================
         map start-->
   <div class="map pt-0">
      <div class="pq-bg-map">
         <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.5583187833477!2d5.154494800000003!3d7.290987000000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x10478fb74b5f3fdb%3A0x24852e5c436419e6!2sRexta%20Technologies!5e0!3m2!1sen!2sng!4v1760489612168!5m2!1sen!2sng"
            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
   </div>
   <!--=================================
         map end-->
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

   <!-- Contact Form JavaScript -->
   <script>
     document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Clear previous messages
    document.getElementById('successMessage').style.display = 'none';
    document.getElementById('errorMessage').style.display = 'none';
    document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
    
    // Get form data
    const formData = new FormData(this);
    
    // Send via AJAX
    fetch('contact-handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Get the name from form BEFORE resetting
            const name = document.getElementById('name').value;
            
            // Clear error messages and placeholders
            document.getElementById('errorMessage').style.display = 'none';
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
            
            // Show personalized success message
            const personalizedMessage = `Dear ${name}, thank you for your message! We will get back to you soon.`;
            document.getElementById('successMessage').textContent = personalizedMessage;
            document.getElementById('successMessage').style.display = 'block';
            
            // Reset form and clear all fields
            document.getElementById('contactForm').reset();
        } else {
            // Show error message
            document.getElementById('errorMessage').textContent = data.message;
            document.getElementById('errorMessage').style.display = 'block';
        }
    })
    .catch(error => {
        document.getElementById('errorMessage').textContent = 'An error occurred. Please try again.';
        document.getElementById('errorMessage').style.display = 'block';
        console.error('Error:', error);
    });
}); </script>
</body>
</html>
<?php
session_start();
require_once '../config/helpers.php';  
require_once '../config/database.php';

$getStartedUrl = getGetStartedUrl();

$home_blogs = [];
$error = null;

try {
    // Fetch published blogs - 6 posts for carousel
    $stmt = $pdo->prepare("
        SELECT 
            bp.id, bp.title, bp.slug, bp.excerpt, bp.featured_image, 
            bp.created_at, bp.content,
            COALESCE(dp.full_name, u.email) as doctor_name,
            (SELECT COUNT(*) FROM blog_comments WHERE blog_post_id = bp.id) as comment_count
        FROM blog_posts bp
        JOIN users u ON bp.doctor_id = u.id
        LEFT JOIN doctor_profiles dp ON u.id = dp.user_id
        WHERE bp.status = 'published'
        ORDER BY bp.created_at DESC
        LIMIT 6
    ");
    
    $stmt->execute();
    $home_blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    error_log("Blog error: " . $e->getMessage());
    $error = "Error loading blog posts. Please try again later.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title> Eye Care Service – Medicate </title>

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
                                    <li class="menu-item ">
                                       <a href="cardiology.php">Cardiology</a>
                                    </li>
                                    <li class="menu-item ">
                                       <a href="dental.php">Dental </a>
                                    </li>
                                    <li class="menu-item">
                                       <a href="endocrinology.php">Endocrinology</a>
                                    </li>
                                    <li class="menu-item current-menu-item">
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
                                 <a href="../case-study.php">Case Studies </a>
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
   <div class="pq-breadcrumb" style="background-image:url('../assets/images/breadcrumb.jpg');">
      <div class="container">
         <div class="row align-items-center">
            <div class="col-lg-12">
               <nav aria-label="breadcrumb">
                  <div class="pq-breadcrumb-title">
                     <h2>Eye Care </h2>
                  </div>
                  <div class="pq-breadcrumb-container mt-2">
                     <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home mr-2"></i>Home</a></li>
                        <li class="breadcrumb-item active"> Eye Care </li>
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
                           <li><a href="cardiology.php">Cardiology Services </a>
                           </li>
                           <li><a href="dental.php">Dental Services</a> </li>
                           <li><a href="endocrinology.php">Endocrinology Services</a> </li>
                           <li class=" current-menu-item"> <a href="eye-care.php">Eye Care Services</a> </li>
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
                     <h4 class="pq-section-title">Professional Eye Care Services</h4>
                     <div class="pq-section-description">
                        <p>Our ophthalmology department provides comprehensive eye care services from routine eye exams to advanced surgical procedures. Our experienced ophthalmologists and optometrists utilize cutting-edge diagnostic equipment to diagnose and treat cataracts, glaucoma, macular degeneration, diabetic retinopathy, and other vision-threatening conditions with precision and care.</p>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-lg-6">
                        <ul class="pq-portfolio-list-check">
                           <li><i aria-hidden="true" class="ion ion-checkmark"></i><span>Comprehensive Eye Examinations</span></li>
                           <li><i aria-hidden="true" class="ion ion-checkmark"></i><span>Cataract Surgery and Treatment</span></li>
                           <li><i aria-hidden="true" class="ion ion-checkmark"></i><span>Glaucoma Diagnosis and Management</span></li>
                           <li><i aria-hidden="true" class="ion ion-checkmark"></i><span>Retinal Disease Treatment</span></li>
                           <li><i aria-hidden="true" class="ion ion-checkmark"></i><span>LASIK and Refractive Surgery</span></li>
                        </ul>
                     </div>
                     <div class="col-lg-6 p-lg-0">
                        <ul class="pq-portfolio-list-check">
                           <li><i aria-hidden="true" class="ion ion-checkmark"></i><span>Diabetic Eye Disease Management</span></li>
                           <li><i aria-hidden="true" class="ion ion-checkmark"></i><span>Contact Lens Fitting and Care</span></li>
                           <li><i aria-hidden="true" class="ion ion-checkmark"></i><span>Pediatric Eye Care</span></li>
                           <li><i aria-hidden="true" class="ion ion-checkmark"></i><span>Dry Eye Treatment</span></li>
                           <li><i aria-hidden="true" class="ion ion-checkmark"></i><span>Emergency Eye Care Services</span></li>
                        </ul>
                     </div>
                     <div class="row">
                        <div class="pq-section-title-box pq-section-title-style-2 pt-2">
                           <h4 class="pq-section-title">Why Choose Our Eye Care Services</h4>
                           <div class="pq-section-description">Our eye care center features the latest diagnostic technology including OCT imaging, visual field testing, and fundus photography. We provide personalized care for all ages, from pediatric vision screening to senior eye health management, ensuring optimal vision and eye health for life.</div>
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
                           <h4 class="pq-section-title">Eye Care FAQs</h4>
                           <div class="pq-section-description">
                              <p>Important information about eye health and our comprehensive ophthalmology services. Protect your vision with expert care from our dedicated eye specialists.</p>
                           </div>
                        </div>
                        <div class="pq-accordion-block ">
                           <div class="pq-accordion-box  1">
                              <div class="pq-ad-title">
                                 <h6 class="ad-title-text">
                                    How often should I have an eye exam?
                                    <i aria-hidden="true" class="ion ion-ios-arrow-down active"></i><i
                                       aria-hidden="true" class="ion ion-ios-arrow-up inactive"></i>
                                 </h6>
                              </div>
                              <div class="pq-accordion-details" style="display: none;">
                                 <p class="pq-detail-text">Adults should have comprehensive eye exams every 1-2 years, or annually if over 60 or at risk for eye disease. Children need exams at 6 months, 3 years, before first grade, and annually thereafter. Those with diabetes or family history of eye disease need more frequent monitoring.</p>
                              </div>
                           </div>
                           <div class="pq-accordion-box   2">
                              <div class="pq-ad-title">
                                 <h6 class="ad-title-text">
                                    What are the symptoms of cataracts?
                                    <i aria-hidden="true" class="ion ion-ios-arrow-down active"></i><i
                                       aria-hidden="true" class="ion ion-ios-arrow-up inactive"></i>
                                 </h6>
                              </div>
                              <div class="pq-accordion-details" style="display: none;">
                                 <p class="pq-detail-text">Cataract symptoms include cloudy or blurry vision, faded colors, glare sensitivity, poor night vision, double vision in one eye, and frequent prescription changes. Cataracts develop gradually and can be successfully treated with modern surgical techniques when they interfere with daily activities.</p>
                              </div>
                           </div>
                           <div class="pq-accordion-box   3 ">
                              <div class="pq-ad-title">
                                 <h6 class="ad-title-text">
                                    What is glaucoma and how is it treated?
                                    <i aria-hidden="true" class="ion ion-ios-arrow-down active"></i><i
                                       aria-hidden="true" class="ion ion-ios-arrow-up inactive"></i>
                                 </h6>
                              </div>
                              <div class="pq-accordion-details" style="display: block;">
                                 <p class="pq-detail-text">Glaucoma is increased eye pressure that damages the optic nerve, potentially causing vision loss. Treatment includes prescription eye drops, laser procedures, or surgery to lower eye pressure. Early detection through regular eye exams is crucial as vision loss from glaucoma cannot be reversed.</p>
                              </div>
                           </div>
                           <div class="pq-accordion-box   4 ">
                              <div class="pq-ad-title">
                                 <h6 class="ad-title-text">
                                    Am I a candidate for LASIK surgery?
                                    <i aria-hidden="true" class="ion ion-ios-arrow-down active"></i><i
                                       aria-hidden="true" class="ion ion-ios-arrow-up inactive"></i>
                                 </h6>
                              </div>
                              <div class="pq-accordion-details" style="display: block;">
                                 <p class="pq-detail-text">Good LASIK candidates are over 18, have stable vision for at least one year, healthy eyes without diseases, adequate corneal thickness, and realistic expectations. A comprehensive evaluation will determine your eligibility for LASIK or alternative vision correction procedures.</p>
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

     <!--Section blog Start-->
   <section class="pq-blog pq-bg-grey pq-pb-210">
      <div class="container">
         <div class="row">
            <div class="col-lg-12">
               <div class="pq-section pq-style-1 text-center">
                  <span class="pq-section-sub-title">our blog</span>
                  <h5 class="pq-section-title">See Our Latest Blog</h5>
               </div>
            </div>
            <div class="col-lg-12">
               <?php if (empty($home_blogs)): ?>
                  <div style="text-align: center; padding: 40px; color: #999;">
                     <p>No blog posts available yet. Check back soon!</p>
                  </div>
               <?php else: ?>
                  <div class="owl-carousel owl-theme" data-dots="false" data-nav="false" data-desk_num="3"
                     data-lap_num="2" data-tab_num="2" data-mob_num="1" data-mob_sm="1" data-autoplay="true"
                     data-loop="true" data-margin="30">
                     <?php foreach ($home_blogs as $blog): ?>
                        <div class="item">
                           <div class="pq-blog-post pq-style-1 pq-bg-grey">
                              <div class="pq-post-media">
                                 <?php if (!empty($blog['featured_image'])): ?>
                                    <img src="../<?php echo htmlspecialchars($blog['featured_image']); ?>"
                                       class="img-fluid" alt="<?php echo htmlspecialchars($blog['title']); ?>">
                                 <?php else: ?>
                                    <div style="width: 100%; height: 250px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                                 <?php endif; ?>
                                 <div class="pq-post-date">
                                    <a href="blog-single.php?id=<?php echo $blog['id']; ?>">
                                       <span><?php echo date('F d, Y', strtotime($blog['created_at'])); ?></span>
                                    </a>
                                 </div>
                              </div>
                              <div class="pq-blog-contain">
                                 <div class="pq-post-meta">
                                    <ul>
                                       <li class="pq-post-author">
                                          <i class="fa fa-user"></i><?php echo htmlspecialchars($blog['doctor_name'] ?? 'Dr. Medicate'); ?>
                                       </li>
                                       <li class="pq-post-comment">
                                          <a href="blog-single.php?id=<?php echo $blog['id']; ?>">
                                             <i class="fa fa-comments"></i><?php echo $blog['comment_count']; ?>
                                             Comments
                                          </a>
                                       </li>
                                    </ul>
                                 </div>
                                 <h5 class="pq-blog-title">
                                    <a href="blog-single.php?id=<?php echo $blog['id']; ?>">
                                       <?php echo htmlspecialchars(substr($blog['title'], 0, 60)); ?>
                                    </a>
                                 </h5>
                                 <div class="pq-blog-info">
                                    <p><?php echo htmlspecialchars(substr($blog['excerpt'] ?? $blog['content'], 0, 100)) . '...'; ?></p>
                                 </div>
                                 <a href="blog-single.php?id=<?php echo $blog['id']; ?>"
                                    class="pq-button pq-button-link">
                                    <div class="pq-button-block">
                                       <span class="pq-button-text">Read More</span>
                                       <i class="ion ion-plus-round"></i>
                                    </div>
                                 </a>
                              </div>
                           </div>
                        </div>
                     <?php endforeach; ?>
                  </div>
               <?php endif; ?>
            </div>
         </div>
      </div>
   </section>
   <!--Section blog End-->

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
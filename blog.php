<?php
session_start();
require_once 'config/database.php';
require_once 'config/helpers.php';

$getStartedUrl = getGetStartedUrl();
$getStartedButtonText = getGetStartedButtonText();

// Get page number
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$per_page = 6;
$offset = ($page - 1) * $per_page;

$blogs = [];
$recent_posts = [];
$total_pages = 0;
$error = null;

try {
    // Fetch published blogs with pagination
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
        LIMIT :limit OFFSET :offset
    ");
    
    $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get total count
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM blog_posts WHERE status = 'published'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total = $result['count'] ?? 0;
    $total_pages = $total > 0 ? ceil($total / $per_page) : 1;
    
    // Fetch recent posts for sidebar
    $stmt = $pdo->prepare("
        SELECT bp.id, bp.title, bp.featured_image, bp.created_at
        FROM blog_posts bp
        WHERE bp.status = 'published'
        ORDER BY bp.created_at DESC
        LIMIT 4
    ");
    $stmt->execute();
    $recent_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
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
    <title>Blog - Medicate</title>
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/responsive.css">

</head>
<body>
    <div id="pq-loading">
        <div id="pq-loading-center">
            <img src="assets/images/logo.png" class="img-fluid" alt="loading">
        </div>
    </div>



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
                                        <li class="menu-item ">
                                            <a href="about.php">About Us </a>
                                        </li>
                                        <li class="menu-item ">
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
                                        <li class="menu-item ">
                                            <a href="case-study.php">Case Studies </a>
                                        </li>
                                        <li class="menu-item current-menu-item">
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

    <!-- Breadcrumb -->
    <div class="pq-breadcrumb" style="background-image: url('assets/images/breadcrumb.jpg');">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                        <div class="pq-breadcrumb-title"><h2>Our Blog</h2></div>
                        <div class="pq-breadcrumb-container mt-2">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home mr-2"></i>Home</a></li>
                                <li class="breadcrumb-item active">Blog</li>
                            </ol>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Blog Section -->
    <section class="blog" style="padding: 90px 0;">
        <div class="container">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($blogs)): ?>
                        <div class="pq-blog-post" style="text-align: center; padding: 60px 30px;">
                            <i class="fas fa-blog" style="font-size: 64px; opacity: 0.3; color: var(--primary-color);"></i>
                            <h4 style="margin-top: 20px;">No Blog Posts Yet</h4>
                            <p>Check back soon for expert health insights from our doctors.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($blogs as $blog): ?>
                            <div class="pq-blog-post">
                                <div class="pq-post-media">
                                    <?php if (!empty($blog['featured_image']) && file_exists($blog['featured_image'])): ?>
                                        <img src="<?php echo htmlspecialchars($blog['featured_image']); ?>" 
                                             alt="<?php echo htmlspecialchars($blog['title']); ?>">
                                    <?php else: ?>
                                        <img src="assets/images/blog/default-blog.jpg" 
                                             alt="<?php echo htmlspecialchars($blog['title']); ?>"
                                             onerror="this.src='https://via.placeholder.com/800x400/2490eb/ffffff?text=Medicate+Health+Blog'">
                                    <?php endif; ?>
                                    <div class="pq-post-date">
                                        <a href="blog-single.php?id=<?php echo (int)$blog['id']; ?>">
                                            <?php echo date('M d, Y', strtotime($blog['created_at'])); ?>
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="pq-blog-contain">
                                    <div class="pq-post-meta">
                                        <ul>
                                            <li>
                                                <i class="fas fa-user-md"></i>
                                                <a href="#"><?php echo htmlspecialchars($blog['doctor_name']); ?></a>
                                            </li>
                                            <li>
                                                <i class="fas fa-comments"></i>
                                                <a href="blog-single.php?id=<?php echo (int)$blog['id']; ?>#comments">
                                                    <?php echo (int)$blog['comment_count']; ?> Comments
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    
                                    <h5 class="pq-blog-title">
                                        <a href="blog-single.php?id=<?php echo (int)$blog['id']; ?>">
                                            <?php echo htmlspecialchars($blog['title']); ?>
                                        </a>
                                    </h5>
                                    
                                    <p>
                                        <?php 
                                        $excerpt = $blog['excerpt'] ?: strip_tags($blog['content']);
                                        echo htmlspecialchars(substr($excerpt, 0, 200)); 
                                        if (strlen($excerpt) > 200) echo '...';
                                        ?>
                                    </p>
                                    
                                    <a class="pq-button pq-button-link" href="blog-single.php?id=<?php echo (int)$blog['id']; ?>">
                                        <div class="pq-button-block">
                                            <span class="pq-button-text">Read More</span>
                                            <i class="ion ion-ios-arrow-right"></i>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php if ($total_pages > 1): ?>
                            <div class="pq-pagination">
                                <ul class="page-numbers">
                                    <?php if ($page > 1): ?>
                                        <li><a class="page-numbers" href="blog.php?page=<?php echo $page - 1; ?>">
                                            <i class="fas fa-angle-left"></i>
                                        </a></li>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                                        <li>
                                            <?php if ($i == $page): ?>
                                                <span class="page-numbers current"><?php echo $i; ?></span>
                                            <?php else: ?>
                                                <a class="page-numbers" href="blog.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                            <?php endif; ?>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if ($page < $total_pages): ?>
                                        <li><a class="page-numbers" href="blog.php?page=<?php echo $page + 1; ?>">
                                            <i class="fas fa-angle-right"></i>
                                        </a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar pq-widget-area">
                        
                        <!-- Search Widget -->
                        <div class="pq-widget pq-widget_search">
                            <h2 class="pq-widget-title">Search</h2>
                            <form class="search-form" method="get" action="blog.php">
                                <label>
                                    <input type="search" class="search-field" placeholder="Search..." name="s">
                                </label>
                                <button type="submit" class="search-submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>

                        <!-- Recent Posts Widget -->
                        <div class="pq-widget">
                            <h2 class="pq-widget-title">Recent Posts</h2>
                            <?php if (!empty($recent_posts)): ?>
                                <?php foreach ($recent_posts as $recent): ?>
                                    <div class="pq-footer-recent-post">
                                        <div class="pq-footer-recent-post-media">
                                            <?php if (!empty($recent['featured_image']) && file_exists($recent['featured_image'])): ?>
                                                <img src="<?php echo htmlspecialchars($recent['featured_image']); ?>" 
                                                     alt="<?php echo htmlspecialchars($recent['title']); ?>">
                                            <?php else: ?>
                                                <img src="https://via.placeholder.com/80x80/2490eb/ffffff?text=Blog" 
                                                     alt="<?php echo htmlspecialchars($recent['title']); ?>">
                                            <?php endif; ?>
                                        </div>
                                        <div class="pq-footer-recent-post-info">
                                            <span class="pq-post-date">
                                                <i class="fas fa-calendar-alt"></i>
                                                <?php echo date('M d, Y', strtotime($recent['created_at'])); ?>
                                            </span>
                                            <h6>
                                                <a href="blog-single.php?id=<?php echo (int)$recent['id']; ?>">
                                                    <?php echo htmlspecialchars(substr($recent['title'], 0, 50)); ?>
                                                    <?php if (strlen($recent['title']) > 50) echo '...'; ?>
                                                </a>
                                            </h6>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No recent posts available.</p>
                            <?php endif; ?>
                        </div>

                        <!-- Categories Widget -->
                        <div class="pq-widget pq-widget_categories">
                            <h2 class="pq-widget-title">Categories</h2>
                            <ul>
                                <li><a href="blog.php?category=health"><i class="fas fa-angle-right"></i> Health Tips</a></li>
                                <li><a href="blog.php?category=medical"><i class="fas fa-angle-right"></i> Medical News</a></li>
                                <li><a href="blog.php?category=wellness"><i class="fas fa-angle-right"></i> Wellness</a></li>
                                <li><a href="blog.php?category=prevention"><i class="fas fa-angle-right"></i> Prevention</a></li>
                                <li><a href="blog.php?category=treatment"><i class="fas fa-angle-right"></i> Treatment</a></li>
                            </ul>
                        </div>

                        <!-- Tags Widget -->
                        <div class="pq-widget pq-widget_tag_cloud">
                            <h2 class="pq-widget-title">Tags</h2>
                            <div class="tagcloud">
                                <a href="blog.php?tag=health" class="tag-cloud-link">Health</a>
                                <a href="blog.php?tag=medical" class="tag-cloud-link">Medical</a>
                                <a href="blog.php?tag=wellness" class="tag-cloud-link">Wellness</a>
                                <a href="blog.php?tag=doctor" class="tag-cloud-link">Doctor</a>
                                <a href="blog.php?tag=treatment" class="tag-cloud-link">Treatment</a>
                                <a href="blog.php?tag=prevention" class="tag-cloud-link">Prevention</a>
                                <a href="blog.php?tag=care" class="tag-cloud-link">Care</a>
                                <a href="blog.php?tag=tips" class="tag-cloud-link">Tips</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

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
                                        <a href="blog-single.php"> <img
                                                src="assets/images/footer-image/1.jpg" alt=""></a>
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
                                        <a href="blog-single.php"> <img
                                                src="assets/images/footer-image/2.jpg" alt=""></a>
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
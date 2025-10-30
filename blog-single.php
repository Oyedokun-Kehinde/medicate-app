<?php
session_start();
require_once 'config/helpers.php';
require_once 'config/database.php';

$getStartedUrl = getGetStartedUrl();
$blog_id = (int)($_GET['id'] ?? 0);

if ($blog_id <= 0) {
    header('Location: blog.php');
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT 
            bp.id, bp.title, bp.content, bp.excerpt, bp.featured_image, 
            bp.created_at, bp.updated_at,
            dp.full_name as doctor_name,
            u.id as doctor_id
        FROM blog_posts bp
        JOIN users u ON bp.doctor_id = u.id
        LEFT JOIN doctor_profiles dp ON u.id = dp.user_id
        WHERE bp.id = ? AND bp.status = 'published'
    ");
    $stmt->execute([$blog_id]);
    $blog = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$blog) {
        header('Location: blog.php');
        exit;
    }
    
    $stmt = $pdo->prepare("
        SELECT 
            bc.id, bc.comment_text, bc.created_at,
            bc.comment_author_name,
            u.user_type,
            COALESCE(pp.full_name, dp.full_name, u.email) as commenter_name
        FROM blog_comments bc
        JOIN users u ON bc.user_id = u.id
        LEFT JOIN patient_profiles pp ON u.id = pp.user_id
        LEFT JOIN doctor_profiles dp ON u.id = dp.user_id
        WHERE bc.blog_post_id = ?
        ORDER BY bc.created_at DESC
    ");
    $stmt->execute([$blog_id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    header('Location: blog.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($blog['title']); ?> - Medicate</title>
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <style>
        :root {
            --primary-dark: #14457B;
            --primary-light: #238DE6;
        }

        #pq-loading {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        #commentForm{
            margin-bottom: 50px;
        }

        #pq-loading-center img {
            max-width: 200px;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        .blog-hero {
            background: linear-gradient(135deg, rgba(20, 69, 123, 0.95) 0%, rgba(35, 141, 230, 0.95) 100%), 
                        url('assets/images/breadcrumb.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            padding: 80px 0 60px;
            color: white;
            text-align: center;
        }

        .blog-hero h1 {
            font-size: 42px;
            margin-bottom: 20px;
            font-weight: 700;
            line-height: 1.3;
            color: #fff;
        }

        .blog-meta-hero {
            font-size: 16px;
            opacity: 0.95;
        }

        .blog-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .blog-featured-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 8px;
            margin: 30px 0;
            box-shadow: 0 4px 15px rgba(20, 69, 123, 0.15);
        }

        .blog-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            border-top: 2px solid var(--primary-light);
            border-bottom: 2px solid var(--primary-light);
            margin: 30px 0;
            font-size: 14px;
            color: #666;
        }

        .blog-author {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .blog-author-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-light) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
        }

        .blog-content {
            font-size: 16px;
            line-height: 1.8;
            color: #333;
            margin: 40px 0;
        }

        .blog-content p {
            margin-bottom: 20px;
        }

        .blog-content h2 {
            font-size: 28px;
            margin: 30px 0 15px;
            color: var(--primary-dark);
            font-weight: 700;
        }

        .blog-content h3 {
            font-size: 22px;
            margin: 25px 0 12px;
            color: var(--primary-light);
            font-weight: 600;
        }

        .blog-content ul, .blog-content ol {
            margin: 15px 0 15px 30px;
            color: #555;
        }

        .comments-section {
            margin-top: 60px;
            padding-top: 40px;
            margin-bottom: 50px;
            border-top: 2px solid #eee;
        }

        .comments-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 30px;
            color: var(--primary-dark);
        }

        .comment-item {
            margin-bottom: 25px;
            padding: 20px;
            background: #f9f9f9;
            border-left: 4px solid var(--primary-light);
            border-radius: 4px;
        }

        .comment-badge {
            background: var(--primary-light);
            color: white;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }

        .comment-form {
            background: #f5f5f5;
            padding: 30px;
            border-radius: 8px;
            margin-top: 40px;
            border-left: 4px solid var(--primary-dark);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(35, 141, 230, 0.1);
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-light) 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
        }

        .alert { padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        .login-prompt {
            background: #e7f3ff;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            border-left: 4px solid var(--primary-light);
        }

        .login-prompt a {
            color: var(--primary-light);
            font-weight: 600;
            text-decoration: none;
        }

        .back-to-blog {
            display: inline-block;
            margin-top: 40px;
            color: var(--primary-light);
            text-decoration: none;
            font-weight: 600;
        }

        .back-to-blog:hover {
            color: var(--primary-dark);
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
                                        <li class="menu-item current-menu-item">
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

    <!-- Blog Hero -->
    <div class="blog-hero">
        <div class="container">
            <h1><?php echo htmlspecialchars($blog['title']); ?></h1>
            <div class="blog-meta-hero">
                By <strong><?php echo htmlspecialchars($blog['doctor_name'] ?? 'Dr. Medicate'); ?></strong> • 
                <?php echo date('F d, Y', strtotime($blog['created_at'])); ?>
            </div>
        </div>
    </div>

    <!-- Blog Content -->
    <div class="blog-container">
        <?php if (!empty($blog['featured_image'])): ?>
            <img src="<?php echo htmlspecialchars($blog['featured_image']); ?>" alt="Blog featured image" class="blog-featured-image">
        <?php endif; ?>

        <div class="blog-meta">
            <div class="blog-author">
                <div class="blog-author-avatar"><?php echo strtoupper(substr($blog['doctor_name'][0] ?? 'D', 0, 1)); ?></div>
                <div>
                    <strong><?php echo htmlspecialchars($blog['doctor_name'] ?? 'Dr. Medicate'); ?></strong><br>
                    <small>Medical Professional</small>
                </div>
            </div>
            <div>
                <small><?php echo date('M d, Y \a\t H:i', strtotime($blog['created_at'])); ?></small>
            </div>
        </div>

        <div class="blog-content">
            <?php echo nl2br(htmlspecialchars($blog['content'])); ?>
        </div>

        <a href="blog.php" class="back-to-blog">← Back to Blog</a>

        <!-- Comments Section -->
        <div class="comments-section">
            <h2 class="comments-title"><i class="fas fa-comments"></i> Comments (<?php echo count($comments); ?>)</h2>

            <?php if (empty($comments)): ?>
                <div style="text-align: center; color: #999; padding: 40px 0;">
                    <i class="fas fa-comment" style="font-size: 48px; opacity: 0.3; display: block; margin-bottom: 15px;"></i>
                    <p>No comments yet. Be the first to comment!</p>
                </div>
            <?php else: ?>
                <div style="margin-bottom: 40px;">
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment-item">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
                                <div>
                                    <span style="font-weight: 600; color: #333;"><?php echo htmlspecialchars($comment['commenter_name']); ?></span>
                                    <?php if ($comment['user_type'] === 'doctor'): ?>
                                        <span class="comment-badge">Doctor</span>
                                    <?php endif; ?>
                                </div>
                                <span style="font-size: 12px; color: #999;"><?php echo date('F d, Y \a\t H:i', strtotime($comment['created_at'])); ?></span>
                            </div>
                            <div style="color: #666; line-height: 1.6;">
                                <?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Comment Form -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="comment-form">
                    <div style="font-size: 20px; font-weight: 700; margin-bottom: 20px; color: var(--primary-dark);">Leave a Comment</div>
                    <div id="commentMessage"></div>
                    <form id="commentForm">
                        <input type="hidden" name="blog_post_id" value="<?php echo $blog_id; ?>">
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Your Comment *</label>
                            <textarea name="comment_text" id="commentText" class="form-control" placeholder="Share your thoughts..." required></textarea>
                        </div>
                        <button type="submit" class="btn-submit">Post Comment</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="login-prompt">
                    <p>Please <a href="login.php">log in</a> or <a href="register.php">sign up</a> to leave a comment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

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

    <!--Back To Top-->
    <div id="back-to-top">
        <a class="topbtn" id="top" href="#top"><i class="ion-ios-arrow-up"></i></a>
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

    <!-- Preloader & Comments Script -->
    <script>
        window.addEventListener('load', function() {
            const loader = document.getElementById('pq-loading');
            if (loader) loader.style.display = 'none';
        });

        document.getElementById('commentForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const messageDiv = document.getElementById('commentMessage');
            
            fetch('process-blog-comment.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageDiv.className = 'alert alert-success';
                    messageDiv.textContent = data.message;
                    messageDiv.style.display = 'block';
                    document.getElementById('commentText').value = '';
                    setTimeout(() => location.reload(), 2000);
                } else {
                    messageDiv.className = 'alert alert-error';
                    messageDiv.textContent = data.error || 'Error posting comment';
                    messageDiv.style.display = 'block';
                }
            })
            .catch(error => {
                messageDiv.className = 'alert alert-error';
                messageDiv.textContent = 'Error: ' + error.message;
                messageDiv.style.display = 'block';
            });
        });
    </script>
</body>
</html>
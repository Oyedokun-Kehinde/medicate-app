<?php
session_start();
require_once 'config/helpers.php';
require_once 'config/database.php';

$getStartedUrl = getGetStartedUrl();

// Get page number
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 9;
$offset = ($page - 1) * $per_page;

try {
    // Fetch published blogs
    $stmt = $pdo->prepare("
        SELECT 
            bp.id, bp.title, bp.slug, bp.excerpt, bp.featured_image, 
            bp.created_at,
            dp.full_name as doctor_name,
            (SELECT COUNT(*) FROM blog_comments WHERE blog_post_id = bp.id) as comment_count
        FROM blog_posts bp
        JOIN users u ON bp.doctor_id = u.id
        LEFT JOIN doctor_profiles dp ON u.id = dp.user_id
        WHERE bp.status = 'published'
        ORDER BY bp.created_at DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->execute([$per_page, $offset]);
    $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get total count
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM blog_posts WHERE status = 'published'");
    $stmt->execute();
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    $total_pages = ceil($total / $per_page);
    
} catch (Exception $e) {
    $blogs = [];
    $total_pages = 0;
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
    <style>
        .blog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin: 40px 0;
        }

        .blog-card {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
        }

        .blog-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .blog-content {
            padding: 20px;
        }

        .blog-meta {
            font-size: 12px;
            color: #999;
            margin-bottom: 10px;
        }

        .blog-doctor {
            color: #667eea;
            font-weight: 600;
        }

        .blog-title {
            font-size: 18px;
            font-weight: 700;
            margin: 10px 0;
            color: #333;
            line-height: 1.4;
        }

        .blog-title a {
            color: #333;
            text-decoration: none;
        }

        .blog-title a:hover {
            color: #667eea;
        }

        .blog-excerpt {
            font-size: 14px;
            color: #666;
            line-height: 1.6;
            margin: 15px 0;
            height: 60px;
            overflow: hidden;
        }

        .blog-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .blog-comments {
            font-size: 13px;
            color: #999;
        }

        .blog-read-more {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
            font-size: 13px;
        }

        .blog-read-more:hover {
            color: #764ba2;
        }

        .pagination-custom {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 40px 0;
        }

        .pagination-custom a,
        .pagination-custom span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #667eea;
            transition: all 0.3s ease;
        }

        .pagination-custom a:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .pagination-custom .active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .no-blogs {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .no-blogs i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <!-- Header -->
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
                                <li><a href="tel:+2348028134942"><i class="fas fa-phone"></i> +234 8028134942</a></li>
                                <li><a href="mailto:info@medicate.com"><i class="fas fa-envelope"></i>info@medicate.com</a></li>
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
                                <ul id="pq-main-menu" class="navbar-nav ml-auto">
                                    <li class="menu-item"><a href="index.php">Home</a></li>
                                    <li class="menu-item"><a href="about.php">About Us</a></li>
                                    <li class="menu-item"><a href="services.php">Services</a></li>
                                    <li class="menu-item"><a href="specialists.php">Specialists</a></li>
                                    <li class="menu-item"><a href="blog.php">Blog</a></li>
                                    <li class="menu-item"><a href="contact.php">Contact Us</a></li>
                                </ul>
                            </div>
                            <a href="<?php echo $getStartedUrl; ?>" class="pq-button pq-cta-button">
                                <div class="pq-button-block">
                                    <span class="pq-button-text"><?php echo getGetStartedButtonText(); ?></span>
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

    <!-- Breadcrumb -->
    <div class="pq-breadcrumb" style="background-image:url('assets/images/breadcrumb.jpg');">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                        <div class="pq-breadcrumb-title">
                            <h2>Our Blog</h2>
                        </div>
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
    <section class="pq-pb-210" style="padding: 60px 0;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pq-section pq-style-1 text-center">
                        <span class="pq-section-sub-title">Health & Wellness</span>
                        <h5 class="pq-section-title">Latest Articles from Our Experts</h5>
                    </div>
                </div>
            </div>

            <?php if (empty($blogs)): ?>
                <div class="no-blogs">
                    <i class="fas fa-blog"></i>
                    <h4>No Blog Posts Yet</h4>
                    <p>Check back soon for expert health insights from our medical professionals.</p>
                </div>
            <?php else: ?>
                <div class="blog-grid">
                    <?php foreach ($blogs as $blog): ?>
                        <div class="blog-card">
                            <?php if (!empty($blog['featured_image'])): ?>
                                <img src="<?php echo htmlspecialchars($blog['featured_image']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" class="blog-image">
                            <?php else: ?>
                                <div class="blog-image"></div>
                            <?php endif; ?>
                            
                            <div class="blog-content">
                                <div class="blog-meta">
                                    <span class="blog-doctor"><?php echo htmlspecialchars($blog['doctor_name'] ?? 'Dr. Medicate'); ?></span> • 
                                    <?php echo date('M d, Y', strtotime($blog['created_at'])); ?>
                                </div>
                                
                                <h3 class="blog-title">
                                    <a href="blog-single.php?id=<?php echo $blog['id']; ?>">
                                        <?php echo htmlspecialchars(substr($blog['title'], 0, 60)); ?>
                                    </a>
                                </h3>
                                
                                <p class="blog-excerpt">
                                    <?php echo htmlspecialchars(substr($blog['excerpt'] ?? $blog['title'], 0, 100)) . '...'; ?>
                                </p>
                                
                                <div class="blog-footer">
                                    <span class="blog-comments">
                                        <i class="fas fa-comment"></i> <?php echo $blog['comment_count']; ?> Comments
                                    </span>
                                    <a href="blog-single.php?id=<?php echo $blog['id']; ?>" class="blog-read-more">
                                        Read More →
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div class="pagination-custom">
                        <?php if ($page > 1): ?>
                            <a href="blog.php?page=1">« First</a>
                            <a href="blog.php?page=<?php echo $page - 1; ?>">‹ Previous</a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <?php if ($i == $page): ?>
                                <span class="active"><?php echo $i; ?></span>
                            <?php else: ?>
                                <a href="blog.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <a href="blog.php?page=<?php echo $page + 1; ?>">Next ›</a>
                            <a href="blog.php?page=<?php echo $total_pages; ?>">Last »</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer id="pq-footer">
        <div class="pq-footer-style-1">
            <div class="pq-footer-top">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="pq-footer-block">
                                <img src="assets/images/footer_logo.png" class="pq-footer-logo img-fluid" alt="medicate-footer-logo">
                                <p>Providing quality healthcare solutions.</p>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="pq-footer-block">
                                <h4 class="footer-title">Quick Links</h4>
                                <ul>
                                    <li><a href="index.php">Home</a></li>
                                    <li><a href="about.php">About Us</a></li>
                                    <li><a href="services.php">Services</a></li>
                                    <li><a href="blog.php">Blog</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="pq-footer-block">
                                <h4 class="footer-title">Contact</h4>
                                <ul>
                                    <li><a href="tel:+2348028134942"><i class="fas fa-phone"></i> +234 8028134942</a></li>
                                    <li><a href="mailto:info@medicate.com"><i class="fas fa-envelope"></i>info@medicate.com</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="pq-footer-block">
                                <h4 class="footer-title">Follow Us</h4>
                                <ul style="display: flex; gap: 15px;">
                                    <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pq-copyright-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <span class="pq-copyright">&copy; 2025 - Medicate. All Rights Reserved.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
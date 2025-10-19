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
    // Fetch blog post
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
    
    // Fetch comments
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
        .blog-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px 0;
            color: white;
            text-align: center;
        }

        .blog-hero h1 {
            font-size: 42px;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .blog-meta-hero {
            font-size: 16px;
            opacity: 0.9;
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
        }

        .blog-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
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
            color: #667eea;
            font-weight: 700;
        }

        .blog-content h3 {
            font-size: 22px;
            margin: 25px 0 12px;
            color: #764ba2;
            font-weight: 600;
        }

        .comments-section {
            margin-top: 60px;
            padding-top: 40px;
            border-top: 2px solid #eee;
        }

        .comments-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 30px;
            color: #333;
        }

        .comment-item {
            margin-bottom: 25px;
            padding: 20px;
            background: #f9f9f9;
            border-left: 4px solid #667eea;
            border-radius: 4px;
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 12px;
        }

        .comment-author {
            font-weight: 600;
            color: #333;
        }

        .comment-badge {
            background: #667eea;
            color: white;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }

        .comment-date {
            font-size: 12px;
            color: #999;
        }

        .comment-text {
            color: #666;
            line-height: 1.6;
        }

        .comment-form {
            background: #f5f5f5;
            padding: 30px;
            border-radius: 8px;
            margin-top: 40px;
        }

        .comment-form-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .no-comments {
            text-align: center;
            color: #999;
            padding: 40px 0;
        }

        .login-prompt {
            background: #e7f3ff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-top: 20px;
        }

        .login-prompt a {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
        }

        .back-to-blog {
            display: inline-block;
            margin-top: 40px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .back-to-blog:hover {
            color: #764ba2;
        }
    </style>
</head>
<body>
    <!-- Header (same as blog.php) -->
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
            <h2 class="comments-title">
                <i class="fas fa-comments"></i> Comments (<?php echo count($comments); ?>)
            </h2>

            <?php if (empty($comments)): ?>
                <div class="no-comments">
                    <i class="fas fa-comment" style="font-size: 48px; opacity: 0.3; display: block; margin-bottom: 15px;"></i>
                    <p>No comments yet. Be the first to comment!</p>
                </div>
            <?php else: ?>
                <div style="margin-bottom: 40px;">
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment-item">
                            <div class="comment-header">
                                <div>
                                    <span class="comment-author"><?php echo htmlspecialchars($comment['commenter_name']); ?></span>
                                    <?php if ($comment['user_type'] === 'doctor'): ?>
                                        <span class="comment-badge">Doctor</span>
                                    <?php endif; ?>
                                </div>
                                <span class="comment-date"><?php echo date('F d, Y \a\t H:i', strtotime($comment['created_at'])); ?></span>
                            </div>
                            <div class="comment-text">
                                <?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Comment Form -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="comment-form">
                    <div class="comment-form-title">Leave a Comment</div>
                    <div id="commentMessage"></div>
                    <form id="commentForm">
                        <input type="hidden" name="blog_post_id" value="<?php echo $blog_id; ?>">
                        
                        <div class="form-group">
                            <label class="form-label">Your Comment *</label>
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
    <script>
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
                    
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
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
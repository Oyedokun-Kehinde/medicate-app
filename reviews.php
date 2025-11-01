<?php
session_start();
require_once 'config/database.php';
require_once 'config/helpers.php';

$getStartedUrl = getGetStartedUrl();

// Get all doctors with their ratings
try {
    $stmt = $pdo->query("
        SELECT 
            dp.user_id,
            COALESCE(dp.full_name, u.email) as doctor_name,
            dp.specialization,
            dp.bio,
            dp.phone,
            dp.average_rating,
            dp.total_reviews,
            u.email
        FROM doctor_profiles dp
        JOIN users u ON dp.user_id = u.id
        WHERE u.user_type = 'doctor'
        ORDER BY dp.average_rating DESC, dp.total_reviews DESC
    ");
    $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Reviews fetch error: " . $e->getMessage());
    $doctors = [];
}

// Get selected doctor reviews if doctor_id is provided
$selected_doctor = null;
$doctor_reviews = [];

if (isset($_GET['doctor_id'])) {
    $doctor_id = (int)$_GET['doctor_id'];
    
    // Get doctor details
    $stmt = $pdo->prepare("
        SELECT 
            dp.user_id,
            COALESCE(dp.full_name, u.email) as doctor_name,
            dp.specialization,
            dp.bio,
            dp.average_rating,
            dp.total_reviews
        FROM doctor_profiles dp
        JOIN users u ON dp.user_id = u.id
        WHERE dp.user_id = ?
    ");
    $stmt->execute([$doctor_id]);
    $selected_doctor = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get reviews for this doctor
    $stmt = $pdo->prepare("
        SELECT 
            dr.*,
            COALESCE(pp.full_name, u.email) as patient_name
        FROM doctor_reviews dr
        JOIN users u ON dr.patient_id = u.id
        LEFT JOIN patient_profiles pp ON u.id = pp.user_id
        WHERE dr.doctor_id = ?
        ORDER BY dr.created_at DESC
    ");
    $stmt->execute([$doctor_id]);
    $doctor_reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Doctor Reviews – Medicate</title>
    
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    
    <style>
        :root {
            --primary-color: #2490eb;
            --primary-dark-color: #14457b;
            --dark-color: #18100f;
            --secondary-color: #666666;
            --grey-color: #f4f6f9;
        }
        
        .reviews-container {
            padding: 60px 0;
            background: var(--grey-color);
            min-height: 70vh;
        }
        
        .doctor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .doctor-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .doctor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 25px rgba(0,0,0,0.15);
        }
        
        .doctor-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .doctor-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            font-weight: bold;
        }
        
        .doctor-info h3 {
            margin: 0;
            color: var(--dark-color);
            font-size: 1.3rem;
        }
        
        .doctor-specialization {
            color: var(--primary-color);
            font-size: 0.9rem;
            margin-top: 5px;
        }
        
        .rating-display {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 15px 0;
        }
        
        .stars {
            color: #ffc107;
            font-size: 1.2rem;
        }
        
        .rating-value {
            font-weight: bold;
            color: var(--dark-color);
        }
        
        .review-count {
            color: var(--secondary-color);
            font-size: 0.9rem;
        }
        
        .view-reviews-btn {
            background: var(--primary-color);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            width: 100%;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .view-reviews-btn:hover {
            background: var(--primary-dark-color);
            color: white;
        }
        
        .review-details {
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .review-item {
            background: var(--grey-color);
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 20px;
        }
        
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .reviewer-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .reviewer-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        .reviewer-name {
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .review-date {
            color: var(--secondary-color);
            font-size: 0.85rem;
        }
        
        .review-rating {
            color: #ffc107;
        }
        
        .review-text {
            color: var(--dark-color);
            line-height: 1.6;
            margin-top: 10px;
        }
        
        .back-btn {
            background: var(--secondary-color);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }
        
        .back-btn:hover {
            background: var(--dark-color);
            color: white;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--secondary-color);
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.3;
        }
    </style>
</head>

<body>
    <!--Preloader start-->
    <div id="pq-loading">
        <div id="pq-loading-center">
            <img src="assets/images/logo.png" class="img-fluid" alt="loading">
        </div>
    </div>
    <!--loading End-->

    <!--Header start-->
    <header id="pq-header" class="pq-header-default">
        <div class="pq-top-header">
            <div class="container">
                <div class="row flex-row-reverse">
                    <div class="col-md-6 text-right">
                        <div class="pq-header-social text-right">
                            <ul>
                                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="pq-header-contact">
                            <ul>
                                <li><a href="tel:+2348028134942"><i class="fas fa-phone"></i><span> +234 8028134942</span></a></li>
                                <li><a href="mailto:info@medicate.com"><i class="fas fa-envelope"></i><span>info@medicate.com</span></a></li>
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
                                        <li class="menu-item"><a href="index.php">Home</a></li>
                                        <li class="menu-item"><a href="about.php">About Us</a></li>
                                        <li class="menu-item"><a href="services.php">Services</a></li>
                                        <li class="menu-item"><a href="specialists.php">Specialists</a></li>
                                        <li class="menu-item"><a href="blog.php">Blog</a></li>
                                        <li class="menu-item"><a href="contact.php">Contact Us</a></li>
                                    </ul>
                                </div>
                            </div>
                            <a href="<?php echo $getStartedUrl; ?>" class="pq-button">
                                <div class="pq-button-block">
                                    <span class="pq-button-text">Get Started</span>
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
    <!--Header End-->

    <!-- Breadcrumb -->
    <div class="pq-breadcrumb" style="background-image:url('assets/images/breadcrumb.jpg');">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                        <div class="pq-breadcrumb-title">
                            <h2><?php echo $selected_doctor ? 'Doctor Reviews' : 'All Doctor Reviews'; ?></h2>
                        </div>
                        <div class="pq-breadcrumb-container mt-2">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home mr-2"></i>Home</a></li>
                                <li class="breadcrumb-item"><a href="specialists.php">Specialists</a></li>
                                <li class="breadcrumb-item active">Reviews</li>
                            </ol>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="reviews-container">
        <div class="container">
            
            <?php if ($selected_doctor): ?>
                <!-- Detailed Reviews for Selected Doctor -->
                <a href="reviews.php" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to All Doctors
                </a>
                
                <div class="review-details">
                    <div class="doctor-header">
                        <div class="doctor-avatar">
                            <?php echo strtoupper(substr($selected_doctor['doctor_name'], 0, 2)); ?>
                        </div>
                        <div class="doctor-info">
                            <h3>Dr. <?php echo htmlspecialchars($selected_doctor['doctor_name']); ?></h3>
                            <div class="doctor-specialization">
                                <?php echo htmlspecialchars($selected_doctor['specialization'] ?? 'General Practice'); ?>
                            </div>
                            <div class="rating-display">
                                <div class="stars">
                                    <?php
                                    $rating = $selected_doctor['average_rating'] ?? 0;
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo $i <= round($rating) ? '★' : '☆';
                                    }
                                    ?>
                                </div>
                                <span class="rating-value"><?php echo number_format($rating, 1); ?></span>
                                <span class="review-count">(<?php echo $selected_doctor['total_reviews']; ?> reviews)</span>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h4 style="margin: 30px 0 20px;">Patient Reviews</h4>
                    
                    <?php if (empty($doctor_reviews)): ?>
                        <div class="empty-state">
                            <i class="far fa-comments"></i>
                            <h5>No Reviews Yet</h5>
                            <p>This doctor hasn't received any reviews yet.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($doctor_reviews as $review): ?>
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <div class="reviewer-avatar">
                                            <?php echo strtoupper(substr($review['patient_name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div class="reviewer-name"><?php echo htmlspecialchars($review['patient_name']); ?></div>
                                            <div class="review-date">
                                                <?php echo date('F j, Y', strtotime($review['created_at'])); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="review-rating">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            echo $i <= $review['rating'] ? '★' : '☆';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="review-text">
                                    <?php echo nl2br(htmlspecialchars($review['review_text'])); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
            <?php else: ?>
                <!-- All Doctors Grid -->
                <h3 style="margin-bottom: 30px; color: var(--dark-color);">Our Doctors & Their Reviews</h3>
                
                <?php if (empty($doctors)): ?>
                    <div class="empty-state">
                        <i class="fas fa-user-md"></i>
                        <h5>No Doctors Found</h5>
                        <p>No doctors available at the moment.</p>
                    </div>
                <?php else: ?>
                    <div class="doctor-grid">
                        <?php foreach ($doctors as $doctor): ?>
                            <div class="doctor-card" onclick="window.location.href='reviews.php?doctor_id=<?php echo $doctor['user_id']; ?>'">
                                <div class="doctor-header">
                                    <div class="doctor-avatar">
                                        <?php echo strtoupper(substr($doctor['doctor_name'], 0, 2)); ?>
                                    </div>
                                    <div class="doctor-info">
                                        <h3>Dr. <?php echo htmlspecialchars($doctor['doctor_name']); ?></h3>
                                        <div class="doctor-specialization">
                                            <?php echo htmlspecialchars($doctor['specialization'] ?? 'General Practice'); ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="rating-display">
                                    <div class="stars">
                                        <?php
                                        $rating = $doctor['average_rating'] ?? 0;
                                        for ($i = 1; $i <= 5; $i++) {
                                            echo $i <= round($rating) ? '★' : '☆';
                                        }
                                        ?>
                                    </div>
                                    <span class="rating-value"><?php echo number_format($rating, 1); ?></span>
                                </div>
                                
                                <div class="review-count">
                                    <?php echo $doctor['total_reviews'] ?? 0; ?> 
                                    <?php echo ($doctor['total_reviews'] ?? 0) == 1 ? 'review' : 'reviews'; ?>
                                </div>
                                
                                <?php if (!empty($doctor['bio'])): ?>
                                    <p style="color: var(--secondary-color); margin: 15px 0; font-size: 0.9rem;">
                                        <?php echo htmlspecialchars(substr($doctor['bio'], 0, 100)); ?>
                                        <?php echo strlen($doctor['bio']) > 100 ? '...' : ''; ?>
                                    </p>
                                <?php endif; ?>
                                
                                <button class="view-reviews-btn">
                                    <i class="far fa-comments"></i> View Reviews
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

        </div>
    </section>

    <!--=================================
    Footer start
    ============================== -->
    <footer id="pq-footer">
        <div class="pq-footer-style-1">
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
    <!--Footer End-->

    <!--Back To Top start-->
    <div id="back-to-top">
        <a class="topbtn" id="top" href="#top"><i class="ion-ios-arrow-up"></i></a>
    </div>
    <!--Back To Top End-->

    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>

<?php
session_start();
require_once 'config/database.php';
require_once 'config/helpers.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];
$getStartedUrl = getGetStartedUrl();

// Handle mark as read
if (isset($_GET['mark_read'])) {
    $notification_id = (int)$_GET['mark_read'];
    markNotificationAsRead($pdo, $notification_id, $user_id);
    header('Location: notifications.php');
    exit;
}

// Handle mark all as read
if (isset($_GET['mark_all_read'])) {
    markAllNotificationsAsRead($pdo, $user_id);
    $_SESSION['success'] = 'All notifications marked as read!';
    header('Location: notifications.php');
    exit;
}

// Handle delete notification
if (isset($_GET['delete'])) {
    $notification_id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM notifications WHERE id = ? AND user_id = ?");
    $stmt->execute([$notification_id, $user_id]);
    $_SESSION['success'] = 'Notification deleted!';
    header('Location: notifications.php');
    exit;
}

// Get filter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Get notifications
$sql = "SELECT * FROM notifications WHERE user_id = ?";
if ($filter === 'unread') {
    $sql .= " AND is_read = FALSE";
} elseif ($filter !== 'all') {
    $sql .= " AND type = ?";
}
$sql .= " ORDER BY created_at DESC LIMIT 100";

$stmt = $pdo->prepare($sql);
if ($filter !== 'all' && $filter !== 'unread') {
    $stmt->execute([$user_id, $filter]);
} else {
    $stmt->execute([$user_id]);
}
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get counts by type
$count_stmt = $pdo->prepare("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN is_read = FALSE THEN 1 ELSE 0 END) as unread,
        SUM(CASE WHEN type = 'appointment' THEN 1 ELSE 0 END) as appointment,
        SUM(CASE WHEN type = 'consultation' THEN 1 ELSE 0 END) as consultation,
        SUM(CASE WHEN type = 'prescription' THEN 1 ELSE 0 END) as prescription,
        SUM(CASE WHEN type = 'review' THEN 1 ELSE 0 END) as review,
        SUM(CASE WHEN type = 'system' THEN 1 ELSE 0 END) as system
    FROM notifications 
    WHERE user_id = ?
");
$count_stmt->execute([$user_id]);
$counts = $count_stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notifications – Medicate</title>
    
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
        
        .notifications-container {
            padding: 40px 0;
            min-height: 70vh;
        }
        
        .filter-bar {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .filter-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .filter-btn {
            padding: 10px 20px;
            border: 2px solid var(--grey-color);
            background: white;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
            color: var(--secondary-color);
            text-decoration: none;
            display: inline-block;
        }
        
        .filter-btn:hover, .filter-btn.active {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        .filter-btn .badge {
            background: var(--primary-color);
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.75rem;
            margin-left: 5px;
        }
        
        .filter-btn.active .badge {
            background: white;
            color: var(--primary-color);
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn-mark-all, .btn-clear-all {
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-mark-all {
            background: var(--primary-color);
            color: white;
        }
        
        .btn-mark-all:hover {
            background: var(--primary-dark-color);
            color: white;
        }
        
        .btn-clear-all {
            background: #dc3545;
            color: white;
        }
        
        .btn-clear-all:hover {
            background: #c82333;
        }
        
        .notification-item {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s;
            border-left: 4px solid var(--primary-color);
            display: flex;
            gap: 15px;
            align-items: start;
        }
        
        .notification-item:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            transform: translateX(5px);
        }
        
        .notification-item.unread {
            background: #f0f8ff;
            border-left-color: var(--primary-color);
            border-left-width: 5px;
        }
        
        .notification-item.read {
            opacity: 0.7;
            border-left-color: var(--secondary-color);
        }
        
        .notification-icon {
            font-size: 2rem;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            flex-shrink: 0;
        }
        
        .notification-icon.appointment {
            background: #e3f2fd;
            color: #2196f3;
        }
        
        .notification-icon.consultation {
            background: #f3e5f5;
            color: #9c27b0;
        }
        
        .notification-icon.prescription {
            background: #e8f5e9;
            color: #4caf50;
        }
        
        .notification-icon.review {
            background: #fff3e0;
            color: #ff9800;
        }
        
        .notification-icon.system {
            background: #fce4ec;
            color: #e91e63;
        }
        
        .notification-icon.blog, .notification-icon.comment {
            background: #e0f2f1;
            color: #009688;
        }
        
        .notification-content {
            flex: 1;
        }
        
        .notification-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 5px;
        }
        
        .notification-message {
            color: var(--secondary-color);
            margin-bottom: 10px;
            line-height: 1.6;
        }
        
        .notification-meta {
            display: flex;
            gap: 15px;
            align-items: center;
            font-size: 0.85rem;
            color: #999;
        }
        
        .notification-type-badge {
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .notification-type-badge.appointment {
            background: #e3f2fd;
            color: #2196f3;
        }
        
        .notification-type-badge.consultation {
            background: #f3e5f5;
            color: #9c27b0;
        }
        
        .notification-type-badge.prescription {
            background: #e8f5e9;
            color: #4caf50;
        }
        
        .notification-type-badge.review {
            background: #fff3e0;
            color: #ff9800;
        }
        
        .notification-type-badge.system {
            background: #fce4ec;
            color: #e91e63;
        }
        
        .notification-actions {
            display: flex;
            gap: 10px;
            flex-direction: column;
            align-items: flex-end;
        }
        
        .btn-notification-action {
            padding: 6px 12px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-mark-read {
            background: var(--grey-color);
            color: var(--dark-color);
        }
        
        .btn-mark-read:hover {
            background: var(--primary-color);
            color: white;
        }
        
        .btn-delete {
            background: transparent;
            color: #dc3545;
            border: 1px solid #dc3545;
        }
        
        .btn-delete:hover {
            background: #dc3545;
            color: white;
        }
        
        .btn-view-link {
            background: var(--primary-color);
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        
        .btn-view-link:hover {
            background: var(--primary-dark-color);
            color: white;
        }
        
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #999;
        }
        
        .empty-state i {
            font-size: 5rem;
            margin-bottom: 20px;
            opacity: 0.3;
        }
        
        .stats-summary {
            background: white;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .stat-label {
            color: var(--secondary-color);
            font-size: 0.9rem;
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
                            <h2>Notifications</h2>
                        </div>
                        <div class="pq-breadcrumb-container mt-2">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home mr-2"></i>Home</a></li>
                                <li class="breadcrumb-item"><a href="<?php echo $user_type; ?>-dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Notifications</li>
                            </ol>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="notifications-container">
        <div class="container">
            
            <!-- Messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php endif; ?>

            <!-- Statistics Summary -->
            <div class="stats-summary">
                <h5 class="mb-4">Notification Overview</h5>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo $counts['total']; ?></div>
                        <div class="stat-label">Total</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" style="color: #2196f3;"><?php echo $counts['unread']; ?></div>
                        <div class="stat-label">Unread</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" style="color: #4caf50;"><?php echo $counts['appointment']; ?></div>
                        <div class="stat-label">Appointments</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" style="color: #9c27b0;"><?php echo $counts['consultation']; ?></div>
                        <div class="stat-label">Consultations</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" style="color: #ff9800;"><?php echo $counts['prescription']; ?></div>
                        <div class="stat-label">Prescriptions</div>
                    </div>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="filter-bar">
                <div class="filter-buttons">
                    <a href="notifications.php?filter=all" class="filter-btn <?php echo $filter === 'all' ? 'active' : ''; ?>">
                        <i class="fas fa-list"></i> All
                        <span class="badge"><?php echo $counts['total']; ?></span>
                    </a>
                    <a href="notifications.php?filter=unread" class="filter-btn <?php echo $filter === 'unread' ? 'active' : ''; ?>">
                        <i class="fas fa-envelope"></i> Unread
                        <span class="badge"><?php echo $counts['unread']; ?></span>
                    </a>
                    <a href="notifications.php?filter=appointment" class="filter-btn <?php echo $filter === 'appointment' ? 'active' : ''; ?>">
                        <i class="far fa-calendar"></i> Appointments
                        <span class="badge"><?php echo $counts['appointment']; ?></span>
                    </a>
                    <a href="notifications.php?filter=consultation" class="filter-btn <?php echo $filter === 'consultation' ? 'active' : ''; ?>">
                        <i class="fas fa-stethoscope"></i> Consultations
                        <span class="badge"><?php echo $counts['consultation']; ?></span>
                    </a>
                    <a href="notifications.php?filter=prescription" class="filter-btn <?php echo $filter === 'prescription' ? 'active' : ''; ?>">
                        <i class="fas fa-prescription"></i> Prescriptions
                        <span class="badge"><?php echo $counts['prescription']; ?></span>
                    </a>
                </div>
                
                <div class="action-buttons">
                    <?php if ($counts['unread'] > 0): ?>
                        <a href="notifications.php?mark_all_read=1" class="btn-mark-all" onclick="return confirm('Mark all notifications as read?')">
                            <i class="fas fa-check-double"></i> Mark All Read
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="notifications-list">
                <?php if (empty($notifications)): ?>
                    <div class="empty-state">
                        <i class="far fa-bell-slash"></i>
                        <h4>No Notifications</h4>
                        <p>You don't have any notifications<?php echo $filter !== 'all' ? ' in this category' : ''; ?>.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($notifications as $notification): ?>
                        <div class="notification-item <?php echo $notification['is_read'] ? 'read' : 'unread'; ?>">
                            <div class="notification-icon <?php echo $notification['type']; ?>">
                                <?php
                                $icons = [
                                    'appointment' => 'fa-calendar-check',
                                    'consultation' => 'fa-stethoscope',
                                    'prescription' => 'fa-prescription',
                                    'review' => 'fa-star',
                                    'system' => 'fa-cog',
                                    'blog' => 'fa-newspaper',
                                    'comment' => 'fa-comment'
                                ];
                                $icon = $icons[$notification['type']] ?? 'fa-bell';
                                ?>
                                <i class="fas <?php echo $icon; ?>"></i>
                            </div>
                            
                            <div class="notification-content">
                                <div class="notification-title">
                                    <?php echo htmlspecialchars($notification['title']); ?>
                                    <?php if (!$notification['is_read']): ?>
                                        <span style="color: var(--primary-color); margin-left: 10px;">●</span>
                                    <?php endif; ?>
                                </div>
                                <div class="notification-message">
                                    <?php echo htmlspecialchars($notification['message']); ?>
                                </div>
                                <div class="notification-meta">
                                    <span class="notification-type-badge <?php echo $notification['type']; ?>">
                                        <?php echo ucfirst($notification['type']); ?>
                                    </span>
                                    <span>
                                        <i class="far fa-clock"></i>
                                        <?php echo formatDate($notification['created_at'], 'M d, Y g:i A'); ?>
                                    </span>
                                </div>
                                <?php if (!empty($notification['link'])): ?>
                                    <div style="margin-top: 15px;">
                                        <a href="<?php echo htmlspecialchars($notification['link']); ?>" class="btn-view-link">
                                            <i class="fas fa-arrow-right"></i> View Details
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="notification-actions">
                                <?php if (!$notification['is_read']): ?>
                                    <a href="notifications.php?mark_read=<?php echo $notification['id']; ?>" 
                                       class="btn-notification-action btn-mark-read"
                                       title="Mark as read">
                                        <i class="fas fa-check"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="notifications.php?delete=<?php echo $notification['id']; ?>" 
                                   class="btn-notification-action btn-delete"
                                   onclick="return confirm('Delete this notification?')"
                                   title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

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

    <script>
        // Auto refresh every 30 seconds
        setTimeout(function() {
            location.reload();
        }, 30000);
    </script>
</body>
</html>

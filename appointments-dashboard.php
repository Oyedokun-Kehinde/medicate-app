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

// Get user appointments
$all_appointments = getUserAppointments($pdo, $user_id, $user_type);
$upcoming_appointments = getUpcomingAppointments($pdo, $user_id, $user_type);
$pending_appointments = getUserAppointments($pdo, $user_id, $user_type, 'pending');
$confirmed_appointments = getUserAppointments($pdo, $user_id, $user_type, 'confirmed');
$completed_appointments = getUserAppointments($pdo, $user_id, $user_type, 'completed');

// Get user profile
if ($user_type === 'patient') {
    $stmt = $pdo->prepare("SELECT pp.* FROM patient_profiles pp WHERE pp.user_id = ?");
} else {
    $stmt = $pdo->prepare("SELECT dp.* FROM doctor_profiles dp WHERE dp.user_id = ?");
}
$stmt->execute([$user_id]);
$user_profile = $stmt->fetch(PDO::FETCH_ASSOC);

$page_title = $user_type === 'patient' ? 'My Appointments' : 'My Schedule';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $page_title; ?> – Medicate</title>
    
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="assets/fonts/flaticon/flaticon.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    
    <style>
        .dashboard-container {
            padding: 40px 0;
            min-height: 70vh;
        }
        .stats-card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        .stats-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #2490eb;
        }
        .stats-label {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .appointment-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid #2490eb;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }
        .appointment-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .appointment-card.pending {
            border-left-color: #ffc107;
        }
        .appointment-card.confirmed {
            border-left-color: #17a2b8;
        }
        .appointment-card.completed {
            border-left-color: #28a745;
        }
        .appointment-card.cancelled {
            border-left-color: #dc3545;
        }
        .appointment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        .appointment-date {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
        }
        .status-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-badge.pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-badge.confirmed {
            background: #d1ecf1;
            color: #0c5460;
        }
        .status-badge.completed {
            background: #d4edda;
            color: #155724;
        }
        .status-badge.cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        .appointment-details {
            margin: 10px 0;
        }
        .detail-item {
            display: flex;
            align-items: center;
            margin: 8px 0;
            color: #666;
        }
        .detail-item i {
            width: 20px;
            margin-right: 10px;
            color: #2490eb;
        }
        .appointment-actions {
            margin-top: 15px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn-action {
            padding: 8px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-confirm {
            background: #28a745;
            color: white;
        }
        .btn-confirm:hover {
            background: #218838;
            color: white;
        }
        .btn-cancel {
            background: #dc3545;
            color: white;
        }
        .btn-cancel:hover {
            background: #c82333;
            color: white;
        }
        .btn-complete {
            background: #17a2b8;
            color: white;
        }
        .btn-complete:hover {
            background: #138496;
            color: white;
        }
        .btn-view {
            background: #6c757d;
            color: white;
        }
        .btn-view:hover {
            background: #5a6268;
            color: white;
        }
        .tab-navigation {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .tab-btn {
            padding: 10px 25px;
            margin: 5px;
            border: none;
            background: #f8f9fa;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
        }
        .tab-btn.active {
            background: #2490eb;
            color: white;
        }
        .tab-btn:hover {
            background: #2490eb;
            color: white;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.3;
        }
        .calendar-view {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
    </style>
</head>

<body>
    <!-- Header -->
    <!-- php include 'includes/header.php';  -->

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
                              <li class="menu-item">
                                 <a href="index.php">Home</a>
                              </li>
                              <li class="menu-item ">
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
                                 <a href="blog.php">Blog</a>
                              </li>
                              <li class="menu-item ">
                                 <a href="case-study.php">Case Studies </a>
                              </li>
                              <li class="menu-item "></li>
                              <li class="menu-item">
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

    <!-- Breadcrumb -->
    <div class="pq-breadcrumb" style="background-image:url('assets/images/breadcrumb.jpg');">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                        <div class="pq-breadcrumb-title">
                            <h2><?php echo $page_title; ?></h2>
                        </div>
                        <div class="pq-breadcrumb-container mt-2">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home mr-2"></i>Home</a></li>
                                <li class="breadcrumb-item"><a href="<?php echo $user_type; ?>-dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                            </ol>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="dashboard-container">
        <div class="container">
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-number"><?php echo count($all_appointments); ?></div>
                        <div class="stats-label">Total Appointments</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-number" style="color: #ffc107;"><?php echo count($pending_appointments); ?></div>
                        <div class="stats-label">Pending</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-number" style="color: #17a2b8;"><?php echo count($confirmed_appointments); ?></div>
                        <div class="stats-label">Confirmed</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-number" style="color: #28a745;"><?php echo count($completed_appointments); ?></div>
                        <div class="stats-label">Completed</div>
                    </div>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="tab-navigation">
                <button class="tab-btn active" onclick="switchTab('upcoming')">
                    <i class="far fa-calendar-check"></i> Upcoming
                </button>
                <button class="tab-btn" onclick="switchTab('pending')">
                    <i class="far fa-clock"></i> Pending (<?php echo count($pending_appointments); ?>)
                </button>
                <button class="tab-btn" onclick="switchTab('confirmed')">
                    <i class="far fa-check-circle"></i> Confirmed (<?php echo count($confirmed_appointments); ?>)
                </button>
                <button class="tab-btn" onclick="switchTab('completed')">
                    <i class="fas fa-check-double"></i> Completed
                </button>
                <button class="tab-btn" onclick="switchTab('all')">
                    <i class="fas fa-list"></i> All Appointments
                </button>
            </div>

            <!-- Upcoming Tab -->
            <div id="upcoming-tab" class="tab-content active">
                <h4 class="mb-4">Upcoming Appointments</h4>
                <?php if (empty($upcoming_appointments)): ?>
                    <div class="empty-state">
                        <i class="far fa-calendar-times"></i>
                        <h5>No Upcoming Appointments</h5>
                        <p>You don't have any upcoming appointments scheduled.</p>
                        <?php if ($user_type === 'patient'): ?>
                            <a href="consultation.php" class="pq-button pq-button-flat mt-3">
                                <div class="pq-button-block">
                                    <span class="pq-button-text">Book Appointment</span>
                                    <i class="ion ion-plus-round"></i>
                                </div>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <?php foreach ($upcoming_appointments as $appointment): ?>
                        <?php include 'includes/appointment-card.php'; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Pending Tab -->
            <div id="pending-tab" class="tab-content">
                <h4 class="mb-4">Pending Appointments</h4>
                <?php if (empty($pending_appointments)): ?>
                    <div class="empty-state">
                        <i class="far fa-clock"></i>
                        <h5>No Pending Appointments</h5>
                        <p>You don't have any pending appointment requests.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($pending_appointments as $appointment): ?>
                        <?php include 'includes/appointment-card.php'; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Confirmed Tab -->
            <div id="confirmed-tab" class="tab-content">
                <h4 class="mb-4">Confirmed Appointments</h4>
                <?php if (empty($confirmed_appointments)): ?>
                    <div class="empty-state">
                        <i class="far fa-check-circle"></i>
                        <h5>No Confirmed Appointments</h5>
                        <p>You don't have any confirmed appointments.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($confirmed_appointments as $appointment): ?>
                        <?php include 'includes/appointment-card.php'; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Completed Tab -->
            <div id="completed-tab" class="tab-content">
                <h4 class="mb-4">Completed Appointments</h4>
                <?php if (empty($completed_appointments)): ?>
                    <div class="empty-state">
                        <i class="fas fa-check-double"></i>
                        <h5>No Completed Appointments</h5>
                        <p>You haven't completed any appointments yet.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($completed_appointments as $appointment): ?>
                        <?php include 'includes/appointment-card.php'; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- All Appointments Tab -->
            <div id="all-tab" class="tab-content">
                <h4 class="mb-4">All Appointments</h4>
                <?php if (empty($all_appointments)): ?>
                    <div class="empty-state">
                        <i class="fas fa-list"></i>
                        <h5>No Appointments</h5>
                        <p>You don't have any appointments in your history.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($all_appointments as $appointment): ?>
                        <?php include 'includes/appointment-card.php'; ?>
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
                                <h4 class="footer-title">Quick Links</h4>
                                <div class="menu-useful-links-container">
                                    <ul id="menu-useful-links" class="menu">
                                        <li><a href="about.php">About Us</a></li>
                                        <li><a href="contact.php">Contact Us</a></li>
                                        <li><a href="services.php">Our Services</a></li>
                                        <li><a href="specialists.php">Specialists</a></li>
                                        <li><a href="faqs.php">FAQs</a></li>
                                        <li><a href="case-study.php">Case Studies</a></li>
                                        <li><a href="consultation.php">Appointment</a></li>
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
        // Tab switching
        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remove active class from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabName + '-tab').classList.add('active');
            
            // Add active class to clicked button
            event.target.closest('.tab-btn').classList.add('active');
        }

        // Confirm action
        function confirmAction(appointmentId, action, message) {
            if (confirm(message)) {
                window.location.href = `ajax/update-appointment-status.php?id=${appointmentId}&action=${action}`;
            }
        }

        // Auto-refresh appointments every 60 seconds
        setInterval(function() {
            location.reload();
        }, 60000);
    </script>
</body>
</html>

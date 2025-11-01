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

// Get appointment ID
$appointment_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($appointment_id <= 0) {
    $_SESSION['error'] = 'Invalid appointment ID.';
    header('Location: appointments-dashboard.php');
    exit;
}

// Get appointment details
$appointment = getConsultationById($pdo, $appointment_id);

if (!$appointment) {
    $_SESSION['error'] = 'Appointment not found.';
    header('Location: appointments-dashboard.php');
    exit;
}

// Verify user has permission to view this appointment
if ($user_type === 'patient' && $appointment['patient_id'] != $user_id) {
    $_SESSION['error'] = 'You do not have permission to view this appointment.';
    header('Location: appointments-dashboard.php');
    exit;
}

if ($user_type === 'doctor' && $appointment['doctor_id'] != $user_id) {
    $_SESSION['error'] = 'You do not have permission to view this appointment.';
    header('Location: appointments-dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Appointment Details – Medicate</title>
    
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
        
        .details-container {
            padding: 40px 0;
            min-height: 70vh;
        }
        
        .details-card {
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .detail-section {
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 2px solid var(--grey-color);
        }
        
        .detail-section:last-child {
            border-bottom: none;
        }
        
        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-title i {
            color: var(--primary-color);
        }
        
        .detail-row {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: var(--secondary-color);
            width: 200px;
            flex-shrink: 0;
        }
        
        .detail-value {
            color: var(--dark-color);
            flex: 1;
        }
        
        .status-badge {
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-block;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-confirmed {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .status-completed {
            background: #d4edda;
            color: #155724;
        }
        
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 30px;
        }
        
        .btn-action {
            padding: 12px 30px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary {
            background: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark-color);
            color: white;
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background: #218838;
            color: white;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        @media (max-width: 768px) {
            .detail-row {
                flex-direction: column;
            }
            
            .detail-label {
                width: 100%;
                margin-bottom: 5px;
            }
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
    <header id="pq-header" class="pq-header-default ">
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
                        <div class="pq-header-contact ">
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
                            <a href="<?php echo $getStartedUrl; ?>" class="pq-button">
                                <div class="pq-button-block">
                                    <span class="pq-button-text">Get Started</span>
                                    <i class="ion ion-plus-round"></i>
                                </div>
                            </a>
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
                            <h2>Appointment Details</h2>
                        </div>
                        <div class="pq-breadcrumb-container mt-2">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home mr-2"></i>Home</a></li>
                                <li class="breadcrumb-item"><a href="<?php echo $user_type; ?>-dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="appointments-dashboard.php">Appointments</a></li>
                                <li class="breadcrumb-item active">Details</li>
                            </ol>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="details-container">
        <div class="container">
            <div class="details-card">
                
                <!-- Appointment Information -->
                <div class="detail-section">
                    <div class="section-title">
                        <i class="fas fa-calendar-check"></i>
                        Appointment Information
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Appointment ID:</div>
                        <div class="detail-value">#<?php echo str_pad($appointment['id'], 6, '0', STR_PAD_LEFT); ?></div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Status:</div>
                        <div class="detail-value">
                            <span class="status-badge status-<?php echo $appointment['status']; ?>">
                                <?php echo ucfirst($appointment['status']); ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Service:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($appointment['service_name']); ?></div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Date:</div>
                        <div class="detail-value"><?php echo formatDate($appointment['consultation_date'], 'l, F j, Y'); ?></div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Time:</div>
                        <div class="detail-value"><?php echo formatTime($appointment['consultation_time']); ?></div>
                    </div>
                    
                    <?php if (!empty($appointment['notes'])): ?>
                    <div class="detail-row">
                        <div class="detail-label">Notes:</div>
                        <div class="detail-value"><?php echo nl2br(htmlspecialchars($appointment['notes'])); ?></div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Patient Information -->
                <div class="detail-section">
                    <div class="section-title">
                        <i class="fas fa-user-injured"></i>
                        Patient Information
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Patient Name:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($appointment['patient_name']); ?></div>
                    </div>
                    
                    <?php if (!empty($appointment['patient_phone'])): ?>
                    <div class="detail-row">
                        <div class="detail-label">Phone:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($appointment['patient_phone']); ?></div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($user_type === 'doctor' && !empty($appointment['blood_group'])): ?>
                    <div class="detail-row">
                        <div class="detail-label">Blood Group:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($appointment['blood_group']); ?></div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($user_type === 'doctor' && !empty($appointment['allergies'])): ?>
                    <div class="detail-row">
                        <div class="detail-label">Allergies:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($appointment['allergies']); ?></div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Doctor Information -->
                <?php if (!empty($appointment['doctor_name'])): ?>
                <div class="detail-section">
                    <div class="section-title">
                        <i class="fas fa-user-md"></i>
                        Doctor Information
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Doctor Name:</div>
                        <div class="detail-value">Dr. <?php echo htmlspecialchars($appointment['doctor_name']); ?></div>
                    </div>
                    
                    <?php if (!empty($appointment['doctor_specialization'])): ?>
                    <div class="detail-row">
                        <div class="detail-label">Specialization:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($appointment['doctor_specialization']); ?></div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($appointment['doctor_phone'])): ?>
                    <div class="detail-row">
                        <div class="detail-label">Phone:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($appointment['doctor_phone']); ?></div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="appointments-dashboard.php" class="btn-action btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Appointments
                    </a>
                    
                    <?php if ($user_type === 'doctor' && $appointment['status'] === 'pending'): ?>
                        <a href="ajax/update-appointment-status.php?id=<?php echo $appointment['id']; ?>&action=confirm" 
                           class="btn-action btn-success"
                           onclick="return confirm('Confirm this appointment?')">
                            <i class="fas fa-check"></i> Confirm Appointment
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($user_type === 'patient' && in_array($appointment['status'], ['pending', 'confirmed'])): ?>
                        <a href="ajax/update-appointment-status.php?id=<?php echo $appointment['id']; ?>&action=cancel" 
                           class="btn-action btn-danger"
                           onclick="return confirm('Are you sure you want to cancel this appointment?')">
                            <i class="fas fa-times"></i> Cancel Appointment
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($user_type === 'doctor' && $appointment['status'] === 'completed'): ?>
                        <a href="create-prescription.php?consultation=<?php echo $appointment['id']; ?>" 
                           class="btn-action btn-primary">
                            <i class="fas fa-prescription"></i> Create Prescription
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($user_type === 'patient' && $appointment['status'] === 'completed'): ?>
                        <a href="rate-doctor.php?consultation=<?php echo $appointment['id']; ?>&doctor=<?php echo $appointment['doctor_id']; ?>" 
                           class="btn-action btn-primary">
                            <i class="fas fa-star"></i> Rate Doctor
                        </a>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer -->
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

    <!--Back To Top-->
    <div id="back-to-top">
        <a class="topbtn" id="top" href="#top"><i class="ion-ios-arrow-up"></i></a>
    </div>

    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>

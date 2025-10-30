<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$success_msg = $_GET['msg'] ?? null;
$error_msg = $_GET['error'] ?? null;

// Initialize all variables to prevent undefined errors
$doctor = [];
$consultations = [];
$all_patients = [];
$all_doctors = [];
$upcoming_consultations = [];
$servicesCount = 0;

try {
    // Fetch doctor data
    $stmt = $pdo->prepare("
        SELECT u.email, COALESCE(p.full_name, 'Doctor') as full_name, 
               COALESCE(p.specialization, 'Not specified') as specialization,
               p.bio, p.phone
        FROM users u
        LEFT JOIN doctor_profiles p ON u.id = p.user_id
        WHERE u.id = ?
    ");
    $stmt->execute([$user_id]);
    $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$doctor) {
        header('Location: login.php');
        exit;
    }

    // Fetch ALL consultations
    $stmt = $pdo->prepare("
        SELECT c.*, s.name as service_name, 
               COALESCE(up.full_name, u.email) as patient_name,
               u.email as patient_email
        FROM consultations c
        LEFT JOIN services s ON c.service_id = s.id
        LEFT JOIN users u ON c.patient_id = u.id
        LEFT JOIN patient_profiles up ON u.id = up.user_id
        ORDER BY c.created_at DESC
    ");
    $stmt->execute();
    $consultations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch ALL patients with consultation info
    $stmt = $pdo->prepare("
        SELECT DISTINCT
            u.id as user_id,
            u.email,
            COALESCE(pp.full_name, u.email) as patient_name,
            pp.phone,
            COUNT(DISTINCT c.id) as total_consultations
        FROM users u
        LEFT JOIN patient_profiles pp ON pp.user_id = u.id
        LEFT JOIN consultations c ON c.patient_id = u.id
        WHERE u.user_type = 'patient'
        GROUP BY u.id
        ORDER BY patient_name ASC
    ");
    $stmt->execute();
    $all_patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Services count
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM services");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $servicesCount = $result['count'] ?? 0;

    // Fetch ALL doctors with FULL details (including current doctor)
    $stmt = $pdo->prepare("
        SELECT 
            COALESCE(dp.full_name, u.email) as full_name, 
            COALESCE(dp.specialization, 'General Practice') as specialization, 
            dp.bio, 
            dp.phone,
            u.email, 
            u.id as user_id,
            COUNT(DISTINCT c.id) as total_consultations
        FROM users u
        LEFT JOIN doctor_profiles dp ON dp.user_id = u.id
        LEFT JOIN consultations c ON c.doctor_id = u.id AND c.status = 'completed'
        WHERE u.user_type = 'doctor'
        GROUP BY u.id
        ORDER BY full_name ASC
    ");
    $stmt->execute();
    $all_doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch upcoming consultations for this doctor
    $stmt = $pdo->prepare("
        SELECT c.*, s.name as service_name, 
               COALESCE(up.full_name, u.email) as patient_name,
               u.email as patient_email
        FROM consultations c
        LEFT JOIN services s ON c.service_id = s.id
        LEFT JOIN users u ON c.patient_id = u.id
        LEFT JOIN patient_profiles up ON u.id = up.user_id
        WHERE c.doctor_id = ? AND c.status IN ('confirmed', 'pending')
        ORDER BY c.consultation_date ASC, c.consultation_time ASC
    ");
    $stmt->execute([$user_id]);
    $upcoming_consultations = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Doctor Dashboard Error: " . $e->getMessage());
    $error_msg = "Error loading dashboard. Please try again later.";
}

// Safe variable assignments with defaults
$display_name = htmlspecialchars($doctor['full_name'] ?? 'Doctor');
$email = htmlspecialchars($doctor['email'] ?? '');
$specialization = htmlspecialchars($doctor['specialization'] ?? 'Not specified');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard - Medicate</title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/doctor-dashboard.css">
</head>

<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <div class="sidebar-logo">
                <a href="index.php">
                    <img src="assets/images/footer_logo.png" alt="Medicate Logo">
                </a>
            </div>
            <ul class="sidebar-menu">
                <li><a class="nav-link active" data-section="dashboard"><i class="fas fa-chart-line"></i> Dashboard</a>
                </li>
                <li><a class="nav-link" data-section="consultations"><i class="fas fa-calendar-check"></i> All
                        Consultations</a></li>
                <li>
                    <a class="nav-link" data-section="upcoming"><i class="fas fa-calendar-day"></i> Upcoming</a></li>
                <li>
                    <a class="nav-link" data-section="doctors"><i class="fas fa-user-md"></i> All Doctors</a></li>
                <li>
                    <a class="nav-link" data-section="patients"><i class="fas fa-users"></i> All Patients</a>
                </li>
                <li>
                    <a class="nav-link" data-section="services"><i class="fas fa-hospital"></i> Our Services</a></li>
                <li>
                    <a class="nav-link" data-section="blogs"><i class="fas fa-blog"></i> My Blogs</a>
                </li>
                <li>
                    <a href="blog.php"><i class="fas fa-blog"></i> Blogs </a>
                </li>
                <li> 
                    <a class="nav-link" data-section="profile"> <i class="fas fa-user-cog"></i> My Profile</a> </li>
                <li><a href="faqs.php"><i class="fas fa-question-circle"></i> FAQs</a></li>
                <li><a href="contact.php"><i class="fas fa-phone"></i> Contact Us</a></li>
            </ul>
            <div class="sidebar-footer">
                <div class="user-info">
                    <p><strong><?php echo $display_name; ?></strong></p>
                    <p><?php echo $specialization; ?></p>
                    <p><?php echo $email; ?></p>
                </div>
                <form action="logout.php" method="POST" style="margin: 0;">
                    <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
                </form>
            </div>
        </div>

        <div class="main-content">
            <div class="top-bar">
                <div class="top-bar-left">
                    <h2 id="pageTitle">Dashboard</h2>
                </div>
                <div class="top-bar-right">
                    <div class="user-display">
                        <i class="fas fa-user-md"></i>
                        <span><?php echo $display_name; ?></span>
                    </div>
                    <div class="time-display">
                        <i class="fas fa-calendar"></i> <span id="currentDate">Loading...</span>
                    </div>
                    <div class="time-display">
                        <i class="fas fa-clock"></i> <span id="currentTime">00:00</span>
                    </div>
                </div>
            </div>

            <?php if ($success_msg): ?>
                <div class="alert alert-success" style="margin: 20px 30px 0 30px;">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_msg); ?>
                </div>
            <?php endif; ?>
            <?php if ($error_msg): ?>
                <div class="alert alert-danger" style="margin: 20px 30px 0 30px;">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_msg); ?>
                </div>
            <?php endif; ?>

            <div class="content-area">
                <!-- DASHBOARD -->
                <div id="dashboard" class="section active">
                    <div class="welcome-card">
                        <h1>Welcome, Dr. <?php echo $display_name; ?>!</h1>
                        <p>Manage your consultations and patient requests</p>
                    </div>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <i class="fas fa-calendar-check"></i>
                            <h3><?php echo count($consultations); ?></h3>
                            <p>Total Consultations</p>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-calendar-day"></i>
                            <h3><?php echo count($upcoming_consultations); ?></h3>
                            <p>Upcoming</p>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-user-md"></i>
                            <h3><?php echo count($all_doctors); ?></h3>
                            <p>Doctors in Network</p>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-users"></i>
                            <h3><?php echo count($all_patients); ?></h3>
                            <p>Total Patients</p>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-hospital"></i>
                            <h3><?php echo $servicesCount; ?></h3>
                            <p>Services Available</p>
                        </div>
                    </div>
                    <div class="cards-section">
                        <div class="card-box">
                            <div class="card-header"><i class="fas fa-calendar-check"></i> Manage Consultations</div>
                            <div class="card-body">
                                <p>View and respond to all patient consultation requests.</p>
                                <a class="btn-primary nav-link-btn" data-section="consultations">View All</a>
                            </div>
                        </div>
                        <div class="card-box">
                            <div class="card-header"><i class="fas fa-calendar-day"></i> Upcoming Consultations</div>
                            <div class="card-body">
                                <p>View your scheduled consultations.</p>
                                <a class="btn-primary nav-link-btn" data-section="upcoming">View Schedule</a>
                            </div>
                        </div>
                        <div class="card-box">
                            <div class="card-header"><i class="fas fa-user-md"></i> Update Profile</div>
                            <div class="card-body">
                                <p>Keep your professional information up to date.</p>
                                <a class="btn-primary nav-link-btn" data-section="profile">Edit Profile</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ALL CONSULTATIONS -->
                <div id="consultations" class="section">
                    <div class="card-box">
                        <div class="card-header"><i class="fas fa-calendar-check"></i> All Consultations</div>
                        <div class="card-body">
                            <?php if (empty($consultations)): ?>
                                <div class="empty-state">
                                    <i class="fas fa-calendar"></i>
                                    <h4>No Consultations Yet</h4>
                                    <p>Patient requests will appear here.</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($consultations as $c): ?>
                                    <div class="consultation-item status-<?php echo $c['status']; ?>">
                                        <div class="consultation-header">
                                            <h4><?php echo htmlspecialchars($c['service_name'] ?? 'Service'); ?></h4>
                                            <span class="consultation-status"><?php echo ucfirst($c['status']); ?></span>
                                        </div>
                                        <p><strong>Patient:</strong>
                                            <?php echo htmlspecialchars($c['patient_name'] ?? 'N/A'); ?></p>
                                        <p><strong>Email:</strong> <?php echo htmlspecialchars($c['patient_email'] ?? 'N/A'); ?>
                                        </p>
                                        <?php if (!empty($c['consultation_date'])): ?>
                                            <p><strong>Date:</strong>
                                                <?php echo date('F d, Y', strtotime($c['consultation_date'])); ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($c['consultation_time'])): ?>
                                            <p><strong>Time:</strong>
                                                <?php echo date('g:i A', strtotime($c['consultation_time'])); ?></p>
                                        <?php endif; ?>
                                        <p><strong>Notes:</strong> <?php echo htmlspecialchars($c['notes'] ?? 'No notes'); ?>
                                        </p>
                                        <?php if ($c['status'] === 'pending'): ?>
                                            <div class="consultation-actions">
                                                <form method="POST" action="process-consultation-update.php"
                                                    style="display:inline;">
                                                    <input type="hidden" name="consultation_id" value="<?php echo $c['id']; ?>">
                                                    <input type="hidden" name="status" value="confirmed">
                                                    <input type="hidden" name="doctor_id" value="<?php echo $user_id; ?>">
                                                    <button type="submit" class="btn btn-sm btn-success">Accept</button>
                                                </form>
                                                <form method="POST" action="process-consultation-update.php"
                                                    style="display:inline;">
                                                    <input type="hidden" name="consultation_id" value="<?php echo $c['id']; ?>">
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                                </form>
                                            </div>
                                        <?php elseif ($c['status'] === 'confirmed'): ?>
                                            <form method="POST" action="process-consultation-update.php">
                                                <input type="hidden" name="consultation_id" value="<?php echo $c['id']; ?>">
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="btn btn-sm btn-primary">Mark as Completed</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- UPCOMING -->
                <div id="upcoming" class="section">
                    <div class="card-box">
                        <div class="card-header"><i class="fas fa-calendar-day"></i> Upcoming Consultations</div>
                        <div class="card-body">
                            <?php if (empty($upcoming_consultations)): ?>
                                <div class="empty-state">
                                    <i class="fas fa-calendar"></i>
                                    <h4>No Upcoming Consultations</h4>
                                    <p>You don't have any scheduled consultations.</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($upcoming_consultations as $c): ?>
                                    <div class="consultation-item status-<?php echo $c['status']; ?>">
                                        <div class="consultation-header">
                                            <h4><?php echo htmlspecialchars($c['service_name'] ?? 'Service'); ?></h4>
                                            <span class="consultation-status"><?php echo ucfirst($c['status']); ?></span>
                                        </div>
                                        <p><strong>Patient:</strong>
                                            <?php echo htmlspecialchars($c['patient_name'] ?? 'N/A'); ?></p>
                                        <p><strong>Email:</strong> <?php echo htmlspecialchars($c['patient_email'] ?? 'N/A'); ?>
                                        </p>
                                        <?php if (!empty($c['consultation_date'])): ?>
                                            <p><strong>Date:</strong>
                                                <?php echo date('F d, Y', strtotime($c['consultation_date'])); ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($c['consultation_time'])): ?>
                                            <p><strong>Time:</strong>
                                                <?php echo date('g:i A', strtotime($c['consultation_time'])); ?></p>
                                        <?php endif; ?>
                                        <p><strong>Notes:</strong> <?php echo htmlspecialchars($c['notes'] ?? 'No notes'); ?>
                                        </p>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- ALL DOCTORS -->
                <div id="doctors" class="section">
                    <div class="card-box">
                        <div class="card-header"><i class="fas fa-user-md"></i> All Doctors in Network</div>
                        <div class="card-body">
                            <?php if (empty($all_doctors)): ?>
                                <div class="empty-state">
                                    <i class="fas fa-user-md"></i>
                                    <h4>No Doctors Found</h4>
                                    <p>There are no doctors in the system.</p>
                                </div>
                            <?php else: ?>
                                <div class="doctors-grid-enhanced">
                                    <?php foreach ($all_doctors as $doc): ?>
                                        <div class="doctor-card-enhanced">
                                            <div class="doctor-avatar">
                                                <i class="fas fa-user-md"></i>
                                            </div>
                                            <div class="doctor-details">
                                                <h5><?php echo htmlspecialchars($doc['full_name']); ?></h5>
                                                <p class="doctor-spec"><i class="fas fa-stethoscope"></i>
                                                    <?php echo htmlspecialchars($doc['specialization'] ?: 'General Practice'); ?>
                                                </p>
                                                <p class="doctor-email"><i class="fas fa-envelope"></i>
                                                    <?php echo htmlspecialchars($doc['email']); ?></p>
                                                <?php if (!empty($doc['phone'])): ?>
                                                    <p class="doctor-phone"><i class="fas fa-phone"></i>
                                                        <?php echo htmlspecialchars($doc['phone']); ?></p>
                                                <?php endif; ?>
                                                <?php if (!empty($doc['bio'])): ?>
                                                    <p class="doctor-bio"><?php echo htmlspecialchars($doc['bio']); ?></p>
                                                <?php endif; ?>
                                                <div class="doctor-stats">
                                                    <span class="stat-badge">
                                                        <i class="fas fa-check-circle"></i>
                                                        <?php echo $doc['total_consultations']; ?> Completed
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>


                <!-- ALL PATIENTS -->
                <div id="patients" class="section">
                    <div class="card-box">
                        <div class="card-header"><i class="fas fa-users"></i> All Patients</div>
                        <div class="card-body">
                            <?php if (empty($all_patients)): ?>
                                <div class="empty-state">
                                    <i class="fas fa-users"></i>
                                    <h4>No Patients Found</h4>
                                    <p>There are no patients in the system.</p>
                                </div>
                            <?php else: ?>
                                <div class="patients-grid">
                                    <?php foreach ($all_patients as $patient): ?>
                                        <div class="patient-card">
                                            <div class="patient-avatar">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div class="patient-details">
                                                <h5><?php echo htmlspecialchars($patient['patient_name']); ?></h5>
                                                <p class="patient-email"><i class="fas fa-envelope"></i>
                                                    <?php echo htmlspecialchars($patient['email']); ?></p>
                                                <?php if (!empty($patient['phone'])): ?>
                                                    <p class="patient-phone"><i class="fas fa-phone"></i>
                                                        <?php echo htmlspecialchars($patient['phone']); ?></p>
                                                <?php endif; ?>
                                                <div class="patient-stats">
                                                    <span class="stat-badge">
                                                        <i class="fas fa-calendar-check"></i>
                                                        <?php echo $patient['total_consultations']; ?>
                                                        Consultation<?php echo $patient['total_consultations'] !== 1 ? 's' : ''; ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>


                <!-- SERVICES TAB -->
                <div id="services" class="section">
                    <div class="card-box">
                        <div class="card-header"><i class="fas fa-hospital"></i> Our Medical Services</div>
                        <div class="card-body">
                            <div class="services-grid">
                                <!-- Angioplasty -->
                                <a href="services/angioplasty.php" class="service-card-link">
                                    <div class="service-card">
                                        <div class="service-icon"
                                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <i class="fas fa-heartbeat"></i>
                                        </div>
                                        <h4>Angioplasty</h4>
                                        <p>Advanced cardiac intervention procedure to open blocked blood vessels and
                                            restore normal blood flow to the heart.</p>
                                        <div class="service-features">
                                            <span><i class="fas fa-check"></i> Minimally Invasive</span>
                                            <span><i class="fas fa-check"></i> Quick Recovery</span>
                                        </div>
                                        <div class="service-view-btn">
                                            <i class="fas fa-arrow-right"></i> VIEW SERVICE
                                        </div>
                                    </div>
                                </a>

                                <!-- Cardiology -->
                                <a href="services/cardiology.php" class="service-card-link">
                                    <div class="service-card">
                                        <div class="service-icon"
                                            style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                            <i class="fas fa-heart"></i>
                                        </div>
                                        <h4>Cardiology</h4>
                                        <p>Comprehensive heart care including diagnosis, treatment, and management of
                                            cardiovascular conditions.</p>
                                        <div class="service-features">
                                            <span><i class="fas fa-check"></i> ECG & Echo</span>
                                            <span><i class="fas fa-check"></i> 24/7 Emergency</span>
                                        </div>
                                        <div class="service-view-btn">
                                            <i class="fas fa-arrow-right"></i> VIEW SERVICE
                                        </div>
                                    </div>
                                </a>

                                <!-- Dental -->
                                <a href="services/dental.php" class="service-card-link">
                                    <div class="service-card">
                                        <div class="service-icon"
                                            style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                            <i class="fas fa-tooth"></i>
                                        </div>
                                        <h4>Dental Care</h4>
                                        <p>Complete dental solutions from preventive care to cosmetic dentistry. Gentle,
                                            pain-free treatments.</p>
                                        <div class="service-features">
                                            <span><i class="fas fa-check"></i> Cosmetic Dentistry</span>
                                            <span><i class="fas fa-check"></i> Orthodontics</span>
                                        </div>
                                        <div class="service-view-btn">
                                            <i class="fas fa-arrow-right"></i> VIEW SERVICE
                                        </div>
                                    </div>
                                </a>

                                <!-- Endocrinology -->
                                <a href="services/endocrinology.php" class="service-card-link">
                                    <div class="service-card">
                                        <div class="service-icon"
                                            style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                                            <i class="fas fa-dna"></i>
                                        </div>
                                        <h4>Endocrinology</h4>
                                        <p>Expert care for hormone-related disorders including diabetes, thyroid
                                            conditions, and metabolic diseases.</p>
                                        <div class="service-features">
                                            <span><i class="fas fa-check"></i> Diabetes Management</span>
                                            <span><i class="fas fa-check"></i> Hormone Therapy</span>
                                        </div>
                                        <div class="service-view-btn">
                                            <i class="fas fa-arrow-right"></i> VIEW SERVICE
                                        </div>
                                    </div>
                                </a>

                                <!-- Eye Care -->
                                <a href="services/eye-care.php" class="service-card-link">
                                    <div class="service-card">
                                        <div class="service-icon"
                                            style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);">
                                            <i class="fas fa-eye"></i>
                                        </div>
                                        <h4>Eye Care</h4>
                                        <p>Comprehensive ophthalmology services including vision correction, cataract
                                            surgery, and treatment of eye diseases.</p>
                                        <div class="service-features">
                                            <span><i class="fas fa-check"></i> LASIK Surgery</span>
                                            <span><i class="fas fa-check"></i> Cataract Treatment</span>
                                        </div>
                                        <div class="service-view-btn">
                                            <i class="fas fa-arrow-right"></i> VIEW SERVICE
                                        </div>
                                    </div>
                                </a>

                                <!-- Neurology -->
                                <a href="services/neurology.php" class="service-card-link">
                                    <div class="service-card">
                                        <div class="service-icon"
                                            style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                                            <i class="fas fa-brain"></i>
                                        </div>
                                        <h4>Neurology</h4>
                                        <p>Advanced neurological care for brain, spine, and nervous system disorders.
                                            Expert diagnosis and treatment.</p>
                                        <div class="service-features">
                                            <span><i class="fas fa-check"></i> Brain Imaging</span>
                                            <span><i class="fas fa-check"></i> Stroke Care</span>
                                        </div>
                                        <div class="service-view-btn">
                                            <i class="fas fa-arrow-right"></i> VIEW SERVICE
                                        </div>
                                    </div>
                                </a>

                                <!-- Orthopaedics -->
                                <a href="services/orthopaedics.php" class="service-card-link">
                                    <div class="service-card">
                                        <div class="service-icon"
                                            style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
                                            <i class="fas fa-bone"></i>
                                        </div>
                                        <h4>Orthopaedics</h4>
                                        <p>Specialized care for musculoskeletal conditions, joint replacements, and
                                            sports injuries.</p>
                                        <div class="service-features">
                                            <span><i class="fas fa-check"></i> Joint Replacement</span>
                                            <span><i class="fas fa-check"></i> Sports Medicine</span>
                                        </div>
                                        <div class="service-view-btn">
                                            <i class="fas fa-arrow-right"></i> VIEW SERVICE
                                        </div>
                                    </div>
                                </a>

                                <!-- RMI -->
                                <a href="services/rmi.php" class="service-card-link">
                                    <div class="service-card">
                                        <div class="service-icon"
                                            style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
                                            <i class="fas fa-x-ray"></i>
                                        </div>
                                        <h4>RMI - Medical Imaging</h4>
                                        <p>State-of-the-art diagnostic imaging services including X-ray, CT, MRI, and
                                            ultrasound.</p>
                                        <div class="service-features">
                                            <span><i class="fas fa-check"></i> MRI & CT Scans</span>
                                            <span><i class="fas fa-check"></i> Digital X-Ray</span>
                                        </div>
                                        <div class="service-view-btn">
                                            <i class="fas fa-arrow-right"></i> VIEW SERVICE
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
  
                <!-- BLOG SECTION FOR DOCTOR -->
                <div id="blogs" class="section">
                    <div class="card-box">
                        <div class="card-header">
                            <i class="fas fa-blog"></i> My Blog Posts
                            <button class="btn-primary" id="createBlogBtn" style="float: right; padding: 8px 15px;">
                                <i class="fas fa-plus"></i> New Post
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="blogsList"></div>
                        </div>
                    </div>
                </div>

                <!-- BLOG CREATE/EDIT FORM MODAL -->
                <div id="blogFormModal" class="modal" style="display: none;">
                    <div class="modal-content" style="max-width: 900px;">
                        <div class="modal-header">
                            <h2>Create Blog Post</h2>
                            <span class="close-modal">&times;</span>
                        </div>
                        <form id="blogForm" enctype="multipart/form-data">
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Post Title *</label>
                                    <input type="text" name="title" id="blogTitle" class="form-control"
                                        placeholder="Enter post title" required>
                                </div>
                            </div>
                        
                            <!-- Blog Image  -->
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Featured Image</label>
                                    <input type="file" name="featured_image" id="blogImage" class="form-control"
                                        accept="image/*">
                                    <small>Recommended size: 1200x600px, Max 5MB</small>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Excerpt (Summary)</label>
                                    <textarea name="excerpt" id="blogExcerpt" class="form-control" rows="3"
                                        placeholder="Brief summary of your post"></textarea>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Content *</label>
                                    <textarea name="content" id="blogContent" class="form-control" rows="10"
                                        placeholder="Write your blog content here..." required></textarea>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Status</label>
                                    <select name="status" id="blogStatus" class="form-control">
                                        <option value="published">Publish Now</option>
                                        <option value="draft">Save as Draft</option>
                                    </select>
                                </div>
                            </div>

                            <div style="display: flex; gap: 10px; margin-top: 20px;">
                                <button type="submit" class="btn-primary">Publish Post</button>
                                <button type="button" class="btn-secondary" id="cancelBlogBtn">Cancel</button>
                            </div>

                            <div id="blogFormMessage" class="alert" style="display: none; margin-top: 15px;"></div>
                        </form>
                    </div>
                </div>

                <style>
                    .modal {
                        position: fixed;
                        z-index: 1000;
                        left: 0;
                        top: 0;
                        width: 100%;
                        height: 100%;
                        background-color: rgba(0, 0, 0, 0.5);
                        overflow: auto;
                    }

                    .modal-content {
                        background-color: #fff;
                        margin: 5% auto;
                        padding: 30px;
                        border-radius: 8px;
                        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
                    }

                    .modal-header {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        margin-bottom: 20px;
                        padding-bottom: 15px;
                        border-bottom: 1px solid #e0e0e0;
                    }

                    .modal-header h2 {
                        margin: 0;
                        color: #333;
                    }

                    .close-modal {
                        font-size: 28px;
                        font-weight: bold;
                        color: #aaa;
                        cursor: pointer;
                    }

                    .close-modal:hover {
                        color: #000;
                    }

                    .blog-item {
                        padding: 15px;
                        margin-bottom: 15px;
                        border-left: 4px solid #667eea;
                        background: #f9f9f9;
                        border-radius: 4px;
                    }

                    .blog-item h4 {
                        margin: 0 0 10px 0;
                        color: #333;
                    }

                    .blog-meta {
                        font-size: 13px;
                        color: #999;
                        margin-bottom: 10px;
                    }

                    .blog-actions {
                        display: flex;
                        gap: 10px;
                        margin-top: 10px;
                    }

                    .blog-status {
                        display: inline-block;
                        padding: 4px 12px;
                        border-radius: 12px;
                        font-size: 12px;
                        font-weight: bold;
                    }

                    .blog-status.published {
                        background: #d4edda;
                        color: #155724;
                    }

                    .blog-status.draft {
                        background: #fff3cd;
                        color: #856404;
                    }

                    .alert {
                        padding: 12px 15px;
                        border-radius: 4px;
                        margin: 0;
                    }

                    .alert.success {
                        background: #d4edda;
                        color: #155724;
                        border: 1px solid #c3e6cb;
                    }

                    .alert.error {
                        background: #f8d7da;
                        color: #721c24;
                        border: 1px solid #f5c6cb;
                    }
                </style>

                <!-- PROFILE -->
                <div id="profile" class="section">
                    <div class="card-box">
                        <div class="card-header"><i class="fas fa-user-cog"></i> My Profile</div>
                        <div class="card-body">
                            <form id="profileForm" method="POST" action="process-doctor-profile.php">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" name="full_name" class="form-control"
                                            value="<?php echo htmlspecialchars($doctor['full_name']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" name="email" class="form-control"
                                            value="<?php echo htmlspecialchars($email); ?>" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Specialization</label>
                                        <input type="text" name="specialization" class="form-control"
                                            value="<?php echo htmlspecialchars($specialization); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Phone Number</label>
                                        <input type="tel" name="phone" class="form-control"
                                            value="<?php echo htmlspecialchars($doctor['phone'] ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Bio</label>
                                    <textarea name="bio" class="form-control"
                                        rows="4"><?php echo htmlspecialchars($doctor['bio'] ?? ''); ?></textarea>
                                </div>
                                <div style="display: flex; gap: 10px;">
                                    <button type="submit" class="btn-primary">Save Changes</button>
                                    <button type="button" class="btn-secondary"
                                        onclick="window.location.reload()">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </div>

    <div id="snackbar"></div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/doctor-dashboard.js"></script>
</body>

</html>
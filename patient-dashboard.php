<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$success_msg = $_GET['msg'] ?? null;
$error_msg = $_GET['error'] ?? null;

try {
    // Fetch patient info
    $stmt = $pdo->prepare("
        SELECT u.email, COALESCE(p.full_name, '') as full_name,
               p.phone, p.address, p.blood_group, p.emergency_contact_phone,
               p.medical_history, p.allergies
        FROM users u
        LEFT JOIN patient_profiles p ON u.id = p.user_id
        WHERE u.id = ?
    ");
    $stmt->execute([$user_id]);
    $patient = $stmt->fetch();

    if (!$patient) {
        header('Location: login.php');
        exit;
    }

    // Fetch ALL consultations with FULL doctor info
    $stmt = $pdo->prepare("
        SELECT c.*, 
               s.name as service_name,
               dp.full_name as doctor_name,
               dp.specialization as doctor_specialization,
               dp.phone as doctor_phone,
               du.email as doctor_email
        FROM consultations c
        LEFT JOIN services s ON c.service_id = s.id
        LEFT JOIN users du ON c.doctor_id = du.id
        LEFT JOIN doctor_profiles dp ON du.id = dp.user_id
        WHERE c.patient_id = ?
        ORDER BY c.created_at DESC
    ");
    $stmt->execute([$user_id]);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Separate upcoming consultations (confirmed status + future/today date)
    $today = date('Y-m-d');
    $upcoming = array_filter($appointments, function($apt) use ($today) {
        return $apt['status'] === 'confirmed' && 
               !empty($apt['consultation_date']) && 
               $apt['consultation_date'] >= $today;
    });

    // Stats
    $totalAppointments = count($appointments);
    $completedCount = count(array_filter($appointments, fn($a) => $a['status'] === 'completed'));
    $pendingCount = count(array_filter($appointments, fn($a) => in_array($a['status'], ['pending', 'confirmed'])));
    $upcomingCount = count($upcoming);

    // Services count
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM services");
    $stmt->execute();
    $servicesCount = $stmt->fetch()['count'];

} catch (PDOException $e) {
    error_log("Patient Dashboard Error: " . $e->getMessage());
    $error_msg = "Error loading dashboard.";
    $appointments = [];
    $upcoming = [];
    $patient = ['full_name' => '', 'email' => '', 'phone' => '', 'address' => '', 
                'blood_group' => '', 'emergency_contact_phone' => '', 
                'medical_history' => '', 'allergies' => ''];
    $totalAppointments = 0;
    $completedCount = 0;
    $pendingCount = 0;
    $upcomingCount = 0;
    $servicesCount = 0;
}

$display_name = !empty($patient['full_name']) ? $patient['full_name'] : 'Patient';
$email = $patient['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard - Medicate</title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/patient-dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- SIDEBAR (keep your existing sidebar) -->
        <div class="sidebar">
            <div class="sidebar-logo">
                <a href="index.php">
                    <img src="assets/images/footer_logo.png" alt="Medicate Logo">
                </a>
            </div>
            <ul class="sidebar-menu">
                <li><a class="nav-link active" data-section="dashboard"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                <li><a class="nav-link" data-section="appointments"><i class="fas fa-calendar-check"></i> My Consultations</a></li>
                <li><a class="nav-link" data-section="upcoming"><i class="fas fa-calendar-day"></i> Upcoming</a></li>
                <li><a class="nav-link" data-section="consultation"><i class="fas fa-stethoscope"></i> Request Consultation</a></li>
                <li><a class="nav-link" data-section="profile"><i class="fas fa-user-circle"></i> My Profile</a></li>
                <li><a href="faqs.php"><i class="fas fa-question-circle"></i> FAQs</a></li>
                <li><a href="contact.php"><i class="fas fa-phone"></i> Contact Us</a></li>
            </ul>
            <div class="sidebar-footer">
                <div class="user-info">
                    <p><strong><?php echo htmlspecialchars($display_name); ?></strong></p>
                    <p><?php echo htmlspecialchars($email); ?></p>
                </div>
                <form action="logout.php" method="POST" style="margin: 0;">
                    <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
                </form>
            </div>
        </div>

        <div class="main-content">
            <!-- TOP BAR (keep your existing top bar) -->
            <div class="top-bar">
                <div class="top-bar-left">
                    <h2 id="pageTitle">Dashboard</h2>
                </div>
                <div class="top-bar-right">
                    <div class="user-display">
                        <i class="fas fa-user-circle"></i>
                        <span><?php echo htmlspecialchars($display_name); ?></span>
                    </div>
                    <div class="time-display">
                        <i class="fas fa-clock"></i> <span id="currentTime">00:00</span>
                    </div>
                </div>
            </div>

            <!-- ALERTS -->
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
                <!-- DASHBOARD SECTION -->
                <div id="dashboard" class="section active">
                    <div class="welcome-card">
                        <h1>Welcome, <?php echo htmlspecialchars($display_name); ?>!</h1>
                        <p>Here's an overview of your health journey with us</p>
                    </div>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <i class="fas fa-calendar-check"></i>
                            <h3><?php echo $totalAppointments; ?></h3>
                            <p>Total Consultations</p>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-calendar-day"></i>
                            <h3><?php echo $upcomingCount; ?></h3>
                            <p>Upcoming</p>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-check-circle"></i>
                            <h3><?php echo $completedCount; ?></h3>
                            <p>Completed</p>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-hospital"></i>
                            <h3><?php echo $servicesCount; ?></h3>
                            <p>Services Available</p>
                        </div>
                    </div>
                    <!-- Keep your existing cards-section -->
                    <div class="cards-section">
                        <div class="card-box">
                            <div class="card-header"><i class="fas fa-stethoscope"></i> Quick Consultation</div>
                            <div class="card-body">
                                <p style="margin-bottom: 20px; color: #666;">Request a consultation with healthcare professionals.</p>
                                <a class="btn-primary nav-link-btn" data-section="consultation">Request Now</a>
                            </div>
                        </div>
                        <div class="card-box">
                            <div class="card-header"><i class="fas fa-user-circle"></i> Update Profile</div>
                            <div class="card-body">
                                <p style="margin-bottom: 20px; color: #666;">Keep your profile information up to date.</p>
                                <a class="btn-primary nav-link-btn" data-section="profile">Edit Profile</a>
                            </div>
                        </div>
                        <div class="card-box">
                            <div class="card-header"><i class="fas fa-heart"></i> Health Tips</div>
                            <div class="card-body">
                                <p style="margin-bottom: 20px; color: #666;">Stay informed with our latest health articles.</p>
                                <a href="blog.php" class="btn-primary">Read Articles</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ALL CONSULTATIONS SECTION -->
                <div id="appointments" class="section">
                    <div class="card-box">
                        <div class="card-header"><i class="fas fa-calendar-check"></i> My Consultations</div>
                        <div class="card-body">
                            <?php if (empty($appointments)): ?>
                                <div class="empty-state">
                                    <i class="fas fa-calendar"></i>
                                    <h4>No Consultations Yet</h4>
                                    <p>You haven't requested any consultations yet. <a class="nav-link-btn" data-section="consultation">Request one now</a></p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($appointments as $apt): ?>
                                    <div class="appointment-item">
                                        <h4><?php echo htmlspecialchars($apt['service_name'] ?? 'Service'); ?></h4>
                                        
                                        <!-- PATIENT INFO (Your name & email) -->
                                        <p><strong>Patient:</strong> <?php echo htmlspecialchars($display_name); ?> (<?php echo htmlspecialchars($email); ?>)</p>
                                        
                                        <!-- DOCTOR INFO (Shows when accepted) -->
                                        <?php if (!empty($apt['doctor_name'])): ?>
                                            <p><strong>Doctor:</strong> <?php echo htmlspecialchars($apt['doctor_name']); ?>
                                            <?php if (!empty($apt['doctor_specialization'])): ?>
                                                - <?php echo htmlspecialchars($apt['doctor_specialization']); ?>
                                            <?php endif; ?>
                                            </p>
                                            <?php if (!empty($apt['doctor_email'])): ?>
                                                <p><strong>Doctor Email:</strong> <?php echo htmlspecialchars($apt['doctor_email']); ?></p>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <p><strong>Doctor:</strong> <em>Not yet assigned</em></p>
                                        <?php endif; ?>
                                        
                                        <p><strong>Date:</strong>
                                            <?php
                                            if (!empty($apt['consultation_date'])) {
                                                echo date('F d, Y', strtotime($apt['consultation_date']));
                                                if (!empty($apt['consultation_time'])) {
                                                    echo ' @ ' . date('g:i A', strtotime($apt['consultation_time']));
                                                }
                                            } else {
                                                echo date('F d, Y', strtotime($apt['created_at']));
                                            }
                                            ?>
                                        </p>
                                        <p><strong>Notes:</strong> <?php echo htmlspecialchars($apt['notes'] ?? 'No notes'); ?></p>
                                        <span class="appointment-status status-<?php echo $apt['status']; ?>">
                                            <?php echo ucfirst($apt['status']); ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- UPCOMING CONSULTATIONS SECTION (NEW!) -->
                <div id="upcoming" class="section">
                    <div class="card-box">
                        <div class="card-header"><i class="fas fa-calendar-day"></i> Upcoming Consultations</div>
                        <div class="card-body">
                            <?php if (empty($upcoming)): ?>
                                <div class="empty-state">
                                    <i class="fas fa-calendar-day"></i>
                                    <h4>No Upcoming Consultations</h4>
                                    <p>You don't have any confirmed upcoming consultations.</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($upcoming as $apt): ?>
                                    <div class="appointment-item status-confirmed">
                                        <h4><?php echo htmlspecialchars($apt['service_name'] ?? 'Service'); ?></h4>
                                        
                                        <!-- Doctor Info -->
                                        <?php if (!empty($apt['doctor_name'])): ?>
                                            <p><strong>Doctor:</strong> <?php echo htmlspecialchars($apt['doctor_name']); ?>
                                            <?php if (!empty($apt['doctor_specialization'])): ?>
                                                - <?php echo htmlspecialchars($apt['doctor_specialization']); ?>
                                            <?php endif; ?>
                                            </p>
                                            <?php if (!empty($apt['doctor_email'])): ?>
                                                <p><strong>Contact:</strong> <?php echo htmlspecialchars($apt['doctor_email']); ?></p>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        
                                        <p><strong>Date:</strong>
                                            <?php
                                            echo date('F d, Y', strtotime($apt['consultation_date']));
                                            if (!empty($apt['consultation_time'])) {
                                                echo ' @ ' . date('g:i A', strtotime($apt['consultation_time']));
                                            }
                                            ?>
                                        </p>
                                        <p><strong>Notes:</strong> <?php echo htmlspecialchars($apt['notes'] ?? 'No notes'); ?></p>
                                        <span class="appointment-status status-confirmed">Confirmed</span>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- CONSULTATION REQUEST SECTION (Keep your existing form) -->
                <div id="consultation" class="section">
                    <div class="card-box">
                        <div class="card-header"><i class="fas fa-stethoscope"></i> Request Consultation</div>
                        <div class="card-body">
                            <form id="consultationForm" method="POST" action="process-consultation.php">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Service Type <span style="color: red;">*</span></label>
                                        <select name="service_id" class="form-control" required>
                                            <option value="">Select a service</option>
                                            <?php
                                            try {
                                                $stmt = $pdo->prepare("SELECT id, name FROM services ORDER BY name");
                                                $stmt->execute();
                                                $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($services as $service):
                                            ?>
                                                <option value="<?php echo $service['id']; ?>">
                                                    <?php echo htmlspecialchars($service['name']); ?>
                                                </option>
                                            <?php endforeach;
                                            } catch (PDOException $e) {
                                                echo '<option value="">Error loading services</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Preferred Date <span style="color: red;">*</span></label>
                                    <input type="date" name="consultation_date" class="form-control" min="<?= date('Y-m-d') ?>" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Preferred Time (Optional)</label>
                                    <input type="time" name="consultation_time" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Consultation Notes <span style="color: red;">*</span></label>
                                    <textarea name="notes" class="form-control" rows="5" placeholder="Describe your medical concern..." required></textarea>
                                </div>
                                <div style="display: flex; gap: 10px;">
                                    <button type="submit" class="btn-primary">Submit Request</button>
                                    <button type="reset" class="btn-secondary">Clear</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- PROFILE SECTION (Keep your existing profile form) -->
                <div id="profile" class="section">
                    <div class="card-box">
                        <div class="card-header"><i class="fas fa-user-circle"></i> My Profile</div>
                        <div class="card-body">
                            <form id="profileForm" method="POST" action="process-profile.php">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" name="full_name" class="form-control"
                                            value="<?php echo htmlspecialchars($patient['full_name'] ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" name="email" class="form-control"
                                            value="<?php echo htmlspecialchars($email); ?>" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Phone Number</label>
                                        <input type="tel" name="phone" class="form-control"
                                            value="<?php echo htmlspecialchars($patient['phone'] ?? ''); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Location</label>
                                        <input type="text" name="address" class="form-control"
                                            value="<?php echo htmlspecialchars($patient['address'] ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Blood Group</label>
                                        <select name="blood_group" class="form-control">
                                            <option value="">Select blood group</option>
                                            <?php
                                            $blood_groups = ['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'];
                                            foreach ($blood_groups as $bg):
                                                $selected = (isset($patient['blood_group']) && $patient['blood_group'] === $bg) ? 'selected' : '';
                                            ?>
                                                <option value="<?php echo $bg; ?>" <?php echo $selected; ?>>
                                                    <?php echo $bg; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Emergency Contact</label>
                                        <input type="tel" name="emergency_contact_phone" class="form-control"
                                            value="<?php echo htmlspecialchars($patient['emergency_contact_phone'] ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Medical History</label>
                                    <textarea name="medical_history" class="form-control"
                                        rows="4"><?php echo htmlspecialchars($patient['medical_history'] ?? ''); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Allergies</label>
                                    <textarea name="allergies" class="form-control"
                                        rows="3"><?php echo htmlspecialchars($patient['allergies'] ?? ''); ?></textarea>
                                </div>
                                <div style="display: flex; gap: 10px;">
                                    <button type="submit" class="btn-primary">Save Changes</button>
                                    <button type="button" class="btn-secondary" onclick="window.location.reload()">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/patient-dashboard.js"></script>
</body>
</html>
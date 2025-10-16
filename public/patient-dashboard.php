<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Fetch patient data with profile
    $stmt = $pdo->prepare("
        SELECT u.*, p.* 
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

    // Fetch all consultations for this patient
    $stmt = $pdo->prepare("
        SELECT c.*, s.service_name 
        FROM consultations c
        JOIN services s ON c.service_id = s.id
        WHERE c.patient_id = ?
        ORDER BY c.consultation_date DESC
    ");
    $stmt->execute([$user_id]);
    $appointments = $stmt->fetchAll();

    // Calculate statistics
    $totalAppointments = count($appointments);
    
    $completedCount = count(array_filter($appointments, function($a) {
        return $a['status'] === 'completed';
    }));
    
    $pendingCount = count(array_filter($appointments, function($a) {
        return in_array($a['status'], ['pending', 'confirmed']);
    }));

    // Get active services count
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM services WHERE status = 'active'");
    $stmt->execute();
    $servicesCount = $stmt->fetch()['count'];

} catch (PDOException $e) {
    error_log("Dashboard Error: " . $e->getMessage());
    die("Error loading dashboard");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard - Medicate</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/patient-dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-logo">
                <a href="index.php">
                    <img src="assets/images/footer_logo.png" alt="Medicate Logo">
                </a>
            </div>
            <ul class="sidebar-menu">
                <li><a class="nav-link active" data-section="dashboard"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                <li><a class="nav-link" data-section="appointments"><i class="fas fa-calendar-check"></i> My Appointments</a></li>
                <li><a class="nav-link" data-section="consultation"><i class="fas fa-stethoscope"></i> Request Consultation</a></li>
                <li><a class="nav-link" data-section="profile"><i class="fas fa-user-circle"></i> My Profile</a></li>
                <li><a href="faqs.php"><i class="fas fa-question-circle"></i> FAQs</a></li>
                <li><a href="contact.php"><i class="fas fa-phone"></i> Contact Us</a></li>
            </ul>

            <div class="sidebar-footer">
                <div class="user-info">
                    <p><strong><?php echo htmlspecialchars($patient['full_name'] ?? 'Patient'); ?></strong></p>
                    <p><?php echo htmlspecialchars($patient['email'] ?? ''); ?></p>
                </div>
                <form action="logout.php" method="POST" style="margin: 0;">
                    <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <div class="top-bar-left">
                    <h2 id="pageTitle">Dashboard</h2>
                </div>
                <div class="top-bar-right">
                    <div class="user-display">
                        <i class="fas fa-user-circle"></i>
                        <span><?php echo htmlspecialchars($patient['full_name'] ?? 'Patient'); ?></span>
                    </div>
                    <div class="time-display">
                        <i class="fas fa-clock"></i> <span id="currentTime">00:00</span>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Dashboard Section -->
                <div id="dashboard" class="section active">
                    <div class="welcome-card">
                        <h1>Welcome, <?php echo htmlspecialchars($patient['full_name'] ?? 'Patient'); ?>!</h1>
                        <p>Here's an overview of your health journey with us</p>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-card">
                            <i class="fas fa-calendar-check"></i>
                            <h3><?php echo $totalAppointments; ?></h3>
                            <p>Total Appointments</p>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-check-circle"></i>
                            <h3><?php echo $completedCount; ?></h3>
                            <p>Completed</p>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-clock"></i>
                            <h3><?php echo $pendingCount; ?></h3>
                            <p>Pending</p>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-hospital"></i>
                            <h3><?php echo $servicesCount; ?></h3>
                            <p>Services Available</p>
                        </div>
                    </div>

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

                <!-- Appointments Section -->
                <div id="appointments" class="section">
                    <div class="card-box">
                        <div class="card-header"><i class="fas fa-calendar-check"></i> My Appointments</div>
                        <div class="card-body">
                            <?php if (empty($appointments)): ?>
                                <div class="empty-state">
                                    <i class="fas fa-calendar"></i>
                                    <h4>No Appointments Yet</h4>
                                    <p>You haven't booked any consultations yet. <a class="nav-link-btn" data-section="consultation" style="color: #0076f5; cursor: pointer;">Request one now</a></p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($appointments as $apt): ?>
                                    <div class="appointment-item">
                                        <h4><?php echo htmlspecialchars($apt['service_name']); ?></h4>
                                        <p><strong>Date:</strong> <?php echo date('F d, Y', strtotime($apt['consultation_date'])); ?></p>
                                        <p><strong>Time:</strong> <?php echo date('h:i A', strtotime($apt['consultation_time'])); ?></p>
                                        <p><strong>Reason:</strong> <?php echo htmlspecialchars($apt['reason']); ?></p>
                                        <span class="appointment-status status-<?php echo $apt['status']; ?>">
                                            <?php echo ucfirst($apt['status']); ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Consultation Section -->
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
                                            <option value="1">Cardiology Services</option>
                                            <option value="2">Dental Services</option>
                                            <option value="3">Eye Care Services</option>
                                            <option value="4">Neurology Services</option>
                                            <option value="5">Orthopaedics Services</option>
                                            <option value="6">Endocrinology Services</option>
                                            <option value="7">RMI Services</option>
                                            <option value="8">Angioplasty Services</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Urgency <span style="color: red;">*</span></label>
                                        <select name="urgency" class="form-control" required>
                                            <option value="normal">Normal</option>
                                            <option value="urgent">Urgent</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Preferred Date <span style="color: red;">*</span></label>
                                        <input type="date" name="consultation_date" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Preferred Time <span style="color: red;">*</span></label>
                                        <input type="time" name="consultation_time" class="form-control" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Reason for Consultation <span style="color: red;">*</span></label>
                                    <textarea name="reason" class="form-control" rows="5" placeholder="Describe your medical concern..." required></textarea>
                                </div>

                                <div style="display: flex; gap: 10px;">
                                    <button type="submit" class="btn-primary">Submit Request</button>
                                    <button type="reset" class="btn-secondary">Clear</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Profile Section -->
                <div id="profile" class="section">
                    <div class="card-box">
                        <div class="card-header"><i class="fas fa-user-circle"></i> My Profile</div>
                        <div class="card-body">
                            <form id="profileForm" method="POST" action="process-profile.php">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($patient['full_name'] ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($patient['email'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Phone Number</label>
                                        <input type="tel" name="phone" class="form-control" value="<?php echo htmlspecialchars($patient['phone'] ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Location</label>
                                        <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($patient['address'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Blood Group</label>
                                        <select name="blood_group" class="form-control">
                                            <option value="">Select blood group</option>
                                            <option value="O+" <?php echo ($patient['blood_group'] ?? '') === 'O+' ? 'selected' : ''; ?>>O+</option>
                                            <option value="O-" <?php echo ($patient['blood_group'] ?? '') === 'O-' ? 'selected' : ''; ?>>O-</option>
                                            <option value="A+" <?php echo ($patient['blood_group'] ?? '') === 'A+' ? 'selected' : ''; ?>>A+</option>
                                            <option value="A-" <?php echo ($patient['blood_group'] ?? '') === 'A-' ? 'selected' : ''; ?>>A-</option>
                                            <option value="B+" <?php echo ($patient['blood_group'] ?? '') === 'B+' ? 'selected' : ''; ?>>B+</option>
                                            <option value="B-" <?php echo ($patient['blood_group'] ?? '') === 'B-' ? 'selected' : ''; ?>>B-</option>
                                            <option value="AB+" <?php echo ($patient['blood_group'] ?? '') === 'AB+' ? 'selected' : ''; ?>>AB+</option>
                                            <option value="AB-" <?php echo ($patient['blood_group'] ?? '') === 'AB-' ? 'selected' : ''; ?>>AB-</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Emergency Contact</label>
                                        <input type="tel" name="emergency_contact_phone" class="form-control" value="<?php echo htmlspecialchars($patient['emergency_contact_phone'] ?? ''); ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Medical History</label>
                                    <textarea name="medical_history" class="form-control" rows="4"><?php echo htmlspecialchars($patient['medical_history'] ?? ''); ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Allergies</label>
                                    <textarea name="allergies" class="form-control" rows="3"><?php echo htmlspecialchars($patient['allergies'] ?? ''); ?></textarea>
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
    <script src="assets/js/patient-dashboard.js"></script>
</body>
</html>
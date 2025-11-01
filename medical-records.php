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

// Only patients and doctors can access medical records
if ($user_type !== 'patient' && $user_type !== 'doctor') {
    header('Location: index.php');
    exit;
}

// For doctors, they can view patient records if specified
$viewing_patient_id = $user_id;
if ($user_type === 'doctor' && isset($_GET['patient'])) {
    $viewing_patient_id = (int)$_GET['patient'];
    // Verify doctor has access to this patient (has consultation with them)
    $access_check = $pdo->prepare("
        SELECT COUNT(*) FROM consultations 
        WHERE doctor_id = ? AND patient_id = ?
    ");
    $access_check->execute([$user_id, $viewing_patient_id]);
    if ($access_check->fetchColumn() == 0) {
        $_SESSION['error'] = 'You do not have access to this patient\'s records.';
        header('Location: doctor-dashboard.php');
        exit;
    }
}

// Get patient profile
$stmt = $pdo->prepare("SELECT pp.*, u.email FROM patient_profiles pp JOIN users u ON pp.user_id = u.id WHERE pp.user_id = ?");
$stmt->execute([$viewing_patient_id]);
$patient_profile = $stmt->fetch(PDO::FETCH_ASSOC);

// Get medical documents
$all_documents = getPatientDocuments($pdo, $viewing_patient_id);
$lab_results = getPatientDocuments($pdo, $viewing_patient_id, 'lab_result');
$imaging = getPatientDocuments($pdo, $viewing_patient_id, 'imaging');
$prescriptions_docs = getPatientDocuments($pdo, $viewing_patient_id, 'prescription');
$other_docs = getPatientDocuments($pdo, $viewing_patient_id, 'other');

// Get health vitals
$health_vitals = getHealthVitals($pdo, $viewing_patient_id);

// Process upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['document'])) {
    $document_type = $_POST['document_type'] ?? 'other';
    $description = $_POST['description'] ?? '';
    
    // Validate file
    if ($_FILES['document']['error'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES['document']['name'];
        
        if (!isAllowedFileType($file_name)) {
            $_SESSION['error'] = 'File type not allowed. Please upload PDF, JPG, PNG, DOC, DOCX, or TXT files.';
        } else if ($_FILES['document']['size'] > 10 * 1024 * 1024) { // 10MB limit
            $_SESSION['error'] = 'File size must be less than 10MB.';
        } else {
            $upload_result = uploadMedicalDocument(
                $pdo,
                $viewing_patient_id,
                $user_id,
                $_FILES['document'],
                $document_type,
                $description
            );
            
            if ($upload_result) {
                $_SESSION['success'] = 'Document uploaded successfully!';
                header('Location: medical-records.php');
                exit;
            } else {
                $_SESSION['error'] = 'Failed to upload document. Please try again.';
            }
        }
    } else {
        $_SESSION['error'] = 'Error uploading file. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Medical Records – Medicate</title>
    
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    
    <style>
        .records-container {
            padding: 40px 0;
            min-height: 70vh;
        }
        .patient-info-card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #666;
        }
        .info-value {
            color: #333;
        }
        .upload-section {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }
        .document-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .document-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }
        .document-icon {
            font-size: 2.5rem;
            margin-right: 20px;
            color: #2490eb;
        }
        .document-icon.pdf {
            color: #dc3545;
        }
        .document-icon.image {
            color: #28a745;
        }
        .document-icon.word {
            color: #007bff;
        }
        .document-info {
            flex: 1;
        }
        .document-title {
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }
        .document-meta {
            font-size: 0.85rem;
            color: #666;
        }
        .document-actions {
            display: flex;
            gap: 10px;
        }
        .btn-download, .btn-delete, .btn-share {
            padding: 8px 15px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        .btn-download {
            background: #2490eb;
            color: white;
        }
        .btn-download:hover {
            background: #1a7ac9;
            color: white;
        }
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        .btn-delete:hover {
            background: #c82333;
        }
        .btn-share {
            background: #28a745;
            color: white;
        }
        .btn-share:hover {
            background: #218838;
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
        .tab-nav {
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
        }
        .tab-btn.active {
            background: #2490eb;
            color: white;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .doc-type-badge {
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .doc-type-badge.lab_result {
            background: #e3f2fd;
            color: #1976d2;
        }
        .doc-type-badge.imaging {
            background: #f3e5f5;
            color: #7b1fa2;
        }
        .doc-type-badge.prescription {
            background: #e8f5e9;
            color: #388e3c;
        }
        .doc-type-badge.other {
            background: #fff3e0;
            color: #f57c00;
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
                            <h2>Medical Records</h2>
                        </div>
                        <div class="pq-breadcrumb-container mt-2">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home mr-2"></i>Home</a></li>
                                <li class="breadcrumb-item"><a href="<?php echo $user_type; ?>-dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Medical Records</li>
                            </ol>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="records-container">
        <div class="container">
            <!-- Messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php endif; ?>

            <!-- Patient Information Card -->
            <div class="patient-info-card">
                <h4 class="mb-4">
                    <i class="fas fa-user-injured"></i> Patient Information
                </h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-row">
                            <span class="info-label">Full Name:</span>
                            <span class="info-value"><?php echo htmlspecialchars($patient_profile['full_name']); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Email:</span>
                            <span class="info-value"><?php echo htmlspecialchars($patient_profile['email']); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Blood Group:</span>
                            <span class="info-value"><?php echo htmlspecialchars($patient_profile['blood_group'] ?? 'Not specified'); ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <span class="info-label">Phone:</span>
                            <span class="info-value"><?php echo htmlspecialchars($patient_profile['phone'] ?? 'Not specified'); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Allergies:</span>
                            <span class="info-value"><?php echo htmlspecialchars($patient_profile['allergies'] ?? 'None'); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Total Documents:</span>
                            <span class="info-value"><?php echo count($all_documents); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload Section (only for patients) -->
            <?php if ($user_type === 'patient' && $viewing_patient_id === $user_id): ?>
                <div class="upload-section">
                    <h4 class="mb-4">
                        <i class="fas fa-cloud-upload-alt"></i> Upload Medical Document
                    </h4>
                    <form method="POST" enctype="multipart/form-data" id="upload-form">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="document_type">Document Type *</label>
                                <select name="document_type" id="document_type" class="form-control" required>
                                    <option value="lab_result">Lab Result</option>
                                    <option value="imaging">Imaging (X-Ray, MRI, CT Scan)</option>
                                    <option value="prescription">Prescription</option>
                                    <option value="medical_report">Medical Report</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="document">Choose File * (Max 10MB)</label>
                                <input type="file" name="document" id="document" class="form-control" required 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.txt">
                                <small class="text-muted">Supported: PDF, JPG, PNG, DOC, DOCX, TXT</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="description">Description (Optional)</label>
                                <textarea name="description" id="description" class="form-control" rows="3" 
                                          placeholder="Add notes about this document..."></textarea>
                            </div>
                        </div>
                        <button type="submit" class="pq-button pq-button-flat">
                            <div class="pq-button-block">
                                <span class="pq-button-text">Upload Document</span>
                                <i class="ion ion-plus-round"></i>
                            </div>
                        </button>
                    </form>
                </div>
            <?php endif; ?>

            <!-- Tab Navigation -->
            <div class="tab-nav">
                <button class="tab-btn active" onclick="switchTab('all')">
                    <i class="fas fa-list"></i> All Documents (<?php echo count($all_documents); ?>)
                </button>
                <button class="tab-btn" onclick="switchTab('lab')">
                    <i class="fas fa-flask"></i> Lab Results (<?php echo count($lab_results); ?>)
                </button>
                <button class="tab-btn" onclick="switchTab('imaging')">
                    <i class="fas fa-x-ray"></i> Imaging (<?php echo count($imaging); ?>)
                </button>
                <button class="tab-btn" onclick="switchTab('prescriptions')">
                    <i class="fas fa-prescription"></i> Prescriptions (<?php echo count($prescriptions_docs); ?>)
                </button>
                <button class="tab-btn" onclick="switchTab('other')">
                    <i class="fas fa-file"></i> Other (<?php echo count($other_docs); ?>)
                </button>
            </div>

            <!-- All Documents Tab -->
            <div id="all-tab" class="tab-content active">
                <h4 class="mb-4">All Medical Documents</h4>
                <?php if (empty($all_documents)): ?>
                    <div class="empty-state">
                        <i class="fas fa-folder-open"></i>
                        <h5>No Documents</h5>
                        <p>No medical documents have been uploaded yet.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($all_documents as $doc): ?>
                        <?php include 'includes/document-card.php'; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Lab Results Tab -->
            <div id="lab-tab" class="tab-content">
                <h4 class="mb-4">Laboratory Results</h4>
                <?php if (empty($lab_results)): ?>
                    <div class="empty-state">
                        <i class="fas fa-flask"></i>
                        <h5>No Lab Results</h5>
                        <p>No laboratory results have been uploaded.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($lab_results as $doc): ?>
                        <?php include 'includes/document-card.php'; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Imaging Tab -->
            <div id="imaging-tab" class="tab-content">
                <h4 class="mb-4">Medical Imaging</h4>
                <?php if (empty($imaging)): ?>
                    <div class="empty-state">
                        <i class="fas fa-x-ray"></i>
                        <h5>No Imaging Files</h5>
                        <p>No imaging files have been uploaded.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($imaging as $doc): ?>
                        <?php include 'includes/document-card.php'; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Prescriptions Tab -->
            <div id="prescriptions-tab" class="tab-content">
                <h4 class="mb-4">Prescription Documents</h4>
                <?php if (empty($prescriptions_docs)): ?>
                    <div class="empty-state">
                        <i class="fas fa-prescription"></i>
                        <h5>No Prescriptions</h5>
                        <p>No prescription documents have been uploaded.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($prescriptions_docs as $doc): ?>
                        <?php include 'includes/document-card.php'; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Other Documents Tab -->
            <div id="other-tab" class="tab-content">
                <h4 class="mb-4">Other Documents</h4>
                <?php if (empty($other_docs)): ?>
                    <div class="empty-state">
                        <i class="fas fa-file"></i>
                        <h5>No Other Documents</h5>
                        <p>No other documents have been uploaded.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($other_docs as $doc): ?>
                        <?php include 'includes/document-card.php'; ?>
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
        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.getElementById(tabName + '-tab').classList.add('active');
            event.target.closest('.tab-btn').classList.add('active');
        }

        function deleteDocument(docId) {
            if (confirm('Are you sure you want to delete this document? This action cannot be undone.')) {
                window.location.href = `ajax/delete-document.php?id=${docId}`;
            }
        }

        // File size validation
        document.getElementById('document')?.addEventListener('change', function() {
            const file = this.files[0];
            if (file && file.size > 10 * 1024 * 1024) {
                alert('File size must be less than 10MB');
                this.value = '';
            }
        });
    </script>
</body>
</html>

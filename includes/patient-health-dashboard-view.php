<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Health Dashboard – Medicate</title>
    
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --primary-color: #2490eb;
            --primary-dark-color: #14457b;
            --dark-color: #18100f;
            --secondary-color: #666666;
            --grey-color: #f4f6f9;
        }
        .health-dashboard { padding: 40px 0; background: var(--grey-color); }
        .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .health-card { background: white; border-radius: 10px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: all 0.3s; }
        .health-card:hover { transform: translateY(-5px); box-shadow: 0 5px 20px rgba(0,0,0,0.15); }
        .health-card-icon { width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 15px; }
        .icon-bp { background: #e3f2fd; color: #2196f3; }
        .icon-weight { background: #f3e5f5; color: #9c27b0; }
        .icon-glucose { background: #e8f5e9; color: #4caf50; }
        .icon-bmi { background: #e0f2f1; color: #009688; }
        .health-value { font-size: 2rem; font-weight: bold; color: var(--dark-color); margin: 10px 0; }
        .health-label { color: var(--secondary-color); font-size: 0.9rem; text-transform: uppercase; }
        .section-card { background: white; border-radius: 10px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 30px; }
        .medication-item { border-left: 4px solid var(--primary-color); padding: 15px; margin-bottom: 15px; background: var(--grey-color); border-radius: 5px; }
        .quick-action-btn { background: var(--primary-color); color: white; padding: 10px 20px; border-radius: 5px; border: none; cursor: pointer; transition: all 0.3s; text-decoration: none; display: inline-block; }
        .quick-action-btn:hover { background: var(--primary-dark-color); color: white; }
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
                        <div class="pq-breadcrumb-title"><h2>Health Dashboard</h2></div>
                        <div class="pq-breadcrumb-container mt-2">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home mr-2"></i>Home</a></li>
                                <li class="breadcrumb-item"><a href="patient-dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Health Dashboard</li>
                            </ol>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="health-dashboard">
        <div class="container">
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php endif; ?>

            <!-- Quick Actions -->
            <div class="mb-4" style="text-align: right;">
                <a href="medical-records.php" class="quick-action-btn">
                    <i class="fas fa-folder-open"></i> Medical Records
                </a>
                <a href="appointments-dashboard.php" class="quick-action-btn">
                    <i class="fas fa-calendar"></i> Appointments
                </a>
            </div>

            <!-- Health Vitals Cards -->
            <div class="dashboard-grid">
                <!-- Blood Pressure -->
                <div class="health-card">
                    <div class="health-card-icon icon-bp"><i class="fas fa-heartbeat"></i></div>
                    <div class="health-label">Blood Pressure</div>
                    <div class="health-value">
                        <?php echo isset($latest_vitals['blood_pressure']) ? 
                            $latest_vitals['blood_pressure']['systolic'] . '/' . $latest_vitals['blood_pressure']['diastolic'] : 
                            '--/--'; ?>
                    </div>
                </div>

                <!-- Weight -->
                <div class="health-card">
                    <div class="health-card-icon icon-weight"><i class="fas fa-weight"></i></div>
                    <div class="health-label">Weight</div>
                    <div class="health-value">
                        <?php echo isset($latest_vitals['weight']) ? 
                            $latest_vitals['weight']['value'] . ' kg' : 
                            ($patient['weight'] ?? '--') . ' kg'; ?>
                    </div>
                </div>

                <!-- Blood Glucose -->
                <div class="health-card">
                    <div class="health-card-icon icon-glucose"><i class="fas fa-tint"></i></div>
                    <div class="health-label">Blood Glucose</div>
                    <div class="health-value">
                        <?php echo isset($latest_vitals['glucose']) ? $latest_vitals['glucose']['value'] . ' mg/dL' : '--'; ?>
                    </div>
                </div>

                <!-- BMI -->
                <?php if ($bmi): ?>
                <div class="health-card">
                    <div class="health-card-icon icon-bmi"><i class="fas fa-balance-scale"></i></div>
                    <div class="health-label">BMI</div>
                    <div class="health-value"><?php echo $bmi; ?></div>
                    <small><?php echo $bmi_category; ?></small>
                </div>
                <?php endif; ?>
            </div>

            <!-- Medications Section -->
            <div class="section-card">
                <h5 class="mb-4"><i class="fas fa-pills"></i> Active Medications</h5>
                <?php if (empty($medications)): ?>
                    <p style="text-align:center; color:#999;">No active medications</p>
                <?php else: ?>
                    <?php foreach ($medications as $med): ?>
                        <div class="medication-item">
                            <strong><?php echo htmlspecialchars($med['medication_name']); ?></strong><br>
                            <small>Dosage: <?php echo htmlspecialchars($med['dosage']); ?> | Frequency: <?php echo htmlspecialchars($med['frequency']); ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Upcoming Appointments -->
            <div class="section-card">
                <h5 class="mb-4"><i class="far fa-calendar-check"></i> Upcoming Appointments</h5>
                <?php if (empty($upcoming)): ?>
                    <p style="text-align:center; color:#999;">No upcoming appointments</p>
                <?php else: ?>
                    <?php foreach ($upcoming as $appt): ?>
                        <div class="medication-item">
                            <strong>Dr. <?php echo htmlspecialchars($appt['doctor_name']); ?></strong><br>
                            <small><?php echo formatDate($appt['consultation_date']); ?> at <?php echo formatTime($appt['consultation_time']); ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
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
                            <span class="pq-copyright">Copyright 2025 Medicate All Rights Reserved</span>
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

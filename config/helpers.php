<?php
// config/helpers.php

function getGetStartedUrl() {
    if (!isset($_SESSION['user_id'])) {
        // Not logged in → go to register
        return 'register.php';
    }
    
    $userType = $_SESSION['user_type'] ?? 'patient';
    if ($userType === 'patient') {
        return 'patient-dashboard.php';
    } else {
        return 'doctor-dashboard.php';
    }
}
?>
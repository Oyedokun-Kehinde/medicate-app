<?php
/**
 * Helper functions for Medicate
 */

function getGetStartedUrl() {
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Check if user is logged in
    $isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    
    // Detect if current page is in /services subdirectory
    $currentPath = $_SERVER['PHP_SELF'];
    $isInServicesDir = (strpos($currentPath, '/services/') !== false);
    
    // Build the correct URL based on location and login status
    if ($isLoggedIn) {
        // User IS logged in - determine which dashboard based on user type
        $userType = $_SESSION['user_type'] ?? 'patient'; // Default to patient if not set
        
        if ($userType === 'doctor') {
            $dashboard = 'doctor-dashboard.php';
        } else {
            $dashboard = 'patient-dashboard.php';
        }
        
        return $isInServicesDir ? '../' . $dashboard : $dashboard;
    } else {
        // User NOT logged in - send to login
        return $isInServicesDir ? '../login.php' : 'login.php';
    }
}

function getGetStartedButtonText() {
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Check if user is logged in
    $isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    
    if ($isLoggedIn) {
        // User IS logged in - show different text based on user type
        $userType = $_SESSION['user_type'] ?? 'patient';
        
        if ($userType === 'doctor') {
            return 'Go To Dashboard';
        } else {
            return 'My Account';
        }
    } else {
        // User NOT logged in
        return 'Get Started';
    }
}
?>
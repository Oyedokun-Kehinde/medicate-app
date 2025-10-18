<?php
// config/session.php

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Prevent logged-in users from accessing login/register pages
function preventLogin() {
    if (isLoggedIn()) {
        // User is already logged in, redirect to dashboard
        $userType = $_SESSION['user_type'] ?? 'patient';
        if ($userType === 'doctor') {
            header('Location: doctor/dashboard.php');
        } else {
            header('Location: patient/dashboard.php');
        }
        exit();
    }
}

// Require authentication for protected pages
function requireAuth() {
    if (!isLoggedIn()) {
        // User is not logged in, redirect to login
        header('Location: ../login.php');
        exit();
    }
}

// Check if user is a specific type
function requireUserType($type) {
    requireAuth();
    if ($_SESSION['user_type'] !== $type) {
        // Wrong user type, redirect appropriately
        if ($_SESSION['user_type'] === 'doctor') {
            header('Location: ../doctor/dashboard.php');
        } else {
            header('Location: ../patient/dashboard.php');
        }
        exit();
    }
}

// Get current user ID
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Get current user type
function getUserType() {
    return $_SESSION['user_type'] ?? null;
}

// Logout function
function logout() {
    session_start();
    session_unset();
    session_destroy();
    header('Location: ../login.php');
    exit();
}
?>
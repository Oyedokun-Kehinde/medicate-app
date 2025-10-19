<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: doctor-dashboard.php?error=' . urlencode('Invalid request.'));
    exit;
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    header('Location: login.php');
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$error = null;
$success = null;

try {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $specialization = trim($_POST['specialization'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    
    if (empty($full_name)) {
        throw new Exception('Full name is required.');
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Valid email is required.');
    }
    
    // Check if email is already taken by another user
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->execute([$email, $user_id]);
    if ($stmt->fetch()) {
        throw new Exception('Email already in use.');
    }
    
    // Update users table
    $stmt = $pdo->prepare("UPDATE users SET email = ? WHERE id = ?");
    $stmt->execute([$email, $user_id]);
    
    // Update doctor_profiles with proper error handling
    $stmt = $pdo->prepare("
        INSERT INTO doctor_profiles (user_id, full_name, specialization, phone, bio)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            full_name = VALUES(full_name),
            specialization = VALUES(specialization),
            phone = VALUES(phone),
            bio = VALUES(bio)
    ");
    $stmt->execute([$user_id, $full_name, $specialization, $phone, $bio]);
    
    $success = 'Profile updated successfully!';
    
} catch (Exception $e) {
    $error = $e->getMessage();
} catch (PDOException $e) {
    error_log("Doctor Profile Update Error: " . $e->getMessage());
    $error = 'Database error. Please try again.';
}

if ($success) {
    header("Location: doctor-dashboard.php?msg=" . urlencode($success));
} else {
    header("Location: doctor-dashboard.php?error=" . urlencode($error));
}
exit;
?>
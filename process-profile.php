<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: patient-dashboard.php?error=' . urlencode('Invalid request method.'));
    exit;
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    header('Location: login.php');
    exit;
}

$user_id = (int)$_SESSION['user_id'];

try {
    // Get form data
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $blood_group = trim($_POST['blood_group'] ?? '');
    $emergency_contact_phone = trim($_POST['emergency_contact_phone'] ?? '');
    $medical_history = trim($_POST['medical_history'] ?? '');
    $allergies = trim($_POST['allergies'] ?? '');

    // Validation
    if (empty($full_name)) {
        throw new Exception('Full name is required.');
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Valid email is required.');
    }

    // Check email uniqueness
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->execute([$email, $user_id]);
    if ($stmt->fetch()) {
        throw new Exception('This email is already in use by another account.');
    }

    // Start transaction
    $pdo->beginTransaction();

    // Update users table
    $stmt = $pdo->prepare("UPDATE users SET email = ? WHERE id = ?");
    $stmt->execute([$email, $user_id]);

    // Check if patient_profiles record exists
    $stmt = $pdo->prepare("SELECT user_id FROM patient_profiles WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $exists = $stmt->fetch();

    if ($exists) {
        // UPDATE existing record
        $stmt = $pdo->prepare("
            UPDATE patient_profiles SET
                full_name = ?,
                phone = ?,
                address = ?,
                blood_group = ?,
                emergency_contact_phone = ?,
                medical_history = ?,
                allergies = ?
            WHERE user_id = ?
        ");
        $stmt->execute([
            $full_name,
            !empty($phone) ? $phone : null,
            !empty($address) ? $address : null,
            !empty($blood_group) ? $blood_group : null,
            !empty($emergency_contact_phone) ? $emergency_contact_phone : null,
            !empty($medical_history) ? $medical_history : null,
            !empty($allergies) ? $allergies : null,
            $user_id
        ]);
    } else {
        // INSERT new record
        $stmt = $pdo->prepare("
            INSERT INTO patient_profiles (
                user_id, full_name, phone, address, blood_group,
                emergency_contact_phone, medical_history, allergies
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $user_id,
            $full_name,
            !empty($phone) ? $phone : null,
            !empty($address) ? $address : null,
            !empty($blood_group) ? $blood_group : null,
            !empty($emergency_contact_phone) ? $emergency_contact_phone : null,
            !empty($medical_history) ? $medical_history : null,
            !empty($allergies) ? $allergies : null
        ]);
    }

    // Commit transaction
    $pdo->commit();

    header("Location: patient-dashboard.php?msg=" . urlencode('Profile updated successfully!'));
    exit;

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    header("Location: patient-dashboard.php?error=" . urlencode($e->getMessage()));
    exit;
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Profile Update Error: " . $e->getMessage());
    header("Location: patient-dashboard.php?error=" . urlencode('Database error. Please try again later.'));
    exit;
}
?>
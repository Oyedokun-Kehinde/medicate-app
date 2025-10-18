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

$consultation_id = (int)($_POST['consultation_id'] ?? 0);
$status = $_POST['status'] ?? '';
$doctor_id = (int)($_POST['doctor_id'] ?? $_SESSION['user_id']);
$allowed_statuses = ['confirmed', 'completed', 'cancelled'];

if (!$consultation_id || !in_array($status, $allowed_statuses)) {
    header('Location: doctor-dashboard.php?error=' . urlencode('Invalid consultation update.'));
    exit;
}

try {
    // Prepare the update query
    if ($status === 'confirmed') {
        // When confirming, assign the doctor
        $stmt = $pdo->prepare("UPDATE consultations SET status = ?, doctor_id = ? WHERE id = ?");
        $stmt->execute([$status, $doctor_id, $consultation_id]);
        $success = 'Consultation accepted successfully!';
    } else {
        // For completed or cancelled, just update status
        $stmt = $pdo->prepare("UPDATE consultations SET status = ? WHERE id = ?");
        $stmt->execute([$status, $consultation_id]);
        
        if ($status === 'completed') {
            $success = 'Consultation marked as completed!';
        } else {
            $success = 'Consultation cancelled.';
        }
    }

    header("Location: doctor-dashboard.php?msg=" . urlencode($success));

} catch (PDOException $e) {
    error_log("Consultation Update Error: " . $e->getMessage());
    header("Location: doctor-dashboard.php?error=" . urlencode('Failed to update consultation.'));
}
exit;
?>
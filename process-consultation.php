<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: patient-dashboard.php?error=' . urlencode('Invalid request.'));
    exit;
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    header('Location: login.php');
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$error = null;
$success = null;

try {
    // Fetch FULL patient data (including phone)
    $stmt = $pdo->prepare("
        SELECT u.email, p.full_name, p.phone 
        FROM users u 
        LEFT JOIN patient_profiles p ON u.id = p.user_id 
        WHERE u.id = ?
    ");
    $stmt->execute([$user_id]);
    $patient = $stmt->fetch();

    if (!$patient) {
        throw new Exception('Patient profile not found.');
    }

    $service_id = $_POST['service_id'] ?? null;
    $consultation_date = $_POST['consultation_date'] ?? null;
    $consultation_time = $_POST['consultation_time'] ?? null;
    $notes = trim($_POST['notes'] ?? '');

    if (empty($service_id)) throw new Exception('Please select a service.');
    if (empty($consultation_date)) throw new Exception('Please select a consultation date.');
    if (strtotime($consultation_date) < strtotime('today')) throw new Exception('Date must be today or later.');
    if (empty($notes)) throw new Exception('Consultation notes are required.');

    $stmt = $pdo->prepare("SELECT id FROM services WHERE id = ?");
    $stmt->execute([$service_id]);
    if (!$stmt->fetch()) throw new Exception('Invalid service selected.');

    // Insert with FULL patient info
    $stmt = $pdo->prepare("
        INSERT INTO consultations (
            patient_id, 
            patient_name, 
            patient_email,
            patient_phone,
            service_id, 
            consultation_date, 
            consultation_time, 
            notes, 
            status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')
    ");
    $stmt->execute([
        $user_id,
        $patient['full_name'] ?: 'Not provided',
        $patient['email'],
        $patient['phone'] ?: null,
        $service_id,
        $consultation_date,
        !empty($consultation_time) ? $consultation_time : null,
        $notes
    ]);

    $success = 'Consultation request submitted successfully! A doctor will be assigned shortly.';

} catch (Exception $e) {
    $error = $e->getMessage();
} catch (PDOException $e) {
    $error = 'Submission failed. Please try again.';
    error_log("Consultation Error: " . $e->getMessage());
}

if ($success) {
    header("Location: patient-dashboard.php?msg=" . urlencode($success));
} else {
    header("Location: patient-dashboard.php?error=" . urlencode($error));
}
exit;
?>
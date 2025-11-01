<?php
session_start();
require_once 'config/database.php';
require_once 'config/helpers.php';
require_once 'includes/functions.php';

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$getStartedUrl = getGetStartedUrl();

// Get patient profile
$stmt = $pdo->prepare("SELECT pp.*, u.email FROM patient_profiles pp JOIN users u ON pp.user_id = u.id WHERE pp.user_id = ?");
$stmt->execute([$user_id]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);

// Get health vitals
$vitals = getHealthVitals($pdo, $user_id, null, 30);

// Get latest vitals by type
$latest_vitals = [];
foreach (['blood_pressure', 'weight', 'glucose', 'temperature', 'heart_rate'] as $type) {
    $latest = getHealthVitals($pdo, $user_id, $type, 1);
    if (!empty($latest)) {
        $latest_vitals[$type] = $latest[0];
    }
}

// Get active medications
$meds_stmt = $pdo->prepare("
    SELECT m.*, 
    CASE 
        WHEN u.user_type = 'doctor' THEN dp.full_name
        ELSE 'Self-added'
    END as prescriber_name
    FROM medications m
    LEFT JOIN users u ON m.prescribed_by = u.id
    LEFT JOIN doctor_profiles dp ON u.id = dp.user_id
    WHERE m.patient_id = ? AND m.is_active = TRUE
    ORDER BY m.created_at DESC
");
$meds_stmt->execute([$user_id]);
$medications = $meds_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get upcoming appointments
$upcoming = getUpcomingAppointments($pdo, $user_id, 'patient', 3);

// Calculate BMI if height and weight available
$bmi = null;
$bmi_category = '';
if (!empty($patient['height']) && !empty($patient['weight'])) {
    $height_m = $patient['height'] / 100;
    $bmi = round($patient['weight'] / ($height_m * $height_m), 1);
    
    if ($bmi < 18.5) $bmi_category = 'Underweight';
    elseif ($bmi < 25) $bmi_category = 'Normal';
    elseif ($bmi < 30) $bmi_category = 'Overweight';
    else $bmi_category = 'Obese';
}

// Handle add vital form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_vital'])) {
    $vital_type = $_POST['vital_type'];
    $value = $_POST['value'];
    $systolic = isset($_POST['systolic']) ? $_POST['systolic'] : null;
    $diastolic = isset($_POST['diastolic']) ? $_POST['diastolic'] : null;
    $unit = $_POST['unit'];
    $notes = $_POST['notes'] ?? '';
    
    $result = addHealthVital($pdo, $user_id, $vital_type, $value, $unit, $systolic, $diastolic, $notes, $user_id);
    
    if ($result) {
        $_SESSION['success'] = 'Health vital recorded successfully!';
        header('Location: patient-health-dashboard.php');
        exit;
    } else {
        $_SESSION['error'] = 'Failed to record vital. Please try again.';
    }
}

include 'includes/patient-health-dashboard-view.php';

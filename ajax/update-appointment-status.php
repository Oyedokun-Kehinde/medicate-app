<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

// Get parameters
$consultation_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($consultation_id <= 0 || empty($action)) {
    $_SESSION['error'] = 'Invalid request parameters.';
    header('Location: ../appointments-dashboard.php');
    exit;
}

// Map actions to statuses
$action_to_status = [
    'confirm' => 'confirmed',
    'cancel' => 'cancelled',
    'complete' => 'completed',
    'reschedule' => 'pending'
];

if (!isset($action_to_status[$action])) {
    $_SESSION['error'] = 'Invalid action.';
    header('Location: ../appointments-dashboard.php');
    exit;
}

$new_status = $action_to_status[$action];

// Get consultation details
$consultation = getConsultationById($pdo, $consultation_id);

if (!$consultation) {
    $_SESSION['error'] = 'Appointment not found.';
    header('Location: ../appointments-dashboard.php');
    exit;
}

// Verify user has permission
if ($user_type === 'patient' && $consultation['patient_id'] != $user_id) {
    $_SESSION['error'] = 'You do not have permission to modify this appointment.';
    header('Location: ../appointments-dashboard.php');
    exit;
}

if ($user_type === 'doctor' && $consultation['doctor_id'] != $user_id) {
    $_SESSION['error'] = 'You do not have permission to modify this appointment.';
    header('Location: ../appointments-dashboard.php');
    exit;
}

// Business logic validations
if ($action === 'confirm' && $user_type !== 'doctor') {
    $_SESSION['error'] = 'Only doctors can confirm appointments.';
    header('Location: ../appointments-dashboard.php');
    exit;
}

if ($action === 'complete' && $user_type !== 'doctor') {
    $_SESSION['error'] = 'Only doctors can mark appointments as complete.';
    header('Location: ../appointments-dashboard.php');
    exit;
}

// Update the consultation status
try {
    $stmt = $pdo->prepare("UPDATE consultations SET status = ?, updated_at = NOW() WHERE id = ?");
    $result = $stmt->execute([$new_status, $consultation_id]);
    
    if ($result) {
        // Create notifications
        if ($action === 'confirm' && $user_type === 'doctor') {
            // Notify patient
            createNotification(
                $pdo,
                $consultation['patient_id'],
                'appointment',
                'Appointment Confirmed',
                'Your appointment with Dr. ' . $consultation['doctor_name'] . ' on ' . 
                formatDate($consultation['consultation_date']) . ' has been confirmed.',
                'appointments-dashboard.php'
            );
            
            // Create reminder for 24 hours before
            $consultation_datetime = $consultation['consultation_date'] . ' ' . $consultation['consultation_time'];
            $reminder_time = date('Y-m-d H:i:s', strtotime($consultation_datetime . ' -24 hours'));
            
            $reminder_stmt = $pdo->prepare("
                INSERT INTO appointment_reminders (consultation_id, reminder_type, scheduled_time)
                VALUES (?, 'notification', ?)
            ");
            $reminder_stmt->execute([$consultation_id, $reminder_time]);
        }
        
        if ($action === 'cancel') {
            // Notify the other party
            $notify_user_id = ($user_type === 'patient') ? $consultation['doctor_id'] : $consultation['patient_id'];
            $canceller_name = ($user_type === 'patient') ? $consultation['patient_name'] : $consultation['doctor_name'];
            
            createNotification(
                $pdo,
                $notify_user_id,
                'appointment',
                'Appointment Cancelled',
                'The appointment on ' . formatDate($consultation['consultation_date']) . ' has been cancelled by ' . $canceller_name . '.',
                'appointments-dashboard.php'
            );
        }
        
        if ($action === 'complete') {
            // Notify patient
            createNotification(
                $pdo,
                $consultation['patient_id'],
                'consultation',
                'Consultation Completed',
                'Your consultation with Dr. ' . $consultation['doctor_name'] . ' has been completed. Please rate your experience.',
                'rate-doctor.php?consultation=' . $consultation_id . '&doctor=' . $consultation['doctor_id']
            );
        }
        
        $_SESSION['success'] = ucfirst($action) . ' successful! Appointment status updated.';
    } else {
        $_SESSION['error'] = 'Failed to update appointment status.';
    }
    
} catch (PDOException $e) {
    error_log("Update appointment error: " . $e->getMessage());
    $_SESSION['error'] = 'An error occurred while updating the appointment.';
}

header('Location: ../appointments-dashboard.php');
exit;

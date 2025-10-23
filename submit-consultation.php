<?php
session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Function to validate email
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to validate phone
function validate_phone($phone) {
    // Remove all non-numeric characters
    $phone = preg_replace('/[^0-9]/', '', $phone);
    // Check if phone is between 10-15 digits
    return strlen($phone) >= 10 && strlen($phone) <= 15;
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
    exit;
}

try {
    // Get and sanitize input data
    $full_name = sanitize_input($_POST['full_name'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $phone = sanitize_input($_POST['phone'] ?? '');
    $service_id = intval($_POST['service_id'] ?? 0);
    $doctor_id = !empty($_POST['doctor_id']) ? intval($_POST['doctor_id']) : null;
    $consultation_date = sanitize_input($_POST['consultation_date'] ?? '');
    $consultation_time = sanitize_input($_POST['consultation_time'] ?? '');
    $message = sanitize_input($_POST['message'] ?? '');
    
    // Validation
    $errors = [];
    
    if (empty($full_name)) {
        $errors[] = 'Full name is required.';
    } elseif (strlen($full_name) < 3) {
        $errors[] = 'Full name must be at least 3 characters.';
    }
    
    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!validate_email($email)) {
        $errors[] = 'Please provide a valid email address.';
    }
    
    if (empty($phone)) {
        $errors[] = 'Phone number is required.';
    } elseif (!validate_phone($phone)) {
        $errors[] = 'Please provide a valid phone number.';
    }
    
    if ($service_id <= 0) {
        $errors[] = 'Please select a service/department.';
    }
    
    if (empty($consultation_date)) {
        $errors[] = 'Preferred consultation date is required.';
    } else {
        // Validate date format and ensure it's not in the past
        $date = DateTime::createFromFormat('Y-m-d', $consultation_date);
        $today = new DateTime();
        $today->setTime(0, 0, 0);
        
        if (!$date || $date->format('Y-m-d') !== $consultation_date) {
            $errors[] = 'Invalid date format.';
        } elseif ($date < $today) {
            $errors[] = 'Consultation date cannot be in the past.';
        }
    }
    
    if (empty($consultation_time)) {
        $errors[] = 'Preferred consultation time is required.';
    }
    
    if (empty($message)) {
        $errors[] = 'Please provide details about your consultation needs.';
    } elseif (strlen($message) < 10) {
        $errors[] = 'Message must be at least 10 characters long.';
    }
    
    // If there are validation errors, return them
    if (!empty($errors)) {
        echo json_encode([
            'success' => false,
            'message' => 'Please fix the following errors:',
            'errors' => $errors
        ]);
        exit;
    }
    
    // Check if service exists
    $stmt = $pdo->prepare("SELECT id FROM services WHERE id = ?");
    $stmt->execute([$service_id]);
    if (!$stmt->fetch()) {
        echo json_encode([
            'success' => false,
            'message' => 'Selected service not found.'
        ]);
        exit;
    }
    
    // Check if doctor exists (if provided)
    if ($doctor_id !== null) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ? AND user_type = 'doctor'");
        $stmt->execute([$doctor_id]);
        if (!$stmt->fetch()) {
            $doctor_id = null; // Reset to null if invalid
        }
    }
    
    // Get user's IP address
    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    
    // Check for duplicate submission (same email within last 5 minutes)
    $stmt = $pdo->prepare("
        SELECT id FROM guest_consultations 
        WHERE email = ? 
        AND created_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE)
    ");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        echo json_encode([
            'success' => false,
            'message' => 'You have already submitted a consultation request recently. Please wait a few minutes before submitting another.'
        ]);
        exit;
    }
    
    // Insert consultation into database
    $stmt = $pdo->prepare("
        INSERT INTO guest_consultations 
        (full_name, email, phone, service_id, doctor_id, consultation_date, consultation_time, message, ip_address, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
    ");
    
    $result = $stmt->execute([
        $full_name,
        $email,
        $phone,
        $service_id,
        $doctor_id,
        $consultation_date,
        $consultation_time,
        $message,
        $ip_address
    ]);
    
    if ($result) {
        $consultation_id = $pdo->lastInsertId();
        
        // Optional: Send email notification (you'll need to implement this)
        // send_consultation_notification($consultation_id, $email, $full_name);
        
        echo json_encode([
            'success' => true,
            'message' => 'Your consultation request has been submitted successfully! Our team will contact you shortly.',
            'consultation_id' => $consultation_id,
            'show_registration_prompt' => true
        ]);
    } else {
        throw new Exception('Failed to submit consultation.');
    }
    
} catch (PDOException $e) {
    error_log('Consultation submission error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while processing your request. Please try again later.'
    ]);
} catch (Exception $e) {
    error_log('Consultation submission error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
<?php
session_start();

// Set error handling FIRST
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Catch all errors and convert to JSON
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $errstr,
        'file' => $errfile,
        'line' => $errline
    ]);
    exit;
});

try {
    // Debug: Log that we received the request
    error_log("Register controller called");
    
    // Check if database config exists
    if (!file_exists('../config/database.php')) {
        throw new Exception('Database config file not found at: ' . realpath('../config'));
    }
    
    require_once '../config/database.php';
    error_log("Database connected successfully");
    
    // Check if it's AJAX request
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
        throw new Exception('Invalid request: Not AJAX');
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method: ' . $_SERVER['REQUEST_METHOD']);
    }

    // Get and sanitize input
    $user_type = trim($_POST['user_type'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $password = $_POST['password_hash'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    error_log("Form data received - Email: $email, User Type: $user_type");

    // Validation
    if (empty($user_type) || !in_array($user_type, ['patient', 'doctor'])) {
        throw new Exception('Please select a valid user type');
    }

    if (empty($full_name)) {
        throw new Exception('Full name is required');
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Please enter a valid email address');
    }

    if (empty($phone)) {
        throw new Exception('Phone number is required');
    }

    if (empty($location)) {
        throw new Exception('Location is required');
    }

    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/\d/', $password)) {
        throw new Exception('Password must be at least 8 characters with 1 uppercase and 1 number');
    }

    if ($password !== $confirm_password) {
        throw new Exception('Passwords do not match');
    }

    // Check if email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        throw new Exception('This email is already registered');
    }

    error_log("Email validation passed");

    // Start transaction
    $pdo->beginTransaction();
    error_log("Transaction started");

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into users table
    $stmt = $pdo->prepare("
        INSERT INTO users (user_type, email, password_hash, full_name, phone, status, email_verified, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, 'active', 0, NOW(), NOW())
    ");
    $stmt->execute([$user_type, $email, $hashed_password, $full_name, $phone]);
    $user_id = $pdo->lastInsertId();

    error_log("User created with ID: $user_id");

    if (!$user_id) {
        throw new Exception('Failed to create user account');
    }

    // Insert into patient_profiles or doctor_profiles
    if ($user_type === 'patient') {
        $stmt = $pdo->prepare("
            INSERT INTO patient_profiles (user_id, phone, address, city, created_at, updated_at) 
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->execute([$user_id, $phone, $location, $location]);
        error_log("Patient profile created");
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO doctor_profiles (user_id, phone, address, city, status, created_at, updated_at) 
            VALUES (?, ?, ?, ?, 'pending', NOW(), NOW())
        ");
        $stmt->execute([$user_id, $phone, $location, $location]);
        error_log("Doctor profile created");
    }

    $pdo->commit();
    error_log("Transaction committed successfully");

    echo json_encode([
        'success' => true,
        'message' => 'Registration successful! You can now login.'
    ]);

} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    error_log("PDO Error: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
    
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    error_log("Error: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

exit;
?>
<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

// Check if request is AJAX
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

try {
    $user_id = $_SESSION['user_id'];

    // Fetch current verification status
    $stmt = $pdo->prepare("
        SELECT email_verified 
        FROM users 
        WHERE id = ?
    ");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user) {
        echo json_encode(['error' => 'User not found']);
        exit;
    }

    // Return verification status
    echo json_encode([
        'email_verified' => (bool)$user['email_verified'],
        'timestamp' => date('Y-m-d H:i:s')
    ]);

} catch (PDOException $e) {
    error_log("Verification check error: " . $e->getMessage());
    echo json_encode([
        'error' => 'Database error',
        'email_verified' => false
    ]);
}

exit;
?>
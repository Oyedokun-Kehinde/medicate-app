<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

try {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($login) || empty($password)) {
        throw new Exception('Please enter both email/phone and password');
    }

    // Determine if login is email or phone
    $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);
    
    if ($isEmail) {
        $stmt = $pdo->prepare("SELECT id, user_type, password_hash, email_verified, email FROM users WHERE email = ? AND status = 'active'");
        $stmt->execute([$login]);
    } else {
        $phone = preg_replace('/^\+?234/', '0', $login);
        $stmt = $pdo->prepare("SELECT id, user_type, password_hash, email_verified, email FROM users WHERE phone = ? AND status = 'active'");
        $stmt->execute([$phone]);
    }

    $user = $stmt->fetch();
    if (!$user || !password_verify($password, $user['password_hash'])) {
        throw new Exception('Invalid email/phone or password');
    }

    // ✅ NEW: Check if email is verified
    if ($user['email_verified'] == 0) {
        $verifyLink = '<a href="verify-email.php?email=' . urlencode($user['email']) . '">Verify now</a>';
        throw new Exception('Please verify your email first. ' . $verifyLink);
    }

    // Regenerate session ID
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];

    // Redirect based on user type
    $redirect = match($user['user_type']) {
        'doctor' => '/dashboard/doctor/index.php',
        'admin' => '/dashboard/admin/index.php',
        default => '/dashboard/patient/index.php'
    };

    echo json_encode([
        'success' => true,
        'redirect' => $redirect
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
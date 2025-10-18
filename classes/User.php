<?php
// classes/User.php

require_once __DIR__ . '/../config/database.php';

class User {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    // Register a new user (patient or doctor) - AUTO VERIFIED
    public function register($email, $password, $user_type) {
        // Validate input
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email format.'];
        }

        if (strlen($password) < 6) {
            return ['success' => false, 'message' => 'Password must be at least 6 characters.'];
        }

        // Check if email already exists
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Email already registered.'];
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Generate verification code (no longer used, but kept for potential future use)
        $verificationCode = rand(100000, 999999);

        // Insert into users table - AUTO SET is_verified = 1
        $stmt = $this->pdo->prepare("
            INSERT INTO users (email, password, user_type, verification_code, is_verified) 
            VALUES (?, ?, ?, ?, 1)
        ");
        $result = $stmt->execute([$email, $hashedPassword, $user_type, $verificationCode]);

        if ($result) {
            $userId = $this->pdo->lastInsertId();

            // Create empty profile
            if ($user_type === 'patient') {
                $this->pdo->prepare("INSERT INTO patient_profiles (user_id, full_name) VALUES (?, '')")
                         ->execute([$userId]);
            } else {
                $this->pdo->prepare("INSERT INTO doctor_profiles (user_id, full_name) VALUES (?, '')")
                         ->execute([$userId]);
            }

            return [
                'success' => true,
                'user_id' => $userId,
                'message' => 'Registration successful!'
            ];
        } else {
            return ['success' => false, 'message' => 'Registration failed. Please try again.'];
        }
    }

    // Login user
    public function login($email, $password) {
        $stmt = $this->pdo->prepare("SELECT id, password, user_type, is_verified FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            if (!$user['is_verified']) {
                return ['success' => false, 'message' => 'Your account is not verified. Please contact support.'];
            }

            // Start session
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_type'] = $user['user_type'];

            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'Invalid email or password.'];
        }
    }

    // Mark user as verified (kept for compatibility, but users are already verified on registration)
    public function verifyUser($userId) {
        $stmt = $this->pdo->prepare("UPDATE users SET is_verified = 1 WHERE id = ?");
        return $stmt->execute([$userId]);
    }
}
?>
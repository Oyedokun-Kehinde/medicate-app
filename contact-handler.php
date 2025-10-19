<?php
// FILE: contact-handler.php
// Save this in your root directory

session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    $errors = [];
    
    // Validate inputs
    if (empty($name)) {
        $errors[] = 'Name is required';
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required';
    }
    
    if (empty($subject)) {
        $errors[] = 'Subject is required';
    }
    
    if (empty($message)) {
        $errors[] = 'Message is required';
    }
    
    if (!empty($errors)) {
        echo json_encode([
            'success' => false,
            'message' => implode(', ', $errors)
        ]);
        exit();
    }
    
    try {
        // Insert into database
        $stmt = $pdo->prepare("
            INSERT INTO contact_messages (name, email, subject, message, created_at)
            VALUES (?, ?, ?, ?, NOW())
        ");
        
        $result = $stmt->execute([$name, $email, $subject, $message]);
        
        if ($result) {
            // Optional: Send email notification
            $to = 'info@medicate.com';
            $emailSubject = "New Contact Message from $name";
            $emailBody = "Name: $name\nEmail: $email\nSubject: $subject\n\nMessage:\n$message";
            $headers = "From: $email";
            
            // Uncomment to enable email sending
            // mail($to, $emailSubject, $emailBody, $headers);
            
            echo json_encode([
                'success' => true,
                'message' => 'Thank you for contacting us! We will get back to you soon.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred. Please try again.'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
    
    exit();
}

// If not POST, redirect to contact page
header('Location: contact.php');
exit();
?>
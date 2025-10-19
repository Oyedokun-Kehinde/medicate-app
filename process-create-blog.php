<?php
// FILE: process-create-blog.php
// This handles blog post creation by doctors

session_start();
require_once 'config/database.php';

// Check if user is doctor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_id = $_SESSION['user_id'];
    $title = trim($_POST['title'] ?? '');
    $content = $_POST['content'] ?? '';
    $excerpt = trim($_POST['excerpt'] ?? '');
    $status = $_POST['status'] ?? 'published';
    
    $errors = [];
    
    // Validation
    if (empty($title)) {
        $errors[] = 'Title is required';
    }
    
    if (empty($content)) {
        $errors[] = 'Content is required';
    }
    
    if (strlen($title) > 255) {
        $errors[] = 'Title must be less than 255 characters';
    }
    
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: doctor-dashboard.php?section=blogs&action=create');
        exit;
    }
    
    try {
        // Generate slug from title
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        
        // Check if slug already exists
        $stmt = $pdo->prepare("SELECT id FROM blog_posts WHERE slug = ?");
        $stmt->execute([$slug]);
        if ($stmt->fetch()) {
            $slug = $slug . '-' . time();
        }
        
        // Handle featured image upload
        $featured_image = null;
        if (!empty($_FILES['featured_image']['name'])) {
            $file = $_FILES['featured_image'];
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = basename($file['name']);
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed) && $file['size'] < 5000000) {
                $new_filename = 'blog_' . time() . '.' . $ext;
                $upload_path = 'assets/images/blog/' . $new_filename;
                
                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                    $featured_image = $upload_path;
                }
            }
        }
        
        // Insert into database
        $stmt = $pdo->prepare("
            INSERT INTO blog_posts (doctor_id, title, slug, content, excerpt, featured_image, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $doctor_id,
            $title,
            $slug,
            $content,
            $excerpt,
            $featured_image,
            $status
        ]);
        
        $blog_id = $pdo->lastInsertId();
        
        $_SESSION['success'] = 'Blog post created successfully!';
        header('Location: doctor-dashboard.php?section=blogs');
        exit;
        
    } catch (Exception $e) {
        $_SESSION['error'] = 'Error creating blog: ' . $e->getMessage();
        header('Location: doctor-dashboard.php?section=blogs&action=create');
        exit;
    }
} else {
    header('Location: doctor-dashboard.php');
    exit;
}
?>
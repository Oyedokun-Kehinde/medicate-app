<?php
session_start();
require_once 'config/database.php';

// Check if user is doctor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Unauthorized. You must be logged in as doctor']);
    exit;
}

// Submit Blog as a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$doctor_id = $_SESSION['user_id'];
$response = ['success' => false, 'error' => ''];

try {
    // Get POST data
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $status = $_POST['status'] ?? 'published';
    
    // Validate title
    if (empty($title)) {
        throw new Exception('Title is required');
    }
    if (strlen($title) < 3) {
        throw new Exception('Title must be at least 3 characters');
    }
    if (strlen($title) > 255) {
        throw new Exception('Title must be less than 255 characters');
    }
    
    // Validate content
    if (empty($content)) {
        throw new Exception('Content is required');
    }
    if (strlen($content) < 10) {
        throw new Exception('Content must be at least 10 characters');
    }
    
    // Validate status
    if (!in_array($status, ['published', 'draft'])) {
        throw new Exception('Invalid status');
    }
    
    // Generate slug from title
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
    
    // Check for duplicate slug
    $stmt = $pdo->prepare("SELECT id FROM blog_posts WHERE slug = ?");
    $stmt->execute([$slug]);
    if ($stmt->fetch()) {
        $slug = $slug . '-' . time();
    }
    
    // Handle featured image upload
    $featured_image = null;
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['featured_image'];
        
        // Validate file size (5MB max)
        if ($file['size'] > 5 * 1024 * 1024) {
            throw new Exception('Image size must not exceed 5MB');
        }
        
        // Validate file type using MIME type detection
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        // Validate allowed MIME types
        $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($mime_type, $allowed_mimes)) {
            throw new Exception('Invalid image type. Only JPEG, PNG, GIF, and WebP allowed');
        }
        
        // Create upload directory
        $upload_dir = 'assets/uploads/blog';
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0755, true)) {
                throw new Exception('Failed to create upload directory');
            }
        }
        
        // Generate a unique filename
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'blog_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($file_extension);
        $upload_path = $upload_dir . '/' . $filename;
        
        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
            throw new Exception('Failed to upload image');
        }
        
        $featured_image = $upload_path;
    }
    
    // Prepare excerpt
    if (empty($excerpt)) {
        $excerpt = substr(strip_tags($content), 0, 200);
    }
    
    // Insert blog post into database
    $stmt = $pdo->prepare("
        INSERT INTO blog_posts (doctor_id, title, slug, content, excerpt, featured_image, status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    
    $result = $stmt->execute([
        $doctor_id,
        $title,
        $slug,
        $content,
        $excerpt,
        $featured_image,
        $status
    ]);
    
    if (!$result) {
        throw new Exception('Database error: ' . implode(' ', $stmt->errorInfo()));
    }
    
    $blog_id = $pdo->lastInsertId();
    
    if (!$blog_id) {
        throw new Exception('Failed to retrieve blog ID');
    }
    
    $response['success'] = true;
    $response['message'] = $status === 'published' ? 'Blog post published successfully!' : 'Blog post saved as draft!';
    $response['post_id'] = $blog_id;
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['error'] = $e->getMessage();
    error_log("Blog creation error: " . $e->getMessage() . " | Doctor ID: " . ($doctor_id ?? 'Unknown'));
}

// CRITICAL: Always return JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
exit;
?>
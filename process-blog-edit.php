<?php
session_start();
require_once 'config/database.php';

// Check if user is doctor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    http_response_code(401);
    
    //API Endpoint for modifying blogs by Doctors
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$doctor_id = $_SESSION['user_id'];
$response = ['success' => false, 'error' => ''];

try {
    $blog_id = intval($_POST['blog_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $status = $_POST['status'] ?? 'published';
    
    // Validate blog ID
    if ($blog_id <= 0) {
        throw new Exception('Invalid blog ID');
    }
    
    // Verify ownership
    $stmt = $pdo->prepare("SELECT doctor_id, featured_image FROM blog_posts WHERE id = ?");
    $stmt->execute([$blog_id]);
    $blog = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$blog) {
        throw new Exception('Blog post not found');
    }
    
    if ($blog['doctor_id'] != $doctor_id) {
        throw new Exception('Unauthorized - you can only edit your own posts');
    }
    
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
    
    $featured_image = $blog['featured_image']; // Keep existing if not replacing
    
    // Handle featured image replacement by upload
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['featured_image'];
        
        // Validate file size (5MB max)
        if ($file['size'] > 5 * 1024 * 1024) {
            throw new Exception('Image size must not exceed 5MB');
        }
        
        // Validate file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
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
        
        // Delete old image if exists
        if (!empty($blog['featured_image']) && file_exists($blog['featured_image'])) {
            unlink($blog['featured_image']);
        }
        
        // Generate unique filename
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
    
    // Update blog post
    $stmt = $pdo->prepare("
        UPDATE blog_posts 
        SET title = ?, content = ?, excerpt = ?, featured_image = ?, status = ?, updated_at = NOW()
        WHERE id = ? AND doctor_id = ?
    ");
    
    $result = $stmt->execute([
        $title,
        $content,
        $excerpt,
        $featured_image,
        $status,
        $blog_id,
        $doctor_id
    ]);
    
    if (!$result) {
        throw new Exception('Database error: ' . implode(' ', $stmt->errorInfo()));
    }
    
    $response['success'] = true;
    $response['message'] = 'Blog post updated successfully!';
    $response['post_id'] = $blog_id;
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['error'] = $e->getMessage();
    error_log("Blog edit error: " . $e->getMessage() . " | Doctor ID: " . ($doctor_id ?? 'Unknown'));
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
exit;
?>
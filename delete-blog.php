<?php
// FILE: delete-blog.php
// Handles blog post deletion

session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

// Check if user is doctor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blog_id = (int)($_POST['blog_id'] ?? 0);
    $doctor_id = $_SESSION['user_id'];
    
    if ($blog_id <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid blog ID']);
        exit;
    }
    
    try {
        // Verify ownership
        $stmt = $pdo->prepare("SELECT id, featured_image FROM blog_posts WHERE id = ? AND doctor_id = ?");
        $stmt->execute([$blog_id, $doctor_id]);
        $blog = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$blog) {
            echo json_encode(['success' => false, 'error' => 'Blog post not found or unauthorized']);
            exit;
        }
        
        // Delete featured image if exists
        if (!empty($blog['featured_image']) && file_exists($blog['featured_image'])) {
            unlink($blog['featured_image']);
        }
        
        // Delete blog post (comments will cascade delete)
        $stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id = ? AND doctor_id = ?");
        $stmt->execute([$blog_id, $doctor_id]);
        
        echo json_encode(['success' => true, 'message' => 'Blog post deleted successfully']);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Error deleting blog: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
}
?>
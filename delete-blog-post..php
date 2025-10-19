<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in as doctor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$doctor_id = $_SESSION['user_id'];
$blog_id = (int)($_POST['blog_id'] ?? 0);

if ($blog_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid blog ID']);
    exit;
}

try {
    // Verify blog belongs to current doctor
    $stmt = $pdo->prepare("SELECT featured_image FROM blog_posts WHERE id = ? AND doctor_id = ?");
    $stmt->execute([$blog_id, $doctor_id]);
    $blog = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$blog) {
        echo json_encode(['success' => false, 'error' => 'Blog not found']);
        exit;
    }
    
    // Delete featured image if exists
    if (!empty($blog['featured_image']) && file_exists($blog['featured_image'])) {
        unlink($blog['featured_image']);
    }
    
    // Delete blog post (comments will be cascade deleted)
    $stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id = ? AND doctor_id = ?");
    $stmt->execute([$blog_id, $doctor_id]);
    
    echo json_encode(['success' => true, 'message' => 'Blog post deleted successfully']);
    
} catch (Exception $e) {
    error_log("Blog deletion error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Error deleting blog post']);
}
?>
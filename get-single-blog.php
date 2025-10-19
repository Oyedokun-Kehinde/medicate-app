<?php
session_start();
require_once 'config/database.php';

// Check authorization
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$doctor_id = $_SESSION['user_id'];
$blog_id = intval($_GET['blog_id'] ?? 0);

if ($blog_id <= 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid blog ID']);
    exit;
}

try {
    // Fetch single blog - verify ownership
    $stmt = $pdo->prepare("
        SELECT id, title, content, excerpt, featured_image, status, created_at, updated_at
        FROM blog_posts 
        WHERE id = ? AND doctor_id = ?
    ");
    $stmt->execute([$blog_id, $doctor_id]);
    $blog = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$blog) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Blog post not found or unauthorized']);
        exit;
    }
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'blog' => $blog
    ]);
    
} catch (PDOException $e) {
    error_log("Error fetching blog: " . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Database error']);
    exit;
}
?>
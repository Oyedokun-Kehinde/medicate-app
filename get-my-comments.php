<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in as patient
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("
        SELECT 
            bc.id, bc.comment_text, bc.created_at,
            bp.id as blog_post_id, bp.title as blog_title
        FROM blog_comments bc
        JOIN blog_posts bp ON bc.blog_post_id = bp.id
        WHERE bc.user_id = ?
        ORDER BY bc.created_at DESC
    ");
    $stmt->execute([$user_id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'comments' => $comments
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
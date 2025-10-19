<?php
// FILE: get-my-comments.php
// Fetches comments posted by logged-in user

session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

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
    $stmt->execute([$_SESSION['user_id']]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'comments' => $comments
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error fetching comments: ' . $e->getMessage()
    ]);
}
?>
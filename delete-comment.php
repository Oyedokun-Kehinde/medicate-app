<?php
// FILE: delete-comment.php
// Handles blog comment deletion

session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_id = (int)($_POST['comment_id'] ?? 0);
    $user_id = $_SESSION['user_id'];
    
    if ($comment_id <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid comment ID']);
        exit;
    }
    
    try {
        // Verify ownership
        $stmt = $pdo->prepare("SELECT id FROM blog_comments WHERE id = ? AND user_id = ?");
        $stmt->execute([$comment_id, $user_id]);
        
        if (!$stmt->fetch()) {
            echo json_encode(['success' => false, 'error' => 'Comment not found or unauthorized']);
            exit;
        }
        
        // Delete comment
        $stmt = $pdo->prepare("DELETE FROM blog_comments WHERE id = ? AND user_id = ?");
        $stmt->execute([$comment_id, $user_id]);
        
        echo json_encode(['success' => true, 'message' => 'Comment deleted successfully']);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Error deleting comment: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
}
?>
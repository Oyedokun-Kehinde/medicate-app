<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$comment_id = (int)($_POST['comment_id'] ?? 0);

if ($comment_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid comment ID']);
    exit;
}

try {
    // Verify comment belongs to current user
    $stmt = $pdo->prepare("SELECT id FROM blog_comments WHERE id = ? AND user_id = ?");
    $stmt->execute([$comment_id, $user_id]);
    
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Comment not found']);
        exit;
    }
    
    // Delete comment
    $stmt = $pdo->prepare("DELETE FROM blog_comments WHERE id = ? AND user_id = ?");
    $stmt->execute([$comment_id, $user_id]);
    
    echo json_encode(['success' => true, 'message' => 'Comment deleted successfully']);
    
} catch (Exception $e) {
    error_log("Comment deletion error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Error deleting comment']);
}
?>
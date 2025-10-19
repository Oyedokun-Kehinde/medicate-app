<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Please log in to comment']);
    exit;
}

$user_id = $_SESSION['user_id'];
$blog_post_id = (int)($_POST['blog_post_id'] ?? 0);
$comment_text = trim($_POST['comment_text'] ?? '');

// Validate inputs
if ($blog_post_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid blog post']);
    exit;
}

if (empty($comment_text)) {
    echo json_encode(['success' => false, 'error' => 'Comment cannot be empty']);
    exit;
}

if (strlen($comment_text) < 3) {
    echo json_encode(['success' => false, 'error' => 'Comment must be at least 3 characters']);
    exit;
}

if (strlen($comment_text) > 1000) {
    echo json_encode(['success' => false, 'error' => 'Comment is too long (max 1000 characters)']);
    exit;
}

try {
    // Verify blog post exists and is published
    $stmt = $pdo->prepare("SELECT id FROM blog_posts WHERE id = ? AND status = 'published'");
    $stmt->execute([$blog_post_id]);
    
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Blog post not found']);
        exit;
    }
    
    // Insert comment
    $stmt = $pdo->prepare("
        INSERT INTO blog_comments (blog_post_id, user_id, comment_text, created_at)
        VALUES (?, ?, ?, NOW())
    ");
    
    $stmt->execute([$blog_post_id, $user_id, $comment_text]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Comment posted successfully!'
    ]);
    
} catch (PDOException $e) {
    error_log("Comment error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Error posting comment']);
}
?>
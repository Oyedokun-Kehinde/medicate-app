<?php
// FILE: process-blog-comment.php
// Handles blog comment submission

session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $blog_post_id = (int)($_POST['blog_post_id'] ?? 0);
    $comment_text = trim($_POST['comment_text'] ?? '');
    $user_id = $_SESSION['user_id'];
    
    header('Content-Type: application/json');
    
    $errors = [];
    
    // Validation
    if ($blog_post_id <= 0) {
        $errors[] = 'Invalid blog post';
    }
    
    if (empty($comment_text)) {
        $errors[] = 'Comment cannot be empty';
    }
    
    if (strlen($comment_text) < 3) {
        $errors[] = 'Comment must be at least 3 characters';
    }
    
    if (strlen($comment_text) > 5000) {
        $errors[] = 'Comment is too long (max 5000 characters)';
    }
    
    if (!empty($errors)) {
        echo json_encode([
            'success' => false,
            'errors' => $errors
        ]);
        exit;
    }
    
    try {
        // Verify blog exists
        $stmt = $pdo->prepare("SELECT id FROM blog_posts WHERE id = ?");
        $stmt->execute([$blog_post_id]);
        if (!$stmt->fetch()) {
            echo json_encode([
                'success' => false,
                'error' => 'Blog post not found'
            ]);
            exit;
        }
        
        // Get commenter name
        $stmt = $pdo->prepare("
            SELECT COALESCE(pp.full_name, dp.full_name, u.email) as name
            FROM users u
            LEFT JOIN patient_profiles pp ON u.id = pp.user_id
            LEFT JOIN doctor_profiles dp ON u.id = dp.user_id
            WHERE u.id = ?
        ");
        $stmt->execute([$user_id]);
        $commenter = $stmt->fetch(PDO::FETCH_ASSOC);
        $comment_author_name = $commenter['name'] ?? 'Anonymous';
        
        // Insert comment
        $stmt = $pdo->prepare("
            INSERT INTO blog_comments (blog_post_id, user_id, comment_author_name, comment_text)
            VALUES (?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $blog_post_id,
            $user_id,
            $comment_author_name,
            $comment_text
        ]);
        
        $comment_id = $pdo->lastInsertId();
        
        echo json_encode([
            'success' => true,
            'message' => 'Comment added successfully!',
            'comment_id' => $comment_id
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => 'Error adding comment: ' . $e->getMessage()
        ]);
    }
} else {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'error' => 'Unauthorized'
    ]);
}
?>
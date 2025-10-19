<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in as doctor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$doctor_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("
        SELECT 
            id, title, excerpt, featured_image, status, created_at, updated_at,
            (SELECT COUNT(*) FROM blog_comments WHERE blog_post_id = blog_posts.id) as comment_count
        FROM blog_posts
        WHERE doctor_id = ?
        ORDER BY created_at DESC
    ");
    $stmt->execute([$doctor_id]);
    $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $html = '';
    
    if (empty($blogs)) {
        $html = '
            <div class="empty-state" style="text-align: center; padding: 40px;">
                <i class="fas fa-blog" style="font-size: 48px; opacity: 0.3; margin-bottom: 15px;"></i>
                <h4>No Blog Posts Yet</h4>
                <p>Start sharing your medical insights with our community!</p>
            </div>
        ';
    } else {
        foreach ($blogs as $blog) {
            $status_class = $blog['status'] === 'published' ? 'published' : 'draft';
            $status_text = ucfirst($blog['status']);
            
            $html .= '
                <div class="blog-item">
                    <h4>' . htmlspecialchars($blog['title']) . '</h4>
                    <div class="blog-meta">
                        Published: ' . date('F d, Y \a\t H:i', strtotime($blog['created_at'])) . ' | 
                        ' . $blog['comment_count'] . ' Comments
                    </div>
                    <p style="color: #666; margin: 10px 0;">' . htmlspecialchars(substr($blog['excerpt'] ?? '', 0, 150)) . '...</p>
                    <span class="blog-status ' . $status_class . '">' . $status_text . '</span>
                    <div class="blog-actions" style="margin-top: 10px;">
                        <button class="btn-primary" style="padding: 6px 12px; font-size: 12px;" onclick="editBlog(' . $blog['id'] . ')">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-delete-comment" onclick="deleteBlog(' . $blog['id'] . ')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                        <a href="blog-single.php?id=' . $blog['id'] . '" class="btn-view-post" target="_blank" style="text-decoration: none;">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </div>
                </div>
            ';
        }
    }
    
    echo json_encode(['success' => true, 'html' => $html]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
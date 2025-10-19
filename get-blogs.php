<?php
// FILE: get-blogs.php
// Fetches blogs for display (used by AJAX and direct calls)

session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

// Determine context
$action = $_GET['action'] ?? 'public';
$my_blogs = $_GET['my_blogs'] ?? false;
$blog_id = $_GET['blog_id'] ?? null;

try {
    if ($action === 'single' && $blog_id) {
        // Fetch single blog post with comments
        $stmt = $pdo->prepare("
            SELECT 
                bp.id, bp.title, bp.slug, bp.content, bp.excerpt, 
                bp.featured_image, bp.created_at, bp.updated_at,
                dp.full_name as doctor_name,
                u.id as doctor_user_id
            FROM blog_posts bp
            JOIN users u ON bp.doctor_id = u.id
            LEFT JOIN doctor_profiles dp ON u.id = dp.user_id
            WHERE bp.id = ? AND bp.status = 'published'
        ");
        $stmt->execute([$blog_id]);
        $blog = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$blog) {
            http_response_code(404);
            exit('Blog not found');
        }
        
        // Fetch comments
        $stmt = $pdo->prepare("
            SELECT 
                bc.id, bc.comment_text, bc.created_at, 
                bc.comment_author_name,
                u.user_type,
                COALESCE(pp.full_name, dp.full_name, u.email) as commenter_name
            FROM blog_comments bc
            JOIN users u ON bc.user_id = u.id
            LEFT JOIN patient_profiles pp ON u.id = pp.user_id
            LEFT JOIN doctor_profiles dp ON u.id = dp.user_id
            WHERE bc.blog_post_id = ?
            ORDER BY bc.created_at DESC
        ");
        $stmt->execute([$blog_id]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'blog' => $blog,
            'comments' => $comments
        ]);
        
    } elseif ($action === 'my_blogs' && isset($_SESSION['user_id'])) {
        // Fetch doctor's own blogs
        if ($_SESSION['user_type'] !== 'doctor') {
            http_response_code(403);
            exit('Unauthorized');
        }
        
        $stmt = $pdo->prepare("
            SELECT id, title, excerpt, featured_image, status, created_at, updated_at
            FROM blog_posts
            WHERE doctor_id = ?
            ORDER BY created_at DESC
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'blogs' => $blogs
        ]);
        
    } else {
        // Fetch all published blogs (public listing)
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $per_page = 9;
        $offset = ($page - 1) * $per_page;
        
        $stmt = $pdo->prepare("
            SELECT 
                bp.id, bp.title, bp.slug, bp.excerpt, bp.featured_image, 
                bp.created_at,
                dp.full_name as doctor_name,
                (SELECT COUNT(*) FROM blog_comments WHERE blog_post_id = bp.id) as comment_count
            FROM blog_posts bp
            JOIN users u ON bp.doctor_id = u.id
            LEFT JOIN doctor_profiles dp ON u.id = dp.user_id
            WHERE bp.status = 'published'
            ORDER BY bp.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$per_page, $offset]);
        $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get total count for pagination
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM blog_posts WHERE status = 'published'");
        $stmt->execute();
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        $total_pages = ceil($total / $per_page);
        
        echo json_encode([
            'success' => true,
            'blogs' => $blogs,
            'current_page' => $page,
            'total_pages' => $total_pages,
            'total_posts' => $total
        ]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
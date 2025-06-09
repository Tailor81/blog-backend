<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../models/Blog.php';

try {
    // Initialize database connection
    $database = new Database();
    $db = $database->getConnection();

    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
    
    $query = "SELECT id, title, slug, featured_image, created_at 
              FROM blog_posts 
              WHERE status = 'published'
              ORDER BY created_at DESC 
              LIMIT :limit";
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'posts' => $posts
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?> 
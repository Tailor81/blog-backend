<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../models/Blog.php';

try {
    // Initialize database connection
    $database = new Database();
    $db = $database->getConnection();

    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 3;
    $offset = ($page - 1) * $per_page;

    // Prepare the search query
    $search = '%' . $search . '%';
    
    // Get total count of matching posts
    $count_query = "SELECT COUNT(*) as total FROM blog_posts WHERE (title LIKE :search OR content LIKE :search) AND status = 'published'";
    $count_stmt = $db->prepare($count_query);
    $count_stmt->execute(['search' => $search]);
    $total_posts = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Get the posts
    $query = "SELECT * FROM blog_posts 
              WHERE (title LIKE :search OR content LIKE :search) AND status = 'published'
              ORDER BY created_at DESC 
              LIMIT :offset, :per_page";
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(':search', $search, PDO::PARAM_STR);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':per_page', $per_page, PDO::PARAM_INT);
    $stmt->execute();
    
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'posts' => $posts,
        'total_posts' => $total_posts
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?> 
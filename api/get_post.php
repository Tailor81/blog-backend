<?php
header('Content-Type: application/json');
require_once '../config/database.php';

try {
    // Initialize database connection
    $database = new Database();
    $conn = $database->getConnection();

    // Get the slug from the query string
    $slug = isset($_GET['slug']) ? $_GET['slug'] : null;

    if (!$slug) {
        throw new Exception('No slug provided');
    }

    // Prepare the SQL query
    $query = "SELECT * FROM blog_posts WHERE slug = :slug";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":slug", $slug);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        throw new Exception('Post not found');
    }

    // Fetch the post
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    // Format the response
    $response = [
        'status' => 'success',
        'post' => [
            'id' => $post['id'],
            'title' => $post['title'],
            'content' => $post['content'],
            'author' => $post['author'],
            'status' => $post['status'],
            'slug' => $post['slug'],
            'featured_image' => $post['featured_image'],
            'meta_description' => $post['meta_description'],
            'created_at' => $post['created_at']
        ]
    ];

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(404);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?> 
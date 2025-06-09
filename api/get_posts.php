<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../models/Blog.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Initialize blog object
$blog = new Blog($db);

// Get pagination parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 3;
$offset = ($page - 1) * $per_page;

// Get total count of posts
$count_query = "SELECT COUNT(*) as total FROM blog_posts";
$count_stmt = $db->prepare($count_query);
$count_stmt->execute();
$total_posts = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Get paginated posts
$query = "SELECT * FROM blog_posts ORDER BY created_at DESC LIMIT :offset, :per_page";
$stmt = $db->prepare($query);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':per_page', $per_page, PDO::PARAM_INT);
$stmt->execute();

$posts = array();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $date = new DateTime($row['created_at']);
    $posts[] = array(
        'id' => $row['id'],
        'title' => $row['title'],
        'content' => $row['content'],
        'author' => $row['author'],
        'created_at' => $date->format('Y-m-d H:i:s'),
        'status' => $row['status'],
        'slug' => $row['slug'],
        'featured_image' => $row['featured_image'],
        'meta_description' => $row['meta_description']
    );
}

echo json_encode([
    'success' => true,
    'posts' => $posts,
    'total_posts' => $total_posts
]); 
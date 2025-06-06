<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../models/Blog.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Initialize blog object
$blog = new Blog($db);

// Read all blog posts
$stmt = $blog->read();
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

echo json_encode($posts); 
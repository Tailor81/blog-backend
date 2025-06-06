<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../models/Blog.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Initialize blog object
$blog = new Blog($db);

// Get the slug from URL
$slug = isset($_GET['slug']) ? $_GET['slug'] : die(json_encode(['error' => 'No post specified']));

// Set the slug property
$blog->slug = $slug;

// Read the post
if($blog->readOne()) {
    $date = new DateTime($blog->created_at);
    $post = array(
        'id' => $blog->id,
        'title' => $blog->title,
        'content' => $blog->content,
        'author' => $blog->author,
        'created_at' => $date->format('Y-m-d H:i:s'),
        'status' => $blog->status,
        'slug' => $blog->slug,
        'featured_image' => $blog->featured_image,
        'meta_description' => $blog->meta_description
    );
    echo json_encode($post);
} else {
    echo json_encode(['error' => 'Post not found']);
} 
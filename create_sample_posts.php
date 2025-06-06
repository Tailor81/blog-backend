<?php
require_once 'config/database.php';
require_once 'models/Blog.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Initialize blog object
$blog = new Blog($db);

// Clear existing posts
try {
    $db->exec("TRUNCATE TABLE blog_posts");
    echo "Cleared existing posts.\n";
} catch (PDOException $e) {
    echo "Error clearing posts: " . $e->getMessage() . "\n";
}

// Sample blog posts
$sample_posts = [
    [
        'title' => 'Getting Started with Web Development',
        'content' => 'Web development is an exciting field that combines creativity with technical skills...',
        'author' => 'John Doe',
        'status' => 'published',
        'slug' => 'getting-started-with-web-development',
        'featured_image' => 'img/blog-1.jpg',  // Using existing image from template
        'meta_description' => 'Learn the basics of web development and start your journey as a web developer.'
    ],
    [
        'title' => 'The Future of Artificial Intelligence',
        'content' => 'Artificial Intelligence is transforming the way we live and work...',
        'author' => 'Jane Smith',
        'status' => 'published',
        'slug' => 'future-of-artificial-intelligence',
        'featured_image' => 'img/blog-2.jpg',  // Using existing image from template
        'meta_description' => 'Explore the latest developments in AI and their impact on our future.'
    ],
    [
        'title' => '10 Tips for Better Code Organization',
        'content' => 'Writing clean, organized code is essential for maintainable software...',
        'author' => 'Mike Johnson',
        'status' => 'published',
        'slug' => 'tips-for-better-code-organization',
        'featured_image' => 'img/blog-1.jpg',  // Using existing image from template
        'meta_description' => 'Learn how to write cleaner, more maintainable code with these essential tips.'
    ]
];

// Create sample posts
$success_count = 0;
foreach ($sample_posts as $post) {
    $blog->title = $post['title'];
    $blog->content = $post['content'];
    $blog->author = $post['author'];
    $blog->status = $post['status'];
    $blog->slug = $post['slug'];
    $blog->featured_image = $post['featured_image'];
    $blog->meta_description = $post['meta_description'];

    if ($blog->create()) {
        $success_count++;
        echo "Created post: " . $post['title'] . "\n";
    } else {
        echo "Failed to create post: " . $post['title'] . "\n";
    }
}

echo "\nSuccessfully created " . $success_count . " posts.\n";
?> 
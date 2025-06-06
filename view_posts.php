<?php
require_once 'config/database.php';
require_once 'models/Blog.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Initialize blog object
$blog = new Blog($db);

// Read all blog posts
$stmt = $blog->read();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Posts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .blog-post {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .blog-post h2 {
            margin-top: 0;
            color: #333;
        }
        .blog-meta {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 10px;
        }
        .blog-content {
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <h1>Blog Posts</h1>
    
    <?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        ?>
        <div class="blog-post">
            <h2><?php echo htmlspecialchars($title); ?></h2>
            <div class="blog-meta">
                By <?php echo htmlspecialchars($author); ?> | 
                Published: <?php echo date('F j, Y', strtotime($created_at)); ?>
            </div>
            <div class="blog-content">
                <?php echo nl2br(htmlspecialchars($content)); ?>
            </div>
        </div>
        <?php
    }
    ?>
</body>
</html> 
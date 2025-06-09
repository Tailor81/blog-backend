<?php
require_once 'api/headers.php';

// Get the requested page
$page = isset($_GET['page']) ? $_GET['page'] : 'index';

// Map the page to the corresponding HTML file
$pages = [
    'index' => 'index.html',
    'blog' => 'blog.html',
    'single' => 'single.html'
];

// Check if the requested page exists
if (isset($pages[$page])) {
    $file = $pages[$page];
    if (file_exists($file)) {
        // Read and output the HTML file
        readfile($file);
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "Page not found";
    }
} else {
    header("HTTP/1.0 404 Not Found");
    echo "Page not found";
}
?> 
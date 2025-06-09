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
        'content' => 'Web development is an exciting field that combines creativity with technical skills. In this comprehensive guide, we\'ll explore the fundamental concepts that every aspiring web developer should know. From HTML and CSS to JavaScript and beyond, we\'ll cover the essential tools and technologies that power the modern web.

Understanding the basics of web development is crucial for anyone looking to build websites or web applications. We\'ll start with HTML, the backbone of every webpage, and learn how to structure content effectively. Then, we\'ll dive into CSS to style our pages and make them visually appealing.

JavaScript will be our next focus, where we\'ll learn how to add interactivity and dynamic features to our websites. We\'ll also explore modern frameworks and libraries that make development more efficient.

By the end of this guide, you\'ll have a solid foundation in web development and be ready to start building your own projects. Remember, practice makes perfect, so don\'t hesitate to experiment and build your own websites as you learn.',
        'author' => 'John Doe',
        'status' => 'published',
        'slug' => 'getting-started-with-web-development',
        'featured_image' => 'images/1.jpg',
        'meta_description' => 'Learn the basics of web development and start your journey as a web developer.'
    ],
    [
        'title' => 'The Future of Artificial Intelligence',
        'content' => 'Artificial Intelligence is transforming the way we live and work. In this in-depth analysis, we\'ll explore the current state of AI and its potential impact on various industries. From machine learning to deep learning, we\'ll examine the technologies driving this revolution.

The applications of AI are vast and growing every day. We\'re seeing AI-powered solutions in healthcare, finance, transportation, and many other sectors. These technologies are helping doctors diagnose diseases, enabling autonomous vehicles, and revolutionizing how businesses operate.

However, with great power comes great responsibility. We\'ll also discuss the ethical considerations surrounding AI development and deployment. Topics include data privacy, algorithmic bias, and the future of work in an AI-driven world.

As we look to the future, it\'s clear that AI will continue to shape our world in profound ways. Understanding these technologies and their implications is crucial for anyone interested in the future of technology and society.',
        'author' => 'Jane Smith',
        'status' => 'published',
        'slug' => 'future-of-artificial-intelligence',
        'featured_image' => 'images/1.jpg',
        'meta_description' => 'Explore the latest developments in AI and their impact on our future.'
    ],
    [
        'title' => '10 Tips for Better Code Organization',
        'content' => 'Writing clean, organized code is essential for maintainable software. In this comprehensive guide, we\'ll explore ten essential tips that will help you write better, more maintainable code. These practices are crucial for both individual developers and teams working on large-scale projects.

First, we\'ll discuss the importance of consistent naming conventions and how they improve code readability. Then, we\'ll explore the benefits of proper code documentation and why it\'s crucial for long-term maintenance. We\'ll also cover the importance of modular design and how it can make your code more reusable and testable.

Error handling is another critical aspect we\'ll examine, along with proper logging practices. We\'ll also discuss the importance of code reviews and how they can help catch issues early in the development process.

By following these tips, you\'ll be able to write code that\'s not only functional but also maintainable and scalable. Remember, good code organization is an investment in the future of your project.',
        'author' => 'Mike Johnson',
        'status' => 'published',
        'slug' => 'tips-for-better-code-organization',
        'featured_image' => 'images/1.jpg',
        'meta_description' => 'Learn how to write cleaner, more maintainable code with these essential tips.'
    ],
    [
        'title' => 'The Rise of Cloud Computing',
        'content' => 'Cloud computing has revolutionized how businesses and individuals store, process, and access data. In this comprehensive overview, we\'ll explore the evolution of cloud computing and its impact on modern technology infrastructure.

We\'ll start by examining the different types of cloud services: Infrastructure as a Service (IaaS), Platform as a Service (PaaS), and Software as a Service (SaaS). Each of these models offers unique benefits and use cases that we\'ll explore in detail.

Security is a major concern in cloud computing, so we\'ll dedicate a section to discussing best practices for securing cloud infrastructure and data. We\'ll also look at how cloud computing enables scalability and flexibility for businesses of all sizes.

The future of cloud computing looks promising, with emerging technologies like edge computing and serverless architecture gaining traction. We\'ll examine these trends and what they mean for the future of technology.',
        'author' => 'Sarah Wilson',
        'status' => 'published',
        'slug' => 'rise-of-cloud-computing',
        'featured_image' => 'images/1.jpg',
        'meta_description' => 'Discover how cloud computing is transforming the technology landscape.'
    ],
    [
        'title' => 'Cybersecurity Best Practices',
        'content' => 'In today\'s digital world, cybersecurity is more important than ever. This comprehensive guide will walk you through essential security practices that every individual and organization should implement.

We\'ll start with the basics of password security and two-factor authentication, then move on to more advanced topics like network security and encryption. We\'ll also discuss the importance of regular security audits and updates.

Social engineering attacks are becoming increasingly sophisticated, so we\'ll dedicate a section to recognizing and preventing these threats. We\'ll also cover data backup strategies and disaster recovery planning.

Remember, cybersecurity is not a one-time effort but an ongoing process. By implementing these best practices, you can significantly reduce your risk of falling victim to cyber attacks.',
        'author' => 'David Brown',
        'status' => 'published',
        'slug' => 'cybersecurity-best-practices',
        'featured_image' => 'images/1.jpg',
        'meta_description' => 'Learn essential cybersecurity practices to protect your digital assets.'
    ],
    [
        'title' => 'The Evolution of Mobile Development',
        'content' => 'Mobile development has come a long way since the first smartphones hit the market. In this detailed exploration, we\'ll trace the evolution of mobile app development and examine current trends and future directions.

We\'ll start by looking at the early days of mobile development and how it has evolved to support modern features like augmented reality and machine learning. We\'ll also explore the differences between native and cross-platform development approaches.

The rise of progressive web apps (PWAs) has changed how we think about mobile development. We\'ll examine how PWAs are bridging the gap between web and native apps, offering new possibilities for developers and users alike.

Looking to the future, we\'ll discuss emerging technologies like 5G and foldable devices, and how they\'re shaping the next generation of mobile applications.',
        'author' => 'Emily Chen',
        'status' => 'published',
        'slug' => 'evolution-of-mobile-development',
        'featured_image' => 'images/1.jpg',
        'meta_description' => 'Explore the past, present, and future of mobile app development.'
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
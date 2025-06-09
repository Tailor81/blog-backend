<?php
class Blog {
    private $conn;
    private $table_name = "blog_posts";

    public $id;
    public $title;
    public $content;
    public $author;
    public $created_at;
    public $updated_at;
    public $status;
    public $slug;
    public $featured_image;
    public $meta_description;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Read all blog posts with pagination
    public function read($page = 1, $per_page = 3) {
        $offset = ($page - 1) * $per_page;
        
        $query = "SELECT * FROM " . $this->table_name . " 
                 WHERE status = 'published' 
                 ORDER BY created_at DESC 
                 LIMIT :offset, :per_page";
                 
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':per_page', $per_page, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt;
    }

    // Get total count of published posts
    public function getTotalPosts() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE status = 'published'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Read single blog post
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->title = $row['title'];
            $this->content = $row['content'];
            $this->author = $row['author'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            $this->status = $row['status'];
            $this->slug = $row['slug'];
            $this->featured_image = $row['featured_image'];
            $this->meta_description = $row['meta_description'];
            return true;
        }
        return false;
    }

    // Create new blog post
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    title = :title,
                    content = :content,
                    author = :author,
                    status = :status,
                    slug = :slug,
                    featured_image = :featured_image,
                    meta_description = :meta_description";

        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->slug = htmlspecialchars(strip_tags($this->slug));
        $this->featured_image = htmlspecialchars(strip_tags($this->featured_image));
        $this->meta_description = htmlspecialchars(strip_tags($this->meta_description));

        // Bind values
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":content", $this->content);
        $stmt->bindParam(":author", $this->author);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":slug", $this->slug);
        $stmt->bindParam(":featured_image", $this->featured_image);
        $stmt->bindParam(":meta_description", $this->meta_description);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?> 
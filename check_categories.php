<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'mock_test_app';

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$database", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<h2>Database Connection: SUCCESS</h2>";
    
    // Check if categories table exists
    $tables = $pdo->query("SHOW TABLES LIKE 'categories'")->fetchAll();
    if (count($tables) > 0) {
        echo "<h3>Categories Table: EXISTS</h3>";
        
        // Check for records in categories
        $count = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
        echo "<p>Number of categories: $count</p>";
        
        if ($count > 0) {
            // Show category data
            $categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
            echo "<h4>Category Data:</h4>";
            echo "<pre>";
            print_r($categories);
            echo "</pre>";
        } else {
            echo "<h4>Creating sample categories:</h4>";
            // Create sample categories if none exist
            $sql = "INSERT INTO categories (name, description) VALUES 
                ('Engineering', 'Engineering entrance exams and practice tests'),
                ('Medical', 'Medical entrance exams and practice tests'),
                ('Programming', 'Programming skills and aptitude tests'),
                ('General Knowledge', 'General knowledge and aptitude tests')";
            
            $result = $pdo->exec($sql);
            echo "<p>Added $result categories</p>";
            
            // Show newly created categories
            $categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
            echo "<h4>New Category Data:</h4>";
            echo "<pre>";
            print_r($categories);
            echo "</pre>";
        }
    } else {
        echo "<h3>Categories Table: MISSING</h3>";
        echo "<p>Creating categories table...</p>";
        
        // Create categories table
        $sql = "CREATE TABLE categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            description TEXT
        )";
        
        $pdo->exec($sql);
        echo "<p>Categories table created successfully.</p>";
        
        // Insert sample data
        $sql = "INSERT INTO categories (name, description) VALUES 
            ('Engineering', 'Engineering entrance exams and practice tests'),
            ('Medical', 'Medical entrance exams and practice tests'),
            ('Programming', 'Programming skills and aptitude tests'),
            ('General Knowledge', 'General knowledge and aptitude tests')";
        
        $result = $pdo->exec($sql);
        echo "<p>Added $result categories</p>";
        
        // Show newly created categories
        $categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
        echo "<h4>New Category Data:</h4>";
        echo "<pre>";
        print_r($categories);
        echo "</pre>";
    }
    
} catch (PDOException $e) {
    echo "<h2>Database Error:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?> 
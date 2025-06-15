<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load configuration
echo "<h2>Loading Configuration</h2>";
try {
    require_once 'includes/config.php';
    echo "<p style='color:green;'>Configuration loaded successfully</p>";
    
    if (defined('DB_HOST')) {
        echo "<p>DB_HOST: " . DB_HOST . "</p>";
        echo "<p>DB_NAME: " . DB_NAME . "</p>";
        echo "<p>DB_USER: " . DB_USER . "</p>";
        echo "<p>Using password: " . (DB_PASS ? "Yes" : "No") . "</p>";
    } else {
        echo "<p style='color:red;'>Database constants not defined in config.php</p>";
    }
    
    echo "<h2>Database Connection Test</h2>";
    if (isset($pdo)) {
        try {
            $stmt = $pdo->query("SELECT 1");
            echo "<p style='color:green;'>Database connection successful</p>";
            
            // Test a real query
            $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            echo "<p>Tables in database: " . implode(", ", $tables) . "</p>";
            
            // Check for categories
            $stmt = $pdo->query("SELECT * FROM categories");
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<p>Number of categories: " . count($categories) . "</p>";
            if (count($categories) > 0) {
                echo "<h3>Categories:</h3>";
                echo "<ul>";
                foreach ($categories as $category) {
                    echo "<li>" . htmlspecialchars($category['name']) . " (ID: {$category['id']})</li>";
                }
                echo "</ul>";
            }
            
            // Test the getCategories function
            echo "<h3>Testing getCategories() function</h3>";
            if (function_exists('getCategories')) {
                $categories = getCategories();
                echo "<p>Number of categories returned: " . count($categories) . "</p>";
                if (count($categories) > 0) {
                    echo "<ul>";
                    foreach ($categories as $category) {
                        echo "<li>" . htmlspecialchars($category['name']) . " (ID: {$category['id']})</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p>No categories returned from function!</p>";
                }
            } else {
                echo "<p style='color:red;'>getCategories() function does not exist!</p>";
            }
            
        } catch (PDOException $e) {
            echo "<p style='color:red;'>Error running query: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color:red;'>PDO database connection not established in config.php</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color:red;'>Error loading configuration: " . $e->getMessage() . "</p>";
}
?> 
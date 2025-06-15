<?php
// Basic test file to verify PHP is working
echo "<h1>PHP Test Page</h1>";
echo "<p>If you can see this, PHP is working correctly.</p>";
echo "<p>Current time: " . date("Y-m-d H:i:s") . "</p>";
echo "<p>Server information: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p>Document root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>URL path: " . $_SERVER['REQUEST_URI'] . "</p>";

// Check if we can connect to the database
echo "<h2>Database Test</h2>";
try {
    $pdo = new PDO("mysql:host=localhost;dbname=mock_test_app", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color:green'>Database connection successful!</p>";
    
    // List tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "<p>Tables in database: " . implode(", ", $tables) . "</p>";
} catch (PDOException $e) {
    echo "<p style='color:red'>Database connection failed: " . $e->getMessage() . "</p>";
}

// Navigation links
echo "<h2>Navigation Links</h2>";
echo "<ul>";
echo "<li><a href='index.php'>Home</a></li>";
echo "<li><a href='team.php'>Team</a></li>";
echo "<li><a href='login.php'>Login</a></li>";
echo "<li><a href='register.php'>Register</a></li>";
echo "</ul>";
?> 
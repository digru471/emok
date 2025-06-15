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
    
    // Create test user
    $name = "Test User";
    $email = "test@example.com";
    $plainPassword = "password123";
    $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
    
    // Check if user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "<p>User with email '$email' already exists (ID: {$user['id']})</p>";
        
        // Update password anyway
        $updateStmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $updateStmt->execute([$hashedPassword, $user['id']]);
        echo "<p>Password updated for this user</p>";
    } else {
        // Insert new user
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, is_admin) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $hashedPassword, 0]); // Regular user
        echo "<p>Created new test user:</p>";
        echo "<ul>";
        echo "<li>ID: " . $pdo->lastInsertId() . "</li>";
        echo "<li>Name: $name</li>";
        echo "<li>Email: $email</li>";
        echo "<li>Password: $plainPassword</li>";
        echo "</ul>";
    }
    
    // Create admin user if it doesn't exist
    $adminName = "Admin User";
    $adminEmail = "admin@example.com";
    $adminPassword = "admin123";
    $adminHashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$adminEmail]);
    $adminUser = $stmt->fetch();
    
    if ($adminUser) {
        echo "<p>Admin user with email '$adminEmail' already exists (ID: {$adminUser['id']})</p>";
        
        // Make sure it's an admin
        $updateStmt = $pdo->prepare("UPDATE users SET is_admin = 1, password = ? WHERE id = ?");
        $updateStmt->execute([$adminHashedPassword, $adminUser['id']]);
        echo "<p>Admin status and password updated</p>";
    } else {
        // Insert new admin user
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, is_admin) VALUES (?, ?, ?, ?)");
        $stmt->execute([$adminName, $adminEmail, $adminHashedPassword, 1]); // Admin user
        echo "<p>Created new admin user:</p>";
        echo "<ul>";
        echo "<li>ID: " . $pdo->lastInsertId() . "</li>";
        echo "<li>Name: $adminName</li>";
        echo "<li>Email: $adminEmail</li>";
        echo "<li>Password: $adminPassword</li>";
        echo "</ul>";
    }
    
    echo "<h3>Login Information</h3>";
    echo "<p>You can now login with:</p>";
    echo "<ul>";
    echo "<li><strong>Regular User:</strong> Email: $email, Password: $plainPassword</li>";
    echo "<li><strong>Admin User:</strong> Email: $adminEmail, Password: $adminPassword</li>";
    echo "</ul>";
    
    echo "<p><a href='login.php'>Go to Login Page</a></p>";
    
} catch (PDOException $e) {
    echo "<h2>Database Error:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?> 
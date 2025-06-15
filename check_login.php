<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session
session_start();

// Include database connection
require_once 'includes/config.php';

// Credentials from form or predefined
$email = "test@example.com";
$password = "password123";

echo "<h2>Login Test</h2>";
echo "<p>Attempting to login with:</p>";
echo "<ul>";
echo "<li>Email: $email</li>";
echo "<li>Password: $password</li>";
echo "</ul>";

// Attempt login
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "<p>User found with ID: {$user['id']}</p>";
        
        if (password_verify($password, $user['password'])) {
            echo "<p style='color:green;'>Password verified successfully!</p>";
            
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['is_admin'] = $user['is_admin'];
            
            echo "<p>Session variables set:</p>";
            echo "<pre>";
            print_r($_SESSION);
            echo "</pre>";
            
            echo "<p>Redirecting to dashboard in 3 seconds...</p>";
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'dashboard.php';
                }, 3000);
            </script>";
            echo "<p><a href='dashboard.php'>Click here if not redirected automatically</a></p>";
        } else {
            echo "<p style='color:red;'>Password verification failed!</p>";
        }
    } else {
        echo "<p style='color:red;'>User not found with email: $email</p>";
    }
} catch (PDOException $e) {
    echo "<p style='color:red;'>Database error: " . $e->getMessage() . "</p>";
}
?> 
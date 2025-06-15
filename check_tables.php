<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'mock_test_app';

// Connect to the database
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Database Table Check</h2>";

// Function to check table existence and data
function checkTable($conn, $tableName) {
    $result = $conn->query("SHOW TABLES LIKE '$tableName'");
    echo "<strong>Table: $tableName</strong><br>";
    
    if ($result->num_rows > 0) {
        echo "- Table exists<br>";
        
        // Check for records
        $count = $conn->query("SELECT COUNT(*) as count FROM $tableName");
        $row = $count->fetch_assoc();
        
        if ($row['count'] > 0) {
            echo "- Contains {$row['count']} records<br>";
            
            // Show sample data (first 3 rows)
            $data = $conn->query("SELECT * FROM $tableName LIMIT 3");
            echo "- Sample data:<br><pre>";
            while ($dataRow = $data->fetch_assoc()) {
                print_r($dataRow);
            }
            echo "</pre>";
            return true;
        } else {
            echo "- Table is empty<br>";
            return false;
        }
    } else {
        echo "- Table does not exist<br>";
        // Create the table
        $sql = "";
        switch ($tableName) {
            case 'users':
                $sql = "CREATE TABLE users (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(100) NOT NULL,
                    email VARCHAR(100) NOT NULL UNIQUE,
                    password VARCHAR(255) NOT NULL,
                    phone VARCHAR(20),
                    is_admin BOOLEAN DEFAULT FALSE,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )";
                break;
            case 'categories':
                $sql = "CREATE TABLE categories (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(100) NOT NULL,
                    description TEXT
                )";
                break;
            case 'subjects':
                $sql = "CREATE TABLE subjects (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    category_id INT,
                    name VARCHAR(100) NOT NULL,
                    description TEXT,
                    FOREIGN KEY (category_id) REFERENCES categories(id)
                )";
                break;
            case 'team_members':
                $sql = "CREATE TABLE team_members (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(100) NOT NULL,
                    role VARCHAR(100) NOT NULL,
                    bio TEXT,
                    image_path VARCHAR(255),
                    display_order INT
                )";
                break;
        }
        
        if (!empty($sql)) {
            if ($conn->query($sql)) {
                echo "- Table created successfully<br>";
            } else {
                echo "- Error creating table: " . $conn->error . "<br>";
            }
        }
        return false;
    }
    echo "<hr>";
}

// Function to insert sample data
function insertSampleData($conn, $tableName) {
    $sql = "";
    switch ($tableName) {
        case 'users':
            $sql = "INSERT INTO users (name, email, password, is_admin) VALUES
                ('Admin User', 'admin@example.com', '" . password_hash('admin123', PASSWORD_DEFAULT) . "', 1),
                ('Test User', 'user@example.com', '" . password_hash('user123', PASSWORD_DEFAULT) . "', 0)";
            break;
        case 'categories':
            $sql = "INSERT INTO categories (name, description) VALUES
                ('Engineering', 'Engineering entrance exams'),
                ('Medical', 'Medical entrance exams'),
                ('General', 'General knowledge tests')";
            break;
        case 'subjects':
            $sql = "INSERT INTO subjects (category_id, name, description) VALUES
                (1, 'Mathematics', 'Advanced mathematics for engineering'),
                (1, 'Physics', 'Physics for engineering entrance'),
                (2, 'Biology', 'Biology for medical entrance')";
            break;
        case 'team_members':
            $sql = "INSERT INTO team_members (name, role, bio, image_path, display_order) VALUES
                ('John Doe', 'teacher', 'Mathematics professor with 10 years of experience', 'asscts/images/team/john.jpg', 1),
                ('Jane Smith', 'developer', 'Lead developer with expertise in educational software', 'asscts/images/team/jane.jpg', 2),
                ('David Johnson', 'teacher', 'Physics expert specializing in competitive exams', 'asscts/images/team/david.jpg', 3)";
            break;
    }
    
    if (!empty($sql)) {
        if ($conn->query($sql)) {
            echo "- Sample data inserted successfully<br>";
            return true;
        } else {
            echo "- Error inserting sample data: " . $conn->error . "<br>";
            return false;
        }
    }
    return false;
}

// Check important tables and add sample data if empty
$tables = ['users', 'categories', 'subjects', 'team_members'];

foreach ($tables as $table) {
    $hasData = checkTable($conn, $table);
    
    if (!$hasData) {
        echo "Inserting sample data for $table:<br>";
        insertSampleData($conn, $table);
    }
    
    echo "<hr>";
}

$conn->close();
echo "Done!";
?> 
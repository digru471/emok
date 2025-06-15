<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'mock_test_app';

// Connect without database selection
$conn = new mysqli($host, $user, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully<br>";

// Check if database exists
$result = $conn->query("SHOW DATABASES LIKE '$database'");

if ($result->num_rows == 0) {
    // Create database
    if ($conn->query("CREATE DATABASE $database")) {
        echo "Database created successfully<br>";
    } else {
        echo "Error creating database: " . $conn->error . "<br>";
    }
} else {
    echo "Database already exists<br>";
}

// Select the database to use it
$conn->select_db($database);

// Import database schema and data
$sql = file_get_contents('db.sql');

if ($conn->multi_query($sql)) {
    echo "Database schema imported successfully<br>";
} else {
    echo "Error importing database schema: " . $conn->error . "<br>";
}

// Create team_members table if not exists
$conn->next_result(); // Move to the next result set

$createTeamTable = "
CREATE TABLE IF NOT EXISTS team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    role VARCHAR(100) NOT NULL,
    bio TEXT,
    image_path VARCHAR(255),
    display_order INT
)";

if ($conn->query($createTeamTable)) {
    echo "Team members table created or already exists<br>";
} else {
    echo "Error creating team members table: " . $conn->error . "<br>";
}

// Insert sample team members if table is empty
$result = $conn->query("SELECT COUNT(*) as count FROM team_members");
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    $insertTeamMembers = "
    INSERT INTO team_members (name, role, bio, image_path, display_order) VALUES
    ('John Doe', 'teacher', 'Mathematics professor with 10 years of experience', 'asscts/images/team/john.jpg', 1),
    ('Jane Smith', 'developer', 'Lead developer with expertise in educational software', 'asscts/images/team/jane.jpg', 2),
    ('David Johnson', 'teacher', 'Physics expert specializing in competitive exams', 'asscts/images/team/david.jpg', 3)
    ";
    
    if ($conn->query($insertTeamMembers)) {
        echo "Sample team members inserted successfully<br>";
    } else {
        echo "Error inserting sample team members: " . $conn->error . "<br>";
    }
}

$conn->close();
echo "Done!";
?> 
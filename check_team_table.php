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

echo "<h2>Team Members Table Structure</h2>";

// Check if table exists
$tableCheck = $conn->query("SHOW TABLES LIKE 'team_members'");
if ($tableCheck->num_rows == 0) {
    echo "Table team_members does not exist.<br>";
} else {
    echo "Table team_members exists.<br>";
    
    // Get column info
    $columns = $conn->query("SHOW COLUMNS FROM team_members");
    echo "<h3>Columns:</h3>";
    echo "<pre>";
    $columnNames = [];
    while ($col = $columns->fetch_assoc()) {
        print_r($col);
        $columnNames[] = $col['Field'];
    }
    echo "</pre>";
    
    // Insert sample data based on actual columns
    if (count($columnNames) > 0) {
        echo "<h3>Inserting sample data with existing columns</h3>";
        $sql = "INSERT INTO team_members (";
        $sql .= implode(", ", $columnNames);
        $sql .= ") VALUES ";
        
        // Add values based on column names
        $rows = [];
        for ($i = 1; $i <= 3; $i++) {
            $values = [];
            foreach ($columnNames as $column) {
                switch ($column) {
                    case 'id':
                        // Skip ID as it's auto-increment
                        break;
                    case 'name':
                        $values[] = "'Member $i'";
                        break;
                    case 'role':
                        $values[] = "'Teacher'";
                        break;
                    case 'image_path':
                        $values[] = "'asscts/images/team/default.jpg'";
                        break;
                    case 'display_order':
                        $values[] = $i;
                        break;
                    default:
                        $values[] = "'Sample data for $column'";
                        break;
                }
            }
            // Skip the ID column when building values
            $rows[] = "(" . implode(", ", $values) . ")";
        }
        $sql .= implode(", ", $rows);
        
        echo "<p>SQL: $sql</p>";
        if ($conn->query($sql)) {
            echo "<p>Sample data inserted successfully.</p>";
        } else {
            echo "<p>Error inserting data: " . $conn->error . "</p>";
        }
    }
}

$conn->close();
?> 
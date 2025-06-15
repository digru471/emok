<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Debug session info
echo "<div style='background:#f8f9fa; border:1px solid #ddd; padding:10px; margin:10px; font-family:monospace;'>";
echo "<h3>Session Debug Info:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
echo "</div>";

require_once 'includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<div style='color:red; background:#ffe6e6; border:1px solid #ff9999; padding:10px; margin:10px;'>";
    echo "<h3>Not Logged In</h3>";
    echo "<p>You are not logged in. Session data is missing.</p>";
    echo "<p><a href='login.php'>Go to login page</a></p>";
    echo "</div>";
    exit;
}

// Continue with normal dashboard
$pageTitle = "Dashboard - Debug Mode";

// Set the page-specific JS
$pageJS = 'dashboard.js';

// Include header
include 'includes/header.php';
?>

<div class="container">
    <div style="background:#e6ffe6; border:1px solid #99cc99; padding:10px; margin:10px;">
        <h3>Debug Mode Active</h3>
        <p>This is a debug version of the dashboard with extra information displayed.</p>
    </div>

    <div class="dashboard-header animate-on-scroll">
        <h2 class="fade-in">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h2>
        <div class="dashboard-actions slide-in-right">
            <a href="profile.php" class="btn btn-secondary">Profile</a>
            <a href="change-password.php" class="btn btn-secondary">Change Password</a>
        </div>
    </div>
    
    <?php
    // Debug database connection
    try {
        $stmt = $pdo->query("SELECT 1");
        echo "<div style='background:#e6ffe6; border:1px solid #99cc99; padding:10px; margin:10px;'>";
        echo "<p>Database connection successful</p>";
        echo "</div>";
    } catch (PDOException $e) {
        echo "<div style='color:red; background:#ffe6e6; border:1px solid #ff9999; padding:10px; margin:10px;'>";
        echo "<h3>Database Error</h3>";
        echo "<p>" . $e->getMessage() . "</p>";
        echo "</div>";
    }
    ?>
    
    <div class="dashboard-stats animate-on-scroll">
        <div class="stat-card slide-up">
            <h3>Tests Taken</h3>
            <p><?php 
                try {
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM test_attempts WHERE user_id = ?");
                    $stmt->execute([$_SESSION['user_id']]);
                    echo $stmt->fetchColumn();
                } catch (PDOException $e) {
                    echo "<span style='color:red;'>Error: " . $e->getMessage() . "</span>";
                }
            ?></p>
        </div>
        <div class="stat-card slide-up" style="animation-delay: 0.1s;">
            <h3>Average Score</h3>
            <p><?php 
                try {
                    $stmt = $pdo->prepare("SELECT AVG(score) FROM test_attempts WHERE user_id = ? AND completed_at IS NOT NULL");
                    $stmt->execute([$_SESSION['user_id']]);
                    echo round($stmt->fetchColumn()) . '%';
                } catch (PDOException $e) {
                    echo "<span style='color:red;'>Error: " . $e->getMessage() . "</span>";
                }
            ?></p>
        </div>
    </div>
    
    <section class="categories-section animate-on-scroll">
        <h3 class="slide-in-right">Test Categories</h3>
        <?php
        try {
            $categories = getCategories();
            if (empty($categories)) {
                echo "<div style='color:orange; background:#fff9e6; border:1px solid #ffcc80; padding:10px; margin:10px;'>";
                echo "<h3>No Categories</h3>";
                echo "<p>No categories were found in the database.</p>";
                echo "</div>";
            }
        } catch (Exception $e) {
            echo "<div style='color:red; background:#ffe6e6; border:1px solid #ff9999; padding:10px; margin:10px;'>";
            echo "<h3>Error retrieving categories</h3>";
            echo "<p>" . $e->getMessage() . "</p>";
            echo "</div>";
            $categories = [];
        }
        ?>
        <div class="category-grid">
            <?php 
            $delay = 0;
            foreach ($categories as $category): 
            ?>
                <div class="category-card slide-up" style="animation-delay: <?php echo $delay; ?>s;">
                    <h4><?php echo htmlspecialchars($category['name']); ?></h4>
                    <p><?php echo htmlspecialchars($category['description']); ?></p>
                    <a href="tests.php?category=<?php echo $category['id']; ?>" class="btn btn-primary">View Subjects</a>
                </div>
            <?php 
            $delay += 0.1;
            endforeach; 
            ?>
        </div>
    </section>
    
    <section class="recent-tests animate-on-scroll">
        <h3 class="slide-in-right">Recent Test Attempts</h3>
        <?php 
        try {
            $history = getUserTestHistory($_SESSION['user_id']);
        } catch (Exception $e) {
            echo "<div style='color:red; background:#ffe6e6; border:1px solid #ff9999; padding:10px; margin:10px;'>";
            echo "<h3>Error retrieving test history</h3>";
            echo "<p>" . $e->getMessage() . "</p>";
            echo "</div>";
            $history = [];
        }
        
        if (empty($history)):
        ?>
            <p>You haven't taken any tests yet.</p>
        <?php else: ?>
            <table class="test-history">
                <thead>
                    <tr>
                        <th>Test</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Score</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $rowDelay = 0;
                    foreach (array_slice($history, 0, 5) as $attempt): 
                    ?>
                        <tr class="slide-in-right" style="animation-delay: <?php echo $rowDelay; ?>s;">
                            <td><?php echo htmlspecialchars($attempt['title']); ?></td>
                            <td><?php echo htmlspecialchars($attempt['subject_name']); ?></td>
                            <td><?php echo date('M j, Y', strtotime($attempt['completed_at'])); ?></td>
                            <td><?php echo $attempt['score']; ?>%</td>
                            <td><a href="results.php?id=<?php echo $attempt['id']; ?>" class="btn btn-small">View Details</a></td>
                        </tr>
                    <?php 
                    $rowDelay += 0.05;
                    endforeach; 
                    ?>
                </tbody>
            </table>
            <div class="view-all">
                <a href="history.php" class="btn btn-secondary">View All History</a>
            </div>
        <?php endif; ?>
    </section>
</div>

<?php include 'includes/footer.php'; ?> 
<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include configuration
require_once 'includes/config.php';

// Check if user is logged in
requireLogin();

// Set page title and JS
$pageTitle = "Dashboard";
$pageCSS = 'user.css';
$pageJS = 'user.js';

// Include header
include 'includes/header.php';
?>

<div class="container">
    <div class="dashboard-header animate-on-scroll">
        <h2 class="fade-in">Welcome, <?php echo htmlspecialchars(isset($_SESSION['name']) ? $_SESSION['name'] : 'User'); ?></h2>
        <div class="dashboard-actions slide-in-right">
            <a href="profile.php" class="btn btn-secondary">Profile</a>
            <a href="change-password.php" class="btn btn-secondary">Change Password</a>
        </div>
    </div>
    
    <div class="dashboard-stats animate-on-scroll">
        <div class="stat-card slide-up">
            <h3>Tests Taken</h3>
            <p><?php 
                try {
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM test_attempts WHERE user_id = ?");
                    $stmt->execute([$_SESSION['user_id']]);
                    echo $stmt->fetchColumn();
                } catch (PDOException $e) {
                    echo "0";
                    error_log("Error counting test attempts: " . $e->getMessage());
                }
            ?></p>
        </div>
        <div class="stat-card slide-up" style="animation-delay: 0.1s;">
            <h3>Average Score</h3>
            <p><?php 
                try {
                    $stmt = $pdo->prepare("SELECT AVG(score) FROM test_attempts WHERE user_id = ? AND completed_at IS NOT NULL");
                    $stmt->execute([$_SESSION['user_id']]);
                    $avg = $stmt->fetchColumn();
                    echo $avg ? round($avg) . '%' : '0%';
                } catch (PDOException $e) {
                    echo "0%";
                    error_log("Error calculating average score: " . $e->getMessage());
                }
            ?></p>
        </div>
    </div>
    
    <section class="categories-section animate-on-scroll">
        <h3 class="slide-in-right">Test Categories</h3>
        <div class="category-grid">
            <?php 
            try {
                $categories = getCategories();
                if (empty($categories)) {
                    echo "<p>No test categories available yet.</p>";
                } else {
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
                }
            } catch (Exception $e) {
                echo "<p>Error loading categories: " . htmlspecialchars($e->getMessage()) . "</p>";
                error_log("Error loading categories: " . $e->getMessage());
            }
            ?>
        </div>
    </section>
    
    <section class="recent-tests animate-on-scroll">
        <h3 class="slide-in-right">Recent Test Attempts</h3>
        <?php 
        try {
            $history = getUserTestHistory($_SESSION['user_id']);
            if (empty($history)):
            ?>
                <p class="no-data">You haven't taken any tests yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="test-history" id="attempts-table">
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
                                <tr class="slide-in-right" style="animation-delay: <?php echo $rowDelay; ?>s;" data-attempt-id="<?php echo $attempt['id']; ?>">
                                    <td><?php echo htmlspecialchars($attempt['title']); ?></td>
                                    <td><?php echo htmlspecialchars($attempt['subject_name']); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($attempt['completed_at'])); ?></td>
                                    <td data-score="<?php echo $attempt['score']; ?>"><?php echo $attempt['score']; ?>%</td>
                                    <td><a href="results.php?id=<?php echo $attempt['id']; ?>" class="btn-view">View Details</a></td>
                                </tr>
                            <?php 
                            $rowDelay += 0.05;
                            endforeach; 
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="view-all">
                    <a href="history.php" class="btn btn-secondary">View All History</a>
                </div>
            <?php endif;
        } catch (Exception $e) {
            echo "<p>Error loading test history: " . htmlspecialchars($e->getMessage()) . "</p>";
            error_log("Error loading test history: " . $e->getMessage());
        }
        ?>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
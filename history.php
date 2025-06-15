<?php
require_once 'includes/config.php';
requireLogin();

$pageTitle = "Test History";
$pageCSS = "user.css";
$pageJS = "user.js";
include 'includes/header.php';

// Get all test attempts for the current user
$history = getUserTestHistory($_SESSION['user_id']);

// Handle test attempt deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $attemptId = (int)$_GET['delete'];
    
    // Verify this attempt belongs to the current user
    $stmt = $pdo->prepare("SELECT id FROM test_attempts WHERE id = ? AND user_id = ?");
    $stmt->execute([$attemptId, $_SESSION['user_id']]);
    
    if ($stmt->fetch()) {
        // Delete attempt answers first
        $pdo->prepare("DELETE FROM user_answers WHERE attempt_id = ?")->execute([$attemptId]);
        
        // Then delete the attempt
        if ($pdo->prepare("DELETE FROM test_attempts WHERE id = ?")->execute([$attemptId])) {
            $_SESSION['message'] = "Test attempt deleted successfully";
            $_SESSION['message_type'] = "success";
            header("Location: history.php");
            exit();
        }
    }
    
    $_SESSION['message'] = "Error deleting test attempt";
    $_SESSION['message_type'] = "danger";
    header("Location: history.php");
    exit();
}
?>

<style>
/* Reset and base styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html {
    height: 100%;
    position: relative;
}

body {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    position: relative;
}

.container {
    flex: 1 0 auto;
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

footer {
    width: 100%;
    background-color: var(--dark);
    color: white;
    padding: 3rem 0 1rem;
    flex-shrink: 0;
}

/* History specific styles */
.history-header {
    margin-bottom: 2rem;
}

.history-table {
    width: 100%;
    overflow-x: auto;
    margin-bottom: 2rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.history-table table {
    width: 100%;
    border-collapse: collapse;
    min-width: 800px;
}

.history-table th {
    background: var(--light);
    color: var(--text);
    font-weight: 600;
    text-align: left;
    padding: 1rem;
    border-bottom: 2px solid var(--border);
}

.history-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border);
    color: var(--text);
}

.history-table tr:last-child td {
    border-bottom: none;
}

.history-table tr:hover {
    background: var(--light);
}

.score {
    font-weight: 600;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.9rem;
}

.score.pass {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.score.fail {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.status {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
}

.status.completed {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.status.incomplete {
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
}

.actions {
    display: flex;
    gap: 0.5rem;
}

.btn-view {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-view i {
    font-size: 0.9rem;
}

.btn-view:not(.btn-danger) {
    background: var(--primary);
    color: white;
}

.btn-view:not(.btn-danger):hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
}

.btn-danger {
    background: var(--danger);
    color: white;
}

.btn-danger:hover {
    background: var(--danger-dark);
    transform: translateY(-2px);
}

.history-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-card h3 {
    color: var(--text-muted);
    font-size: 1rem;
    margin-bottom: 0.5rem;
}

.stat-value {
    font-size: 2rem;
    font-weight: 600;
    color: var(--primary);
}

.no-history {
    text-align: center;
    padding: 3rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.no-history p {
    margin-bottom: 1rem;
    color: var(--text-muted);
}

/* Animation classes */
.animate-on-scroll {
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.6s ease;
}

.animate-on-scroll.visible {
    opacity: 1;
    transform: translateY(0);
}

.slide-up {
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.6s ease;
}

.slide-up.visible {
    opacity: 1;
    transform: translateY(0);
}
</style>

<div class="container">
    <div class="history-header">
        <h2>Your Test History</h2>
        <div class="history-actions">
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type']; ?>">
            <?php echo $_SESSION['message']; unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (empty($history)): ?>
        <div class="no-history">
            <p>You haven't taken any tests yet.</p>
            <a href="tests.php" class="btn btn-primary">Browse Tests</a>
        </div>
    <?php else: ?>
        <div class="history-table">
            <table>
                <thead>
                    <tr>
                        <th>Test</th>
                        <th>Subject</th>
                        <th>Date Taken</th>
                        <th>Score</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history as $attempt): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($attempt['title']); ?></td>
                            <td><?php echo htmlspecialchars($attempt['subject_name']); ?></td>
                            <td>
                                <?php if ($attempt['completed_at']): ?>
                                    <?php echo date('M j, Y g:i a', strtotime($attempt['completed_at'])); ?>
                                <?php else: ?>
                                    Incomplete
                                <?php endif; ?>
                            </td>
                            <td data-score="<?php echo $attempt['score']; ?>">
                                <?php if ($attempt['score'] !== null): ?>
                                    <span class="score <?php echo ($attempt['score'] >= ($attempt['passing_score'] ?? 70)) ? 'pass' : 'fail'; ?>">
                                        <?php echo $attempt['score']; ?>%
                                    </span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($attempt['completed_at']): ?>
                                    <span class="status completed">Completed</span>
                                <?php else: ?>
                                    <span class="status incomplete">Incomplete</span>
                                <?php endif; ?>
                            </td>
                            <td class="actions">
                                <?php if ($attempt['completed_at']): ?>
                                    <a href="results.php?id=<?php echo $attempt['id']; ?>" class="btn-view">
                                        <i class="fas fa-chart-bar"></i> View Results
                                    </a>
                                <?php else: ?>
                                    <a href="take-test.php?id=<?php echo $attempt['test_id']; ?>" class="btn-view">
                                        <i class="fas fa-play"></i> Continue
                                    </a>
                                <?php endif; ?>
                                <a href="history.php?delete=<?php echo $attempt['id']; ?>" 
                                   class="btn-view btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this test attempt?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="history-stats animate-on-scroll">
            <div class="stat-card slide-up">
                <h3>Average Score</h3>
                <p class="stat-value">
                    <?php 
                    $stmt = $pdo->prepare("SELECT AVG(score) FROM test_attempts WHERE user_id = ? AND completed_at IS NOT NULL");
                    $stmt->execute([$_SESSION['user_id']]);
                    echo round($stmt->fetchColumn()) . '%';
                    ?>
                </p>
            </div>
            
            <div class="stat-card slide-up" style="animation-delay: 0.1s;">
                <h3>Tests Completed</h3>
                <p class="stat-value">
                    <?php 
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM test_attempts WHERE user_id = ? AND completed_at IS NOT NULL");
                    $stmt->execute([$_SESSION['user_id']]);
                    echo $stmt->fetchColumn();
                    ?>
                </p>
            </div>
            
            <div class="stat-card slide-up" style="animation-delay: 0.2s;">
                <h3>Pass Rate</h3>
                <p class="stat-value">
                    <?php 
                    $stmt = $pdo->prepare("SELECT 
                        (COUNT(CASE WHEN score >= COALESCE((SELECT passing_score FROM tests WHERE id = test_id LIMIT 1), 70) THEN 1 END) * 100.0 / 
                        COUNT(*)) AS pass_rate
                        FROM test_attempts 
                        WHERE user_id = ? AND completed_at IS NOT NULL");
                    $stmt->execute([$_SESSION['user_id']]);
                    echo round($stmt->fetchColumn()) . '%';
                    ?>
                </p>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
// Intersection Observer for scroll animations
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
        }
    });
}, {
    threshold: 0.1
});

// Observe all elements with animation classes
document.querySelectorAll('.animate-on-scroll, .slide-up').forEach((el) => observer.observe(el));

// Add hover effect to table rows
document.querySelectorAll('.history-table tbody tr').forEach(row => {
    row.addEventListener('mouseenter', () => {
        row.style.transform = 'translateX(5px)';
        row.style.transition = 'transform 0.3s ease';
    });
    
    row.addEventListener('mouseleave', () => {
        row.style.transform = 'translateX(0)';
    });
});

// Add confirmation for delete action
document.querySelectorAll('.btn-danger').forEach(btn => {
    btn.addEventListener('click', (e) => {
        if (!confirm('Are you sure you want to delete this test attempt?')) {
            e.preventDefault();
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>
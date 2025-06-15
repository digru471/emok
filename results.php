<?php 
require_once 'includes/config.php';
requireLogin();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: history.php");
    exit();
}

$attemptId = $_GET['id'];
$attempt = getAttemptDetails($attemptId);

// Verify this attempt belongs to the current user (unless admin)
if (!$attempt || ($attempt['user_id'] != $_SESSION['user_id'] && !isAdmin())) {
    header("Location: history.php");
    exit();
}

$answers = getAttemptAnswers($attemptId);
$correctCount = 0;
foreach ($answers as $answer) {
    if ($answer['is_correct']) {
        $correctCount++;
    }
}
$totalQuestions = count($answers);

// Set page title
$pageTitle = "Test Results";
$pageCSS = 'user.css';
$pageJS = 'user.js';
?>

<?php include 'includes/header.php'; ?>

<div class="container">
    <div class="result-header">
        <h2>Test Results: <?php echo htmlspecialchars($attempt['title']); ?></h2>
        <div class="result-summary">
            <div class="score-card">
                <h3>Your Score</h3>
                <div class="score-display <?php echo ($attempt['score'] >= ($attempt['passing_score'] ?? 70)) ? 'pass' : 'fail'; ?>">
                    <?php echo $attempt['score']; ?>%
                </div>
                <p><?php echo $correctCount; ?> out of <?php echo $totalQuestions; ?> correct</p>
            </div>
            
            <div class="result-details">
                <p><strong>Subject:</strong> <?php echo htmlspecialchars($attempt['subject_name']); ?></p>
                <p><strong>Date Taken:</strong> <?php echo date('F j, Y g:i a', strtotime($attempt['completed_at'])); ?></p>
                <?php if ($attempt['time_limit']): ?>
                    <p><strong>Time Limit:</strong> <?php echo $attempt['time_limit']; ?> minutes</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="result-breakdown">
        <h3>Question Breakdown</h3>
        
        <?php foreach ($answers as $index => $answer): ?>
            <div class="question-result <?php echo $answer['is_correct'] ? 'correct' : 'incorrect'; ?>">
                <div class="question-header">
                    <h4>Question <?php echo $index + 1; ?></h4>
                    <span class="result-status">
                        <?php if ($answer['is_correct']): ?>
                            <i class="fas fa-check-circle"></i> Correct
                        <?php else: ?>
                            <i class="fas fa-times-circle"></i> Incorrect
                        <?php endif; ?>
                    </span>
                </div>
                
                <p class="question-text"><?php echo htmlspecialchars($answer['question_text']); ?></p>
                
                <div class="answer-comparison">
                    <div class="your-answer">
                        <h5>Your Answer</h5>
                        <p class="<?php echo $answer['is_correct'] ? 'correct-text' : 'incorrect-text'; ?>"><?php 
                            if ($answer['selected_answer']) {
                                $selectedOption = 'option_' . strtolower($answer['selected_answer']);
                                echo htmlspecialchars($answer[$selectedOption]);
                            } else {
                                echo "<em>No answer selected</em>";
                            }
                        ?></p>
                    </div>
                    
                    <div class="correct-answer">
                        <h5>Correct Answer</h5>
                        <p class="correct-text"><?php 
                            $correctOption = 'option_' . strtolower($answer['correct_answer']);
                            echo htmlspecialchars($answer[$correctOption]);
                        ?></p>
                    </div>
                </div>
                
                <?php if (!empty($answer['explanation'])): ?>
                    <div class="explanation">
                        <h5><i class="fas fa-info-circle"></i> Explanation</h5>
                        <p><?php echo htmlspecialchars($answer['explanation']); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="result-actions">
        <a href="take-test.php?id=<?php echo $attempt['test_id']; ?>" class="btn btn-primary">Retake Exam</a>
        <a href="tests.php" class="btn btn-secondary">Take Another Test</a>
        <a href="history.php" class="btn btn-secondary">View All Results</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
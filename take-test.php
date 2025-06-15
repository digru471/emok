<?php 
require_once 'includes/config.php';
requireLogin();

// Remove pageJS variable as we're adding the script inline
// $pageJS = 'test.js';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: tests.php");
    exit();
}

$testId = $_GET['id'];
$test = getTest($testId);

if (!$test) {
    header("Location: tests.php");
    exit();
}

// Check for existing incomplete attempt or start new one
$stmt = $pdo->prepare("SELECT id FROM test_attempts WHERE user_id = ? AND test_id = ? AND completed_at IS NULL");
$stmt->execute([$_SESSION['user_id'], $testId]);
$attempt = $stmt->fetch();

if ($attempt) {
    $attemptId = $attempt['id'];
} else {
    $attemptId = startTestAttempt($_SESSION['user_id'], $testId);
}

$questions = getTestQuestions($testId);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score = submitTestAnswers($attemptId, $_POST['answers']);
    header("Location: results.php?id=" . $attemptId);
    exit();
}
?>

<?php include 'includes/header.php'; ?>

<style>
    /* Test-specific inline CSS with Livi theme styling - using !important to ensure they take precedence */
    .test-header {
        background-color: white !important;
        padding: 2rem !important;
        border-radius: 8px !important;
        margin-bottom: 2rem !important;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
        border-top: 4px solid #e91e63 !important;
    }
    
    .test-header h2 {
        color: #e91e63 !important;
        margin-bottom: 1rem !important;
        font-weight: 600 !important;
    }
    
    .test-info {
        display: flex !important;
        justify-content: space-between !important;
        margin-bottom: 1rem !important;
    }
    
    .test-info p {
        background-color: #f8bbd0 !important;
        color: #c2185b !important;
        padding: 0.5rem 1rem !important;
        border-radius: 20px !important;
        font-weight: 500 !important;
    }
    
    #timer {
        background-color: #f8f9fa !important;
        padding: 0.75rem 1rem !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        color: #6a1b9a !important;
        text-align: center !important;
        margin-top: 1rem !important;
        border: 1px solid #dee2e6 !important;
    }
    
    #time-display {
        color: #e91e63 !important;
        font-size: 1.2rem !important;
    }
    
    .question-card {
        background-color: white !important;
        border-radius: 8px !important;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
        margin-bottom: 2rem !important;
        overflow: hidden !important;
        border-left: 4px solid #e91e63 !important;
        transition: transform 0.3s ease !important;
    }
    
    .question-card:hover {
        transform: translateY(-5px) !important;
    }
    
    .question-text {
        padding: 1.5rem !important;
        border-bottom: 1px solid #dee2e6 !important;
        background-color: #f8f9fa !important;
    }
    
    .question-text h3 {
        color: #e91e63 !important;
        margin-bottom: 0.5rem !important;
        font-weight: 600 !important;
    }
    
    .question-text p {
        font-size: 1.1rem !important;
        color: #333 !important;
    }
    
    .question-options {
        padding: 1.5rem !important;
    }
    
    .option {
        display: flex !important;
        align-items: center !important;
        padding: 1rem !important;
        margin-bottom: 1rem !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 8px !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
    }
    
    .option:hover {
        background-color: rgba(233, 30, 99, 0.05) !important;
        border-color: #e91e63 !important;
    }
    
    .option input[type="radio"] {
        margin-right: 10px !important;
        cursor: pointer !important;
    }
    
    .option-letter {
        background-color: #f8bbd0 !important;
        color: #c2185b !important;
        width: 30px !important;
        height: 30px !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        margin-right: 15px !important;
        font-weight: 600 !important;
    }
    
    .option-text {
        flex: 1 !important;
    }
    
    .test-actions {
        display: flex !important;
        justify-content: center !important;
        margin-top: 2rem !important;
        margin-bottom: 3rem !important;
    }
    
    .test-actions button {
        padding: 1rem 3rem !important;
        font-size: 1.1rem !important;
        font-weight: 600 !important;
        letter-spacing: 0.5px !important;
    }
    
    @media (max-width: 768px) {
        .test-info {
            flex-direction: column !important;
            gap: 0.5rem !important;
        }
        
        .option {
            padding: 0.75rem !important;
        }
        
        .option-letter {
            width: 25px !important;
            height: 25px !important;
            font-size: 0.9rem !important;
        }
    }
</style>

<div class="container">
    <div class="test-header">
        <h2><?php echo htmlspecialchars($test['title']); ?></h2>
        <div class="test-info">
            <p>Total Questions: <?php echo count($questions); ?></p>
            <p>Time Limit: <?php echo $test['time_limit'] ? $test['time_limit'] . ' minutes' : 'No time limit'; ?></p>
        </div>
        <div id="timer" data-limit="<?php echo $test['time_limit'] * 60; ?>">
            <?php if ($test['time_limit']): ?>
                Time remaining: <span id="time-display"><?php echo gmdate("H:i:s", $test['time_limit'] * 60); ?></span>
            <?php endif; ?>
        </div>
    </div>
    
    <form id="test-form" method="POST">
        <?php foreach ($questions as $index => $question): ?>
            <div class="question-card" id="q<?php echo $question['id']; ?>">
                <div class="question-text">
                    <h3>Question <?php echo $index + 1; ?></h3>
                    <p><?php echo htmlspecialchars($question['question_text']); ?></p>
                </div>
                
                <div class="question-options">
                    <label class="option">
                        <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="a">
                        <span class="option-letter">A</span>
                        <span class="option-text"><?php echo htmlspecialchars($question['option_a']); ?></span>
                    </label>
                    
                    <label class="option">
                        <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="b">
                        <span class="option-letter">B</span>
                        <span class="option-text"><?php echo htmlspecialchars($question['option_b']); ?></span>
                    </label>
                    
                    <label class="option">
                        <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="c">
                        <span class="option-letter">C</span>
                        <span class="option-text"><?php echo htmlspecialchars($question['option_c']); ?></span>
                    </label>
                    
                    <label class="option">
                        <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="d">
                        <span class="option-letter">D</span>
                        <span class="option-text"><?php echo htmlspecialchars($question['option_d']); ?></span>
                    </label>
                </div>
            </div>
        <?php endforeach; ?>
        
        <div class="test-actions">
            <button type="submit" class="btn btn-primary">Submit Test</button>
        </div>
    </form>
</div>

<!-- Add timer functionality directly inline -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const timerElement = document.getElementById('timer');
    if (!timerElement) return;
    
    const timeLimit = parseInt(timerElement.dataset.limit);
    if (isNaN(timeLimit) || timeLimit <= 0) return;
    
    let timeLeft = timeLimit;
    const timeDisplay = document.getElementById('time-display');
    
    function formatTime(seconds) {
        const hrs = Math.floor(seconds / 3600);
        const mins = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        
        return [
            hrs.toString().padStart(2, '0'),
            mins.toString().padStart(2, '0'),
            secs.toString().padStart(2, '0')
        ].join(':');
    }
    
    if (timeDisplay) {
        timeDisplay.textContent = formatTime(timeLeft);
        
        const countdown = setInterval(function() {
            timeLeft--;
            timeDisplay.textContent = formatTime(timeLeft);
            
            if (timeLeft <= 0) {
                clearInterval(countdown);
                alert('Time is up! Your test will be submitted automatically.');
                document.getElementById('test-form').submit();
            }
        }, 1000);
        
        // Warn before leaving the page
        window.addEventListener('beforeunload', function(e) {
            if (timeLeft > 0) {
                e.preventDefault();
                e.returnValue = 'You have a test in progress. Are you sure you want to leave?';
                return e.returnValue;
            }
        });
    }
});
</script>

<?php include 'includes/footer.php'; ?>
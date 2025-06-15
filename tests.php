<?php
require_once 'includes/config.php';
requireLogin();

$pageTitle = "Available Tests";
include 'includes/header.php';

// Get category ID if specified
$categoryId = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$subjectId = isset($_GET['subject']) ? (int)$_GET['subject'] : 0;

// Get all categories
$categories = getCategories();

// Get subjects based on category filter
if ($categoryId > 0) {
    $subjects = getSubjectsByCategory($categoryId);
} else {
    $subjects = $pdo->query("SELECT * FROM subjects ORDER BY name")->fetchAll();
}

// Get tests based on subject filter
if ($subjectId > 0) {
    $tests = getTestsBySubject($subjectId);
} else {
    $tests = $pdo->query("SELECT t.*, s.name AS subject_name,
                         (SELECT COUNT(*) FROM questions WHERE test_id = t.id) as question_count
                         FROM tests t
                         JOIN subjects s ON t.subject_id = s.id
                         WHERE t.is_active = 1
                         ORDER BY s.name, t.title")->fetchAll();
}

// Get user's test history for completion status
$stmt = $pdo->prepare("SELECT test_id FROM test_attempts WHERE user_id = ? AND completed_at IS NOT NULL");
$stmt->execute([$_SESSION['user_id']]);
$completedTests = $stmt->fetchAll(PDO::FETCH_COLUMN);
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

/* Tests specific styles */
.tests-header {
    margin-bottom: 2rem;
}

.test-filters {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.filter-form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.form-group {
    margin-bottom: 0;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: var(--text-muted);
    font-weight: 500;
}

.form-group select {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border);
    border-radius: 6px;
    background-color: white;
    color: var(--text);
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-group select:focus {
    border-color: var(--primary);
    outline: none;
    box-shadow: 0 0 0 3px rgba(180, 82, 228, 0.1);
}

.tests-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.test-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s ease;
}

.test-card:hover {
    transform: translateY(-5px);
}

.test-card-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border);
}

.test-card-header h3 {
    margin: 0;
    color: var(--text);
    font-size: 1.25rem;
}

.test-card-header .subject {
    color: var(--text-muted);
    font-size: 0.9rem;
    margin-top: 0.5rem;
}

.test-card-body {
    padding: 1.5rem;
}

.test-card-footer {
    padding: 1rem 1.5rem;
    background: var(--light);
    border-top: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.test-info {
    display: flex;
    gap: 1rem;
    color: var(--text-muted);
    font-size: 0.9rem;
}

.test-info span {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.test-info i {
    color: var(--primary);
}

.btn-start {
    background: var(--primary);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-start:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
}

.btn-continue {
    background: var(--warning);
    color: var(--text);
}

.btn-continue:hover {
    background: var(--warning-dark);
}

.btn-view {
    background: var(--info);
    color: white;
}

.btn-view:hover {
    background: var(--info-dark);
}

.no-tests {
    text-align: center;
    padding: 3rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.no-tests p {
    margin-bottom: 1rem;
    color: var(--text-muted);
}
</style>

<div class="container">
    <div class="tests-header">
        <h2>Available Tests</h2>
        <div class="test-filters">
            <form method="GET" class="filter-form">
                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category">
                        <option value="0">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>"
                                <?php if ($categoryId == $category['id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <select id="subject" name="subject">
                        <option value="0">All Subjects</option>
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?php echo $subject['id']; ?>"
                                <?php if ($subjectId == $subject['id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($subject['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>
    
    <?php if ($categoryId > 0 && $subjectId === 0): /* Display subjects for the selected category */ ?>
        <div class="section-title">
            <h2>Subjects in <?php echo htmlspecialchars($categories[array_search($categoryId, array_column($categories, 'id'))]['name'] ?? 'Selected Category'); ?></h2>
            <p>Select a subject to view available tests.</p>
        </div>
        <div class="category-grid">
            <?php if (empty($subjects)): ?>
                <p class="no-tests">No subjects found for this category.</p>
            <?php else: ?>
                <?php foreach ($subjects as $subject): ?>
                    <div class="category-card">
                        <h4><?php echo htmlspecialchars($subject['name']); ?></h4>
                        <p><?php echo htmlspecialchars($subject['description'] ?? 'No description available.'); ?></p>
                        <a href="tests.php?category=<?php echo $categoryId; ?>&subject=<?php echo $subject['id']; ?>" class="btn btn-primary">View Tests</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php else: /* Display tests based on filters or all tests */ ?>
        <?php if (empty($tests)): ?>
            <div class="no-tests">
                <p>No tests available for the selected filters.</p>
                <a href="tests.php" class="btn btn-primary">View All Tests</a>
            </div>
        <?php else: ?>
            <div class="tests-grid">
                <?php foreach ($tests as $test): ?>
                    <div class="test-card">
                        <div class="test-card-header">
                            <h3><?php echo htmlspecialchars($test['title']); ?></h3>
                            <div class="subject"><?php echo htmlspecialchars($test['subject_name']); ?></div>
                        </div>
                        
                        <div class="test-card-body">
                            <p><?php echo htmlspecialchars($test['description']); ?></p>
                        </div>
                        
                        <div class="test-card-footer">
                            <div class="test-info">
                                <?php if ($test['time_limit']): ?>
                                    <span><i class="fas fa-clock"></i> <?php echo $test['time_limit']; ?> mins</span>
                                <?php endif; ?>
                                <span><i class="fas fa-question-circle"></i> <?php echo $test['question_count']; ?> questions</span>
                            </div>
                            
                            <?php if (in_array($test['id'], $completedTests)): ?>
                                <a href="results.php?test_id=<?php echo $test['id']; ?>" class="btn-start btn-view">
                                    <i class="fas fa-chart-bar"></i> View Results
                                </a>
                            <?php else: ?>
                                <a href="take-test.php?id=<?php echo $test['id']; ?>" class="btn-start">
                                    <i class="fas fa-play"></i> Start Test
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script>
// Get dropdown elements
const categorySelect = document.getElementById('category');
const subjectSelect = document.getElementById('subject');

let isInitialLoadDone = false; // Flag to control redirects

// Function to handle redirects after user interaction
function handleDropdownChange(event) {
    if (isInitialLoadDone) {
        const categoryId = categorySelect.value;
        const subjectId = subjectSelect.value;

        if (event.currentTarget === categorySelect) { // Category changed
            window.location.href = `tests.php?category=${categoryId}`;
        } else if (event.currentTarget === subjectSelect) { // Subject changed
            if (subjectId > 0) {
                window.location.href = `tests.php?category=${categoryId}&subject=${subjectId}`;
            } else {
                window.location.href = `tests.php?category=${categoryId}`;
            }
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const categoryId = urlParams.get('category');
    const subjectId = urlParams.get('subject');

    if (categoryId) {
        // Fetch subjects via AJAX for the pre-selected category
        fetch(`api/get-subjects.php?category_id=${categoryId}`)
            .then(response => response.json())
            .then(subjects => {
                subjectSelect.innerHTML = '<option value="0">All Subjects</option>';
                subjects.forEach(subject => {
                    const option = document.createElement('option');
                    option.value = subject.id;
                    option.textContent = subject.name;
                    subjectSelect.appendChild(option);
                });

                if (subjectId) {
                    subjectSelect.value = subjectId;
                }
                // Set flag to true AFTER subjects are populated and subject is selected
                isInitialLoadDone = true;

                // Attach listeners ONLY after initial load is complete
                categorySelect.addEventListener('change', handleDropdownChange);
                subjectSelect.addEventListener('change', handleDropdownChange);
            })
            .catch(error => {
                console.error('Error fetching subjects:', error);
                // Set flag even on error to allow user interaction, and attach listeners
                isInitialLoadDone = true;
                categorySelect.addEventListener('change', handleDropdownChange);
                subjectSelect.addEventListener('change', handleDropdownChange);
            });
    } else {
        // If no categoryId, initial load is complete directly
        isInitialLoadDone = true;

        // Attach listeners ONLY after initial load is complete
        categorySelect.addEventListener('change', handleDropdownChange);
        subjectSelect.addEventListener('change', handleDropdownChange);
    }
});
</script>

<?php include 'includes/footer.php'; ?> 

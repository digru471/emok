<?php
require_once 'includes/config.php';
requireLogin();

$pageTitle = "User Profile";
include 'includes/header.php';

// Get current user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    
    // Validate inputs
    if (empty($name) || empty($email)) {
        $errors[] = "Name and email are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    } else {
        // Check if email exists (except current user)
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $_SESSION['user_id']]);
        
        if ($stmt->rowCount() > 0) {
            $errors[] = "Email already in use by another account";
        } else {
            // Update profile
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?");
            if ($stmt->execute([$name, $email, $phone, $_SESSION['user_id']])) {
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;
                $success = "Profile updated successfully";
            } else {
                $errors[] = "Error updating profile";
            }
        }
    }
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

/* Profile specific styles */
.profile-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border);
}

.profile-header h2 {
    color: var(--heading-color);
    margin: 0;
}

.profile-actions .btn-secondary {
    background-color: var(--secondary);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    transition: background-color 0.3s ease;
}

.profile-actions .btn-secondary:hover {
    background-color: var(--secondary-dark);
}

.alert {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    font-weight: 500;
}

.alert-danger {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
    border: 1px solid #dc3545;
}

.alert-success {
    background-color: rgba(40, 167, 69, 0.1);
    color: #28a745;
    border: 1px solid #28a745;
}

.alert ul {
    margin: 0;
    padding-left: 20px;
}

.profile-form {
    background: white;
    padding: 2rem; /* Reduced padding */
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: var(--text-muted);
    font-weight: 500;
    font-size: 0.95rem;
}

.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="tel"] {
    width: 100%;
    padding: 0.9rem 2rem; /* Increased horizontal padding */
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 1rem;
    color: var(--text);
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-group input[type="text"]:focus,
.form-group input[type="email"]:focus,
.form-group input[type="tel"]:focus {
    border-color: var(--primary);
    outline: none;
    box-shadow: 0 0 0 3px rgba(180, 82, 228, 0.1);
}

.form-actions {
    padding-top: 1rem;
    border-top: 1px solid var(--border);
    margin-top: 2rem;
    text-align: right;
}

.btn-primary {
    background: var(--primary);
    color: white;
    padding: 0.9rem 2rem;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1.05rem;
    font-weight: 600;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
}

.profile-stats h3 {
    color: var(--heading-color);
    margin-bottom: 1.5rem;
    text-align: center;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.stat-card {
    background: white;
    padding: 2.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-card h4 {
    color: var(--text-muted);
    font-size: 1rem;
    margin-bottom: 0.5rem;
}

.stat-card p {
    font-size: 2rem;
    font-weight: 600;
    color: var(--primary);
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
    <div class="profile-header">
        <h2>User Profile</h2>
        <div class="profile-actions">
            <a href="change-password.php" class="btn btn-secondary">Change Password</a>
        </div>
    </div>
    
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <?php if (isset($success)): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>
    
    <div class="profile-form animate-on-scroll">
        <form method="POST" action="profile.php">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required 
                       value="<?php echo htmlspecialchars($user['name']); ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required 
                       value="<?php echo htmlspecialchars($user['email']); ?>">
            </div>
            
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" 
                       value="<?php echo htmlspecialchars($user['phone']); ?>">
            </div>
            
            <div class="form-actions">
                <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
            </div>
        </form>
    </div>
    
    <div class="profile-stats animate-on-scroll">
        <h3>Your Statistics</h3>
        <div class="stats-grid">
            <div class="stat-card slide-up">
                <h4>Tests Taken</h4>
                <p><?php 
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM test_attempts WHERE user_id = ?");
                    $stmt->execute([$_SESSION['user_id']]);
                    echo $stmt->fetchColumn();
                ?></p>
            </div>
            
            <div class="stat-card slide-up" style="animation-delay: 0.1s;">
                <h4>Average Score</h4>
                <p><?php 
                    $stmt = $pdo->prepare("SELECT AVG(score) FROM test_attempts WHERE user_id = ? AND completed_at IS NOT NULL");
                    $stmt->execute([$_SESSION['user_id']]);
                    $avgScore = $stmt->fetchColumn();
                    echo $avgScore !== null ? round($avgScore) . '%' : '0%';
                ?></p>
            </div>
            
            <div class="stat-card slide-up" style="animation-delay: 0.2s;">
                <h4>Member Since</h4>
                <p><?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
            </div>
        </div>
    </div>
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

// Client-side form validation
document.querySelector('.profile-form form').addEventListener('submit', function(e) {
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('phone');
    let isValid = true;
    let messages = [];

    // Simple validation for name
    if (nameInput.value.trim() === '') {
        messages.push('Full Name is required.');
        isValid = false;
        nameInput.classList.add('input-error');
    } else {
        nameInput.classList.remove('input-error');
    }

    // Simple validation for email
    if (emailInput.value.trim() === '') {
        messages.push('Email is required.');
        isValid = false;
        emailInput.classList.add('input-error');
    } else if (!/^[\w-]+(?:\.[\w-]+)*@(?:[\w-]+\.)+[a-zA-Z]{2,7}$/.test(emailInput.value)) {
        messages.push('Invalid email format.');
        isValid = false;
        emailInput.classList.add('input-error');
    } else {
        emailInput.classList.remove('input-error');
    }

    // Optional: Phone number validation (simple check)
    if (phoneInput.value.trim() !== '' && !/^[0-9+\s()-]*$/.test(phoneInput.value)) {
        messages.push('Invalid phone number format.');
        isValid = false;
        phoneInput.classList.add('input-error');
    } else {
        phoneInput.classList.remove('input-error');
    }

    if (!isValid) {
        e.preventDefault();
        const alertDiv = document.createElement('div');
        alertDiv.classList.add('alert', 'alert-danger');
        const ul = document.createElement('ul');
        messages.forEach(msg => {
            const li = document.createElement('li');
            li.textContent = msg;
            ul.appendChild(li);
        });
        alertDiv.appendChild(ul);

        const existingAlert = document.querySelector('.profile-form .alert-danger');
        if (existingAlert) {
            existingAlert.remove();
        }
        document.querySelector('.profile-form').prepend(alertDiv);
        alertDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
    } else {
        const existingAlert = document.querySelector('.profile-form .alert-danger');
        if (existingAlert) {
            existingAlert.remove();
        }
    }
});
</script>

<?php include 'includes/footer.php'; ?>
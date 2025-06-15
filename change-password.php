<?php
require_once 'includes/config.php';
requireLogin();

$pageTitle = "Change Password";
include 'includes/header.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    
    // Validate inputs
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $errors[] = "All fields are required";
    } elseif ($newPassword !== $confirmPassword) {
        $errors[] = "New passwords do not match";
    } elseif (strlen($newPassword) < 8) {
        $errors[] = "Password must be at least 8 characters";
    } else {
        $result = changePassword($_SESSION['user_id'], $currentPassword, $newPassword);
        if ($result === true) {
            $success = "Password changed successfully";
        } else {
            $errors[] = $result;
        }
    }
}
?>

<div class="container">
    <div class="form-container">
        <h2>Change Password</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="change-password.php">
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <div class="password-wrapper">
                    <input type="password" id="current_password" name="current_password" required>
                    <button type="button" class="password-toggle">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            
            <div class="form-group">
                <label for="new_password">New Password</label>
                <div class="password-wrapper">
                    <input type="password" id="new_password" name="new_password" required>
                    <button type="button" class="password-toggle">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <div class="password-wrapper">
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <button type="button" class="password-toggle">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Change Password</button>
                <a href="profile.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.password-toggle').forEach(toggle => {
    toggle.addEventListener('click', function() {
        const input = this.previousElementSibling;
        const icon = this.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>
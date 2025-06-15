<?php
require_once 'includes/config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}

$pageTitle = "Reset Password";
include 'includes/header.php';

$errors = [];
$success = false;
$token = $_GET['token'] ?? '';
$validToken = false;

// Check if token is valid
if (!empty($token)) {
    $stmt = $pdo->prepare("SELECT user_id FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$token]);
    $reset = $stmt->fetch();
    
    if ($reset) {
        $validToken = true;
        $userId = $reset['user_id'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $validToken) {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    
    if (empty($newPassword) || empty($confirmPassword)) {
        $errors[] = "Both fields are required";
    } elseif ($newPassword !== $confirmPassword) {
        $errors[] = "Passwords do not match";
    } elseif (strlen($newPassword) < 8) {
        $errors[] = "Password must be at least 8 characters";
    } else {
        $result = resetPasswordWithToken($token, $newPassword);
        if ($result === true) {
            $success = "Password reset successfully. You can now <a href='login.php'>login</a> with your new password.";
        } else {
            $errors[] = $result;
        }
    }
}
?>

<div class="container">
    <div class="form-container">
        <h2>Reset Password</h2>
        
        <?php if (!$validToken): ?>
            <div class="alert alert-danger">
                Invalid or expired password reset token. Please request a new reset link.
            </div>
            <div class="form-footer">
                <a href="forgot-password.php" class="btn btn-primary">Request New Link</a>
            </div>
        <?php else: ?>
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
                    <?php echo $success; ?>
                </div>
            <?php else: ?>
                <form method="POST" action="reset-password.php?token=<?php echo htmlspecialchars($token); ?>">
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
                        <button type="submit" class="btn btn-primary">Reset Password</button>
                    </div>
                </form>
            <?php endif; ?>
        <?php endif; ?>
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
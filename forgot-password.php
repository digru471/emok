<?php
require_once 'includes/config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}

$pageTitle = "Forgot Password";
include 'includes/header.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    } else {
        $token = generatePasswordResetToken($email);
        if (is_string($token)) {
            // In a real application, you would send an email with the reset link
            $resetLink = APP_URL . "/reset-password.php?token=" . urlencode($token);
            $success = "If an account with that email exists, we've sent a password reset link.";
            
            // For demo purposes, we'll show the link (remove in production)
            $success .= "<br><br><strong>Demo Link:</strong> <a href='$resetLink'>$resetLink</a>";
        } else {
            $errors[] = $token;
        }
    }
}
?>

<div class="container">
    <div class="form-container">
        <h2>Forgot Password</h2>
        
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
        <?php endif; ?>
        
        <form method="POST" action="forgot-password.php">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Send Reset Link</button>
                <a href="login.php" class="btn btn-secondary">Back to Login</a>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
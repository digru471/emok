<?php require_once 'includes/config.php'; ?>

<?php
// Redirect if already logged in
if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $errors[] = "Both email and password are required";
    } else {
        $result = loginUser($email, $password);
        if ($result === true) {
            // Redirect to appropriate dashboard
            if (isAdmin()) {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: dashboard.php");
            }
            exit();
        } else {
            $errors[] = $result;
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<style>
    :root {
        --brand-purple: #b452e4;
        --brand-purple-light: #d098f0;
        --brand-purple-dark: #8e35bd;
        --text-dark: #2c3e50;
        --text-muted: #6c757d;
        --bg-light: #f5f7fa;
        --bg-white: #ffffff;
        --border-color: #e9ecef;
        --danger-color: #ff6a88;
        --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    
    .form-container {
        background-color: var(--bg-white);
        border-radius: 12px;
        box-shadow: var(--shadow);
        max-width: 500px;
        margin: 50px auto;
        padding: 30px;
        position: relative;
        overflow: hidden;
    }
    
    .form-container:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(135deg, var(--brand-purple) 0%, var(--brand-purple-dark) 100%);
    }
    
    .form-container h2 {
        color: var(--brand-purple);
        font-weight: 600;
        margin-bottom: 25px;
        text-align: center;
        font-size: 28px;
    }
    
    .alert-danger {
        background-color: rgba(255, 106, 136, 0.1);
        color: var(--danger-color);
        border-left: 4px solid var(--danger-color);
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
    }
    
    .alert-danger ul {
        margin: 0;
        padding-left: 20px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--text-dark);
    }
    
    .form-group input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s;
        box-sizing: border-box;
    }
    
    .form-group input:focus {
        border-color: var(--brand-purple);
        outline: none;
        box-shadow: 0 0 0 3px rgba(180, 82, 228, 0.1);
    }
    
    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        font-size: 14px;
    }
    
    .remember-me {
        display: flex;
        align-items: center;
        cursor: pointer;
    }
    
    .remember-me input {
        margin-right: 8px;
    }
    
    .forgot-password {
        color: var(--brand-purple);
        text-decoration: none;
        transition: color 0.3s;
    }
    
    .forgot-password:hover {
        color: var(--brand-purple-dark);
        text-decoration: underline;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--brand-purple) 0%, var(--brand-purple-dark) 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 14px 20px;
        font-weight: 500;
        cursor: pointer;
        width: 100%;
        font-size: 16px;
        transition: all 0.3s;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(180, 82, 228, 0.3);
    }
    
    .form-footer {
        text-align: center;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
        color: var(--text-muted);
        font-size: 14px;
    }
    
    .form-footer a {
        color: var(--brand-purple);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s;
    }
    
    .form-footer a:hover {
        color: var(--brand-purple-dark);
        text-decoration: underline;
    }
</style>

<div class="container">
    <div class="form-container">
        <h2>Login</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" placeholder="Enter your email">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>
            
            <div class="form-options">
                <label class="remember-me">
                    <input type="checkbox" name="remember"> Remember me
                </label>
                <a href="forgot-password.php" class="forgot-password">Forgot password?</a>
            </div>
            
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        
        <div class="form-footer">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
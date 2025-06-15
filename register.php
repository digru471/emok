<?php require_once 'includes/config.php'; ?>

<?php
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $phone = trim($_POST['phone']);
    
    // Validate inputs
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters";
    } elseif ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match";
    }
    
    if (empty($errors)) {
        $result = registerUser($name, $email, $password, $phone);
        if ($result === true) {
            $success = true;
        } else {
            $errors[] = $result;
        }
    }
}
?>

<style>
    .form-container {
        background-color: var(--bg-white, #fff);
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        max-width: 500px;
        margin: 50px auto;
        padding: 30px;
        position: relative;
        overflow: hidden;
        border-top: 4px solid #b452e4;
    }
    .form-container h2 {
        color: #b452e4;
        font-weight: 700;
        margin-bottom: 25px;
        text-align: left;
        font-size: 2rem;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #2c3e50;
    }
    .form-group input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s, box-shadow 0.3s;
        box-sizing: border-box;
    }
    .form-group input:focus {
        border-color: #b452e4;
        outline: none;
        box-shadow: 0 0 0 3px rgba(180, 82, 228, 0.1);
    }
    .btn-primary {
        background: linear-gradient(135deg, #b452e4, #8e35bd);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px 24px;
        font-weight: 600;
        cursor: pointer;
        font-size: 1rem;
        transition: background 0.3s, box-shadow 0.3s, transform 0.2s;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #8e35bd, #b452e4);
        box-shadow: 0 5px 15px rgba(180, 82, 228, 0.2);
        transform: translateY(-2px);
    }
    .form-footer {
        text-align: left;
        margin-top: 20px;
        color: #6c757d;
        font-size: 1rem;
    }
    .form-footer a {
        color: #b452e4;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s;
    }
    .form-footer a:hover {
        color: #8e35bd;
        text-decoration: underline;
    }
    .input-error {
        border-color: #ff6a88 !important;
    }
    .error-message {
        color: #ff6a88;
        font-size: 0.95rem;
        margin-top: 5px;
    }
</style>

<?php include 'includes/header.php'; ?>

<div class="container">
    <div class="form-container">
        <h2>Register</h2>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                Registration successful! You can now <a href="login.php">login</a>.
            </div>
        <?php elseif (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        
        <div class="form-footer">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
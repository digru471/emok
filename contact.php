<?php
require_once 'includes/config.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    // Validate inputs
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($subject)) {
        $errors[] = "Subject is required";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required";
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $phone, $subject, $message]);
            
            $success = true;
            $_POST = []; // Clear form
        } catch (PDOException $e) {
            $errors[] = "Error submitting message: " . $e->getMessage();
        }
    }
}

$pageTitle = "Contact Us";
include 'includes/header.php';
?>

<!-- Add Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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
    padding-bottom: 300px; /* Height of the footer */
}

.container {
    flex: 1 0 auto;
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

footer {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    background-color: var(--dark);
    color: white;
    padding: 3rem 0 1rem;
}

.contact-form-container {
    max-width: 800px;
    margin: 40px auto;
    padding: 30px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.contact-form-container h2 {
    color: var(--brand-purple);
    margin-bottom: 30px;
    text-align: center;
    font-size: 2rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-weight: 500;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e1e1e1;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: var(--brand-purple);
    box-shadow: 0 0 0 3px rgba(180, 82, 228, 0.1);
    outline: none;
}

.form-group textarea {
    resize: vertical;
    min-height: 120px;
}

.form-actions {
    text-align: center;
    margin-top: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
}

.btn-primary {
    background: linear-gradient(45deg, #b452e4, #8a2be2);
    color: white;
    border: none;
    padding: 15px 40px;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 4px 15px rgba(180, 82, 228, 0.2);
    min-width: 200px;
    justify-content: center;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(180, 82, 228, 0.3);
    background: linear-gradient(45deg, #8a2be2, #b452e4);
}

.btn-primary:active {
    transform: translateY(0);
    box-shadow: 0 4px 15px rgba(180, 82, 228, 0.2);
}

.btn-primary i {
    font-size: 1.2rem;
}

.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    animation: slideIn 0.3s ease;
}

.alert-danger {
    background-color: #fff5f5;
    border: 1px solid #feb2b2;
    color: #c53030;
}

.alert-success {
    background-color: #f0fff4;
    border: 1px solid #9ae6b4;
    color: #2f855a;
}

.alert ul {
    margin: 0;
    padding-left: 20px;
}

.alert li {
    margin: 5px 0;
}

.input-error {
    border-color: #f56565 !important;
}

.error-message {
    color: #c53030;
    font-size: 0.875rem;
    margin-top: 5px;
}

@keyframes slideIn {
    from {
        transform: translateY(-10px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Character counter for message */
.char-counter {
    text-align: right;
    font-size: 0.875rem;
    color: #666;
    margin-top: 5px;
}

/* Loading spinner */
.spinner {
    display: none;
    width: 20px;
    height: 20px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid var(--brand-purple);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-left: 10px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive design */
@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .contact-form-container {
        margin: 20px;
        padding: 20px;
    }
}

/* Add a container for the button to ensure visibility */
.button-container {
    position: relative;
    z-index: 10;
    margin: 20px 0;
    display: block;
    width: 100%;
}
</style>

<div class="container">
    <div class="contact-form-container">
        <h2>Contact Us</h2>
        
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
                Thank you for your message! We'll get back to you soon.
            </div>
        <?php endif; ?>
        
        <form method="POST" action="contact.php" id="contactForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Full Name*</label>
                    <input type="text" id="name" name="name" required 
                           value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email*</label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" 
                       value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="subject">Subject*</label>
                <input type="text" id="subject" name="subject" required 
                       value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="message">Message*</label>
                <textarea id="message" name="message" rows="5" required maxlength="1000"><?php 
                    echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; 
                ?></textarea>
                <div class="char-counter"><span id="charCount">0</span>/1000 characters</div>
            </div>
            
            <div class="button-container">
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-paper-plane"></i>
                        Send Message
                        <span class="spinner" id="submitSpinner"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const messageInput = document.getElementById('message');
    const charCount = document.getElementById('charCount');
    const submitBtn = document.getElementById('submitBtn');
    const submitSpinner = document.getElementById('submitSpinner');
    
    // Character counter
    messageInput.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = length;
        
        if (length > 900) {
            charCount.style.color = '#f56565';
        } else {
            charCount.style.color = '#666';
        }
    });
    
    // Form validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('input-error');
                
                // Add error message if not exists
                let errorMsg = field.parentElement.querySelector('.error-message');
                if (!errorMsg) {
                    errorMsg = document.createElement('div');
                    errorMsg.className = 'error-message';
                    field.parentElement.appendChild(errorMsg);
                }
                errorMsg.textContent = 'This field is required';
            } else {
                field.classList.remove('input-error');
                const errorMsg = field.parentElement.querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.remove();
                }
            }
        });
        
        // Email validation
        const emailField = document.getElementById('email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (emailField.value && !emailRegex.test(emailField.value)) {
            isValid = false;
            emailField.classList.add('input-error');
            let errorMsg = emailField.parentElement.querySelector('.error-message');
            if (!errorMsg) {
                errorMsg = document.createElement('div');
                errorMsg.className = 'error-message';
                emailField.parentElement.appendChild(errorMsg);
            }
            errorMsg.textContent = 'Please enter a valid email address';
        }
        
        if (isValid) {
            // Show loading spinner
            submitBtn.disabled = true;
            submitSpinner.style.display = 'inline-block';
        } else {
            e.preventDefault();
        }
    });
    
    // Real-time validation
    const inputs = form.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('input-error');
                const errorMsg = this.parentElement.querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.remove();
                }
            }
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
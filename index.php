<?php require_once 'includes/config.php'; ?>
<?php include 'includes/header.php'; ?>

<!-- Landify Theme CSS with Admin Panel Colors -->
<style>
    :root {
        /* Admin Brand Colors */
        --brand-purple: #b452e4;
        --brand-purple-light: #d098f0;
        --brand-purple-dark: #8e35bd;
        
        /* Admin Card Colors */
        --card-orange: linear-gradient(135deg, #ff9d7e 0%, #ff6a88 100%);
        --card-blue: linear-gradient(135deg, #56ccf2 0%, #3a8ef7 100%);
        --card-teal: linear-gradient(135deg, #2de2c3 0%, #38ef7d 100%);
        
        /* Admin Text Colors */
        --text-dark: #2c3e50;
        --text-muted: #6c757d;
        --text-light: #ffffff;
        
        /* Admin Background Colors */
        --bg-light: #f5f7fa;
        --bg-white: #ffffff;
        --sidebar-bg: #2f2f3a;
        
        /* Admin UI Colors */
        --border-color: #e9ecef;
        --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --success-color: #38ef7d;
        --warning-color: #ff9d7e;
        --danger-color: #ff6a88;
        --info-color: #56ccf2;
        
        /* Gradients */
        --primary-gradient: linear-gradient(135deg, #b452e4, #8e35bd);
    }
    
    html, body {
        margin: 0;
        padding: 0;
        width: 100vw;
        height: 100vh;
        overflow-x: hidden;
    }
    
    .landify-wrapper {
        width: 100vw;
        max-width: 100vw;
        padding: 0;
        margin: 0;
        background: var(--bg-light);
        min-height: 100vh;
        overflow-x: hidden;
        display: flex;
        flex-direction: column;
    }
    
    .landify-container {
        width: 100vw;
        max-width: 100vw;
        margin: 0;
        padding: 0;
        flex: 1;
    }
    
    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--bg-light);
        color: var(--text-dark);
        line-height: 1.6;
    }
    
    /* Content containers for internal spacing */
    .section-content {
        display: flex;
        flex-direction: column;
        width: 100%;
        max-width: 100%;
        margin: 0;
        padding: 0;
    }
    
    /* Header Alignment */
    header {
        background: var(--bg-white) !important;
        border-bottom: none !important;
        box-shadow: var(--shadow) !important;
        width: 100%;
        max-width: 100%;
    }
    
    .navbar {
        background: var(--bg-white) !important;
        backdrop-filter: blur(10px);
        border-bottom: 1px solid var(--border-color) !important;
        width: 100%;
        max-width: 100%;
    }
    
    .navbar-brand, .nav-link {
        color: var(--text-dark) !important;
    }
    
    .navbar-brand:hover, .nav-link:hover {
        color: var(--brand-purple) !important;
    }
    
    .dropdown-menu {
        background-color: var(--bg-white) !important;
        border: 1px solid var(--border-color) !important;
    }
    
    .dropdown-item {
        color: var(--text-dark) !important;
    }
    
    .dropdown-item:hover {
        background-color: rgba(180, 82, 228, 0.1) !important;
        color: var(--brand-purple) !important;
    }
    
    .btn-primary {
        background: var(--primary-gradient) !important;
        border: none !important;
    }
    
    .btn-secondary {
        background: rgba(255, 255, 255, 0.1) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        color: var(--text-light) !important;
    }
    
    /* Hero Section */
    .hero-section {
        padding: 60px 0;
        text-align: center;
        background: var(--primary-gradient);
        position: relative;
        overflow: hidden;
        width: 100%;
        max-width: 100%;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: -20%;
        right: -10%;
        width: 600px;
        height: 600px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }
    
    .hero-section::after {
        content: '';
        position: absolute;
        bottom: -20%;
        left: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }
    
    .hero-content {
        position: relative;
        z-index: 2;
        color: var(--text-light);
        padding: 0 80px;
        max-width: unset !important;
        margin: 0 auto;
    }
    
    .hero-title {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 20px;
    }
    
    .hero-description {
        font-size: 1.2rem;
        margin-bottom: 30px;
        opacity: 0.9;
    }
    
    .cta-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 20px;
    }
    
    .btn {
        padding: 12px 24px;
        font-weight: 600;
        border-radius: 6px;
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }
    
    /* Features Section */
    .features-section {
        padding: 80px 0;
        background: var(--bg-light);
        width: 100%;
        max-width: 100%;
    }
    
    .section-title {
        text-align: center;
        margin-bottom: 60px;
    }
    
    .section-title h2 {
        color: var(--brand-purple);
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 15px;
    }
    
    .section-title p {
        color: var(--text-muted);
        font-size: 1.1rem;
    }
    
    .feature-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        padding: 0 80px;
        max-width: unset !important;
        margin: 0 auto;
    }
    
    .feature {
        background: var(--bg-white);
        padding: 30px;
        border-radius: 12px;
        box-shadow: var(--shadow);
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .feature:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }
    
    .feature i {
        font-size: 2.5rem;
        color: var(--brand-purple);
        margin-bottom: 20px;
    }
    
    .feature h3 {
        color: var(--text-dark);
        font-size: 1.5rem;
        margin-bottom: 15px;
    }
    
    .feature p {
        color: var(--text-muted);
        line-height: 1.6;
    }
    
    /* Team Section */
    .team-section {
        padding: 80px 0;
        background: var(--bg-light);
        width: 100%;
        max-width: 100%;
    }
    
    .team-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        padding: 0 20px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .team-member {
        background: var(--bg-white);
        padding: 30px;
        border-radius: 12px;
        box-shadow: var(--shadow);
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .team-member:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }
    
    .member-image {
        width: 120px;
        height: 120px;
        margin: 0 auto 20px;
        overflow: hidden;
        border-radius: 50%;
        border: 3px solid var(--brand-purple);
        box-shadow: 0 0 20px rgba(180, 82, 228, 0.2);
    }
    
    .member-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .team-member h3 {
        color: var(--text-dark);
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .team-member .role {
        color: var(--brand-purple);
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .team-member .bio {
        color: var(--text-muted);
        font-size: 0.9rem;
        line-height: 1.6;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        .feature, .team-member {
            flex: 0 0 50%;
        }
    }
    
    @media (max-width: 768px) {
        .feature, .team-member {
            flex: 0 0 100%;
        }
        
        .hero-title {
            font-size: 2.2rem;
        }
        
        .section-title h2 {
            font-size: 2rem;
        }
    }
    
    /* Footer Alignment */
    footer {
        background: var(--sidebar-bg) !important;
        border-top: 1px solid rgba(255, 255, 255, 0.05) !important;
    }
    
    footer p, footer a {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    
    footer a:hover {
        color: var(--brand-purple) !important;
    }
    
    /* Admin Layout Structure */
    .admin-container {
        display: flex;
        min-height: 100vh;
        width: 100%;
        position: relative;
    }
    
    .admin-sidebar {
        width: 250px;
        position: fixed;
        left: 0;
        top: 60px; /* Match header height */
        bottom: 0;
        background-color: var(--sidebar-bg);
        color: white;
        overflow-y: auto;
        z-index: 100;
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
    }
    
    .admin-content {
        flex: 1;
        padding: 30px;
        margin-left: 250px; /* Match sidebar width */
        width: calc(100% - 250px); /* Ensure it takes full width minus sidebar */
        box-sizing: border-box;
        max-width: 100%;
    }
    
    /* Responsive adjustments */
    @media (max-width: 991px) {
        .admin-content {
            margin-left: 0;
            width: 100%;
            padding: 20px 15px;
        }
        
        .admin-sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        .admin-sidebar.show {
            transform: translateX(0);
        }
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
        border-top: 4px solid var(--brand-purple);
    }

    .form-container h2 {
        color: var(--brand-purple);
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
        color: var(--text-dark);
    }

    .form-group input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s, box-shadow 0.3s;
        box-sizing: border-box;
    }

    .form-group input:focus {
        border-color: var(--brand-purple);
        outline: none;
        box-shadow: 0 0 0 3px rgba(180, 82, 228, 0.1);
    }

    .btn-primary {
        background: var(--primary-gradient);
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
        color: var(--text-muted);
        font-size: 1rem;
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

    .input-error {
        border-color: var(--danger-color) !important;
    }

    .error-message {
        color: var(--danger-color);
        font-size: 0.95rem;
        margin-top: 5px;
    }
</style>

<div class="landify-wrapper">
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">Welcome to <?php echo APP_NAME; ?></h1>
            <p class="hero-description">Practice and improve your skills with our professional mock tests</p>
            <div class="cta-buttons">
                <?php if (!isLoggedIn()): ?>
                    <a href="register.php" class="btn btn-primary">Register</a>
                    <a href="login.php" class="btn btn-secondary">Login</a>
                <?php else: ?>
                    <a href="dashboard.php" class="btn btn-primary">Go to Dashboard</a>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <section class="features-section">
        <div class="section-title">
            <h2>Why Choose Our Platform</h2>
            <p>Our comprehensive tools and resources are designed to help you succeed</p>
        </div>
        
        <div class="feature-grid">
            <div class="feature">
                <i class="fas fa-book"></i>
                <h3>Wide Range of Subjects</h3>
                <p>Access tests across multiple subjects and categories to enhance your knowledge and skills.</p>
            </div>
            
            <div class="feature">
                <i class="fas fa-clock"></i>
                <h3>Timed Tests</h3>
                <p>Practice under real exam conditions with accurately timed tests to build your confidence.</p>
            </div>
            
            <div class="feature">
                <i class="fas fa-chart-bar"></i>
                <h3>Detailed Analytics</h3>
                <p>Track your progress with comprehensive result analysis to identify areas for improvement.</p>
            </div>
        </div>
    </section>
    
    <section class="team-section">
        <div class="section-title">
            <h2>Our Team</h2>
            <p>Meet the experts behind our platform who are committed to your success</p>
        </div>
        
        <div class="team-grid">
            <?php 
            $teamMembers = getTeamMembers();
            foreach ($teamMembers as $member): 
            ?>
                <div class="team-member">
                    <div class="member-image">
                        <img src="<?php echo htmlspecialchars($member['photo'] ?? 'uploads/team_members/default.jpg'); ?>" alt="<?php echo htmlspecialchars($member['name']); ?>">
                    </div>
                    <h3><?php echo htmlspecialchars($member['name']); ?></h3>
                    <p class="role"><?php echo htmlspecialchars(ucfirst($member['role'])); ?></p>
                    <p class="bio"><?php echo htmlspecialchars($member['experience'] ?? 'No biography available.'); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.form-container form');
    if (!form) return;

    const password = form.querySelector('input[name="password"]');
    const confirmPassword = form.querySelector('input[name="confirm_password"]');
    const email = form.querySelector('input[name="email"]');
    const requiredInputs = form.querySelectorAll('input[required]');
    
    // Helper to show error
    function showError(input, message) {
        input.classList.add('input-error');
        let error = input.parentElement.querySelector('.error-message');
        if (!error) {
            error = document.createElement('div');
            error.className = 'error-message';
            input.parentElement.appendChild(error);
        }
        error.textContent = message;
    }

    // Helper to clear error
    function clearError(input) {
        input.classList.remove('input-error');
        let error = input.parentElement.querySelector('.error-message');
        if (error) error.remove();
    }

    // Password match check
    function checkPasswordMatch() {
        if (password.value !== confirmPassword.value) {
            showError(confirmPassword, 'Passwords do not match');
            return false;
        } else {
            clearError(confirmPassword);
            return true;
        }
    }

    // Email format check
    function checkEmail() {
        const re = /^[^@\\s]+@[^@\\s]+\\.[^@\\s]+$/;
        if (!re.test(email.value)) {
            showError(email, 'Please enter a valid email address');
            return false;
        } else {
            clearError(email);
            return true;
        }
    }

    // Required fields check
    function checkRequired() {
        let valid = true;
        requiredInputs.forEach(input => {
            if (!input.value.trim()) {
                showError(input, 'This field is required');
                valid = false;
            } else {
                clearError(input);
            }
        });
        return valid;
    }

    // Real-time validation
    if (password && confirmPassword) {
        confirmPassword.addEventListener('input', checkPasswordMatch);
        password.addEventListener('input', checkPasswordMatch);
    }
    if (email) {
        email.addEventListener('input', checkEmail);
    }

    form.addEventListener('submit', function(e) {
        let valid = true;
        if (!checkRequired()) valid = false;
        if (!checkEmail()) valid = false;
        if (!checkPasswordMatch()) valid = false;
        if (!valid) e.preventDefault();
    });
});
</script>
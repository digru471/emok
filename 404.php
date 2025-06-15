<?php
// Include the configuration file
require_once 'includes/config.php';

// Set page title and include header
$pageTitle = "Page Not Found";
include 'includes/header.php';
?>

<div class="container mt-5 text-center">
    <div class="error-page">
        <h1 class="text-danger">404</h1>
        <h2>Page Not Found</h2>
        <p class="mt-3">Sorry, the page you are looking for does not exist or has been moved.</p>
        
        <div class="mt-4">
            <a href="<?php echo APP_URL; ?>/index.php" class="btn btn-primary">Go to Homepage</a>
        </div>
        
        <div class="mt-5">
            <h3>Looking for something specific?</h3>
            <ul class="mt-3 list-unstyled">
                <li><a href="<?php echo APP_URL; ?>/team.php">Our Team</a></li>
                <li><a href="<?php echo APP_URL; ?>/tests.php">Available Tests</a></li>
                <li><a href="<?php echo APP_URL; ?>/login.php">Login</a></li>
                <li><a href="<?php echo APP_URL; ?>/register.php">Register</a></li>
            </ul>
        </div>
    </div>
</div>

<style>
.error-page {
    padding: 50px 0;
}
.error-page h1 {
    font-size: 120px;
    font-weight: 700;
    margin-bottom: 0;
}
.error-page h2 {
    font-size: 32px;
    margin-bottom: 30px;
}
.error-page p {
    font-size: 18px;
    color: #666;
}
</style>

<?php include 'includes/footer.php'; ?> 
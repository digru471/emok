<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the configuration file (with try/catch to prevent nested errors)
try {
    require_once 'includes/config.php';
} catch (Exception $e) {
    // Just continue if config can't be loaded
}

// Set page title
$pageTitle = "Server Error";

// Try to include header, but provide fallback if unavailable
try {
    include 'includes/header.php';
} catch (Exception $e) {
    // Simple fallback header
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Server Error</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body { font-family: "Poppins", sans-serif; margin: 0; padding: 0; color: #333; }
            .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        </style>
    </head>
    <body>
    <div class="container">';
}
?>

<div class="container mt-5 text-center">
    <div class="error-page">
        <h1 class="text-danger">500</h1>
        <h2>Server Error</h2>
        <p class="mt-3">Sorry, something went wrong on our end. The server encountered an error while processing your request.</p>
        
        <div class="mt-4">
            <a href="<?php echo defined('APP_URL') ? APP_URL : '/bca'; ?>/index.php" class="btn btn-primary">Return to Homepage</a>
        </div>
        
        <?php if(isset($_SERVER['HTTP_REFERER'])): ?>
        <div class="mt-3">
            <a href="<?php echo htmlspecialchars($_SERVER['HTTP_REFERER']); ?>" class="btn btn-outline-secondary">Go Back</a>
        </div>
        <?php endif; ?>
        
        <div class="error-details mt-5">
            <p>Please try again later. If the problem persists, contact the administrator.</p>
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
    color: #dc3545;
}
.error-page h2 {
    font-size: 32px;
    margin-bottom: 30px;
}
.error-page p {
    font-size: 18px;
    color: #666;
}
.btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #9c27b0;
    color: white;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}
.btn-outline-secondary {
    background-color: transparent;
    border: 1px solid #6c757d;
    color: #6c757d;
}
.btn:hover {
    opacity: 0.9;
}
</style>

<?php 
// Try to include footer, but handle it if unavailable
try {
    include 'includes/footer.php';
} catch (Exception $e) {
    echo '</div></body></html>';
}
?> 
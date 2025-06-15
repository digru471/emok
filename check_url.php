<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load configuration
require_once 'includes/config.php';

// Output server and URL info
echo "<h1>URL Configuration Check</h1>";

echo "<h2>Server Information</h2>";
echo "<ul>";
echo "<li>HTTP_HOST: " . $_SERVER['HTTP_HOST'] . "</li>";
echo "<li>REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "</li>";
echo "<li>SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "</li>";
echo "<li>PHP_SELF: " . $_SERVER['PHP_SELF'] . "</li>";
echo "<li>DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "</li>";
echo "</ul>";

echo "<h2>Application Configuration</h2>";
echo "<ul>";
echo "<li>APP_URL: " . APP_URL . "</li>";
echo "<li>APP_ROOT: " . APP_ROOT . "</li>";
echo "</ul>";

echo "<h2>Base URL Detection</h2>";
$baseURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$currentPath = dirname($_SERVER['SCRIPT_NAME']);
echo "<ul>";
echo "<li>Detected Base URL: " . $baseURL . "</li>";
echo "<li>Current Path: " . $currentPath . "</li>";
echo "<li>Full URL: " . $baseURL . $currentPath . "</li>";
echo "</ul>";

echo "<h2>Test Links</h2>";
echo "<ul>";
echo "<li><a href='" . APP_URL . "/index.php'>Home Page (using APP_URL)</a></li>";
echo "<li><a href='index.php'>Home Page (relative)</a></li>";
echo "<li><a href='" . APP_URL . "/team.php'>Team Page (using APP_URL)</a></li>";
echo "<li><a href='team.php'>Team Page (relative)</a></li>";
echo "</ul>";

echo "<h2>Server Request Information</h2>";
echo "<pre>";
print_r($_SERVER);
echo "</pre>";
?> 
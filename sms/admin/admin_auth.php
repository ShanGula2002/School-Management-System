<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * BIG SYSTEM AUTHENTICATION CHECK
 * 1. Checks if user_id session exists (set during login_process)
 * 2. Ensures the role is strictly 'Admin'
 */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    
    // Clear all session data for safety
    session_unset();
    session_destroy(); 
    
    // Redirect to index.php (the landing page) with an error trigger
    // Since your login is now a MODAL on index.php, we redirect there.
    header("Location: ../index.php?error=unauthorized");
    exit();
}

// Pro-tip: You can add a timestamp check here later to auto-logout 
// users after 30 minutes of inactivity for higher security.
?>
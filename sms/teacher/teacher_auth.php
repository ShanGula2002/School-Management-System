<?php
// Start the session if it hasn't been started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * TEACHER AUTHENTICATION GATEKEEPER
 * This ensures that only logged-in teachers can access these files.
 */

// 1. Check if the user is logged in
// 2. Check if the role is specifically 'teacher' (or verify the teacher_id exists)
if (!isset($_SESSION['teacher_id']) || $_SESSION['role'] !== 'Teacher') {
    header("Location: ../login.php?error=unauthorized_access");
    exit();
}

// Optional: Regenerate session ID periodically to prevent session fixation
if (!isset($_SESSION['last_regeneration'])) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
} elseif (time() - $_SESSION['last_regeneration'] > 1800) { // Every 30 mins
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}
?>
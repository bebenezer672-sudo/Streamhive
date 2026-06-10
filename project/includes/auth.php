<?php
// Start session and check if user is logged in
session_start();

// Check if user is authenticated
if (!isset($_SESSION['user_id'])) {
    // User is not logged in
    $_SESSION['authenticated'] = false;
} else {
    // User is logged in
    $_SESSION['authenticated'] = true;
}

// Optional: Add user data to superglobal for easy access
$authenticated = isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0;

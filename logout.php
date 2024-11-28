<?php
session_start();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Make sure the session cookie is cleared, just in case it's still active
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirect to login page
header("Location: login.php");
exit();

<?php

use VetSync\Service\SessionManager;

$sessionName = $_ENV['SESSION_NAME'] ?? 'VETSYNC_SESSION';
$sessionLifetime = ($_ENV['SESSION_LIFETIME'] ?? 120) * 60; // convert minutes to seconds

// Configure session settings
ini_set('session.gc_maxlifetime', $sessionLifetime);
ini_set('session.cookie_lifetime', $sessionLifetime);
ini_set('session.use_strict_mode', 1); // Prevents session fixation attacks
ini_set('session.cookie_httponly', 1); // Prevents JavaScript access to session cookie
ini_set('session.use_only_cookies', 1); // Forces sessions to only use cookies
ini_set('session.cookie_secure', $_ENV['APP_ENV'] === 'production' ? 1 : 0); // Secure in production

// Start the session if not already started
session_name($sessionName);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Instantiate the session manager
$session = new SessionManager();

// Check path type once to avoid multiple function calls
$sessionPathType = null;
if (uriAppPath('user')) {
    $sessionPathType = 'user';
} elseif (uriAppPath('admin')) {
    $sessionPathType = 'admin';
} elseif (uriAppPath('auth')) {
    $sessionPathType = 'auth';
}

// Handle authentication and access control
if (($sessionPathType === 'user' || $sessionPathType === 'admin') && !$session->has()) {
    // Redirect to landing page if accessing restricted area without session
    header("Location: " . app('landing'));
    exit;

} elseif ($sessionPathType === 'auth' && $session->has()) {
    // Destroy existing session when accessing auth pages
    $session->destroy();

} elseif ($session->has()) {
    // Handle incorrect area access based on account type
    $sessionType = $session->get()['type'] ?? null;
    if (
        ($sessionType === 'user' && $sessionPathType === 'admin') ||
        ($sessionType === 'admin' && $sessionPathType === 'user')
    ) {
        header("Location: " . app($sessionType));
        exit;
    }
}

// Debug session data
// error_log(print_r($session->get(), true));

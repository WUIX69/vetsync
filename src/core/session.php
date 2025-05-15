<?php

$sessionName = $_ENV['SESSION_NAME'] ?? null;
$sessionLifetime = ($_ENV['SESSION_LIFETIME'] ?? 120) * 60; // convert minutes to seconds
$uriPath = $_SERVER['REQUEST_URI']; // Get current path

function sessionSet($data = null)
{
    global $sessionName;
    $_SESSION[$sessionName] = $data;
}

function sessionAdd($data = null)
{
    global $sessionName;
    if (is_array($data)) {
        $_SESSION[$sessionName] = array_merge($_SESSION[$sessionName], $data);
    } else {
        $_SESSION[$sessionName][$data];
    }
}

function sessionGet()
{
    global $sessionName;
    return $_SESSION[$sessionName] ?? null;
}

function sessionCheck()
{
    global $sessionName;
    return isset($_SESSION[$sessionName]) ?? false;
}

function sessionRemove($value = null)
{
    global $sessionName;
    if (!sessionCheck()) {
        return false;
    }

    unset($_SESSION[$sessionName][$value]);
    return true;
}

function sessionDestroy()
{
    session_unset();
    session_destroy();
    // return true;
}

// Simple path-checking functions
function isAuthPath($path)
{
    return strpos($path, '/auth/') !== false;
}

function isRestrictedPath($path)
{
    return strpos($path, '/user/') !== false || strpos($path, '/admin/') !== false;
}

// Configure session settings
ini_set('session.gc_maxlifetime', $sessionLifetime);
ini_set('session.cookie_lifetime', $sessionLifetime);
ini_set('session.use_strict_mode', 1); // Prevents session fixation attacks
ini_set('session.cookie_httponly', 1); // Prevents JavaScript access to session cookie
ini_set('session.use_only_cookies', 1); // Forces sessions to only use cookies
ini_set('session.cookie_secure', $_ENV['APP_ENV'] === 'production' ? 1 : 0); // Secure in production

// Start the session if not already started
session_name($sessionName); // Set the session name
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Session access control logic
$isAuthPage = isAuthPath($uriPath);
$isRestrictedPage = isRestrictedPath($uriPath);

// Handle user with active session trying to access auth page
if ($isAuthPage && sessionCheck()) {
    error_log('Session destroyed: User with active session accessed auth page');
    sessionDestroy();
}

// Handle access to restricted areas without a session
if ($isRestrictedPage && !sessionCheck()) {
    error_log('Access denied: No active session for restricted area');

    if (!$isAuthPage) {
        header("Location: " . app('landing'));
        exit;
    }
}
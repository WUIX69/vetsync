<?php

$sessionName = $_ENV['SESSION_NAME'] ?? 'VETSYNC_SESSION';
$sessionLifetime = ($_ENV['SESSION_LIFETIME'] ?? 120) * 60; // convert minutes to seconds

class SessionManager
{
    private $sessionName;

    public function __construct($sessionName = null)
    {
        if ($sessionName !== null) {
            $this->sessionName = $sessionName;
        } else {
            global $sessionName;
            $this->sessionName = $sessionName;
        }
    }

    public function set($data = null)
    {
        $_SESSION[$this->sessionName] = $data;
    }

    public function add($data = null)
    {
        if (is_array($data)) {
            $_SESSION[$this->sessionName] = array_merge($_SESSION[$this->sessionName] ?? [], $data);
        } else {
            $_SESSION[$this->sessionName][$data] = $data;
        }
    }

    public function get()
    {
        return $_SESSION[$this->sessionName] ?? null;
    }

    public function has()
    {
        return isset($_SESSION[$this->sessionName]);
    }

    public function remove($value = null)
    {
        if (!$this->has())
            return false;
        unset($_SESSION[$this->sessionName][$value]);
        return true;
    }

    public function destroy()
    {
        session_unset();
        session_destroy();
    }
}

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
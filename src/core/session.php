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

$isUserSession = uriAppPath('user');
$isAdminSession = uriAppPath('admin');
$isAuthSession = uriAppPath('auth');

// Handle access to restricted areas without a session
$isRestrictedPage = $isUserSession || $isAdminSession;
if ($isRestrictedPage && !$session->has()) {
    // error_log('Access denied: No active session for restricted area');
    header("Location: " . app('landing'));
    exit;
}

// Handle user with active session trying to access auth page
if ($isAuthSession && $session->has()) {
    // error_log('Session destroyed: User with active session accessed auth page');
    $session->destroy();
}

// Handle user and admin trying to access each other's pages
// error_log('Session type: ' . $session->get()['type']);
if ($session->has()) {
    if ($session->get()['type'] === 'admin' && $isUserSession) {
        header("Location: " . app('admin'));
        exit;
    }

    if ($session->get()['type'] === 'user' && $isAdminSession) {
        header("Location: " . app('user'));
        exit;
    }
}
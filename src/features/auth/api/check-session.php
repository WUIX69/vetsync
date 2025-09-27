<?php
include_once __DIR__ . '/../../../core/app.php';

header('Content-Type: application/json');

global $session;

if ($session->has()) {
    $userData = $session->get();
    echo json_encode([
        'success' => true,
        'logged_in' => true,
        'user' => $userData
    ]);
} else {
    echo json_encode([
        'success' => true,
        'logged_in' => false,
        'user' => null
    ]);
}
?>
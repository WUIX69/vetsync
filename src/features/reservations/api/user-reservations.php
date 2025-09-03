<?php

include '../../../core/app.php';
apiHeaders();

use VetSync\Models\Reservations;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    global $session;

    if (!$session->has()) {
        echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
        exit;
    }

    $userData = $session->get();
    $result = Reservations::getByUser($userData['uuid']);

    echo json_encode($result);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request method']);
exit;

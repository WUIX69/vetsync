<?php

include '../../../core/app.php';
apiHeaders();

use VetSync\Models\Appointments;

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $uuid = $_POST['uuid'] ?? null;
    $status = $_POST['status'] ?? null;
    if (!$uuid || !$status) {
        echo json_encode(['success' => false, 'message' => 'Missing parameters']);
        exit;
    }
    $response = VetSync\Models\Appointments::updateStatus($uuid, $status);
    echo json_encode($response);
    exit;
}

// Booking handler (must be after the status update handler)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure user is logged in using SessionManager
    if (!$session->has()) {
        echo json_encode(['success' => false, 'message' => 'You must be logged in to book an appointment.']);
        exit;
    }

    $userData = $session->get();

    $data = [
        'uuid' => uuid(),
        'service_uuid' => $_POST['service_uuid'] ?? null,
        'user_uuid' => $userData['uuid'], // Use the uuid from session manager
        'pet_uuid' => $_POST['pet_uuid'] ?? null,
        'date' => $_POST['date'] ?? null,
        'note' => $_POST['special_request'] ?? null,
        // Add other fields as needed
    ];

    $response = Appointments::store($data);
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $response = Appointments::all();
    echo json_encode($response);
    exit;
}

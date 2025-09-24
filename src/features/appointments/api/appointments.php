<?php

include '../../../core/app.php';
apiHeaders();

use VetSync\Models\Appointments;

$response = [];

// Handle admin actions (update_status, reschedule, delete) FIRST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_status') {
        $uuid = $_POST['uuid'] ?? null;
        $status = $_POST['status'] ?? null;
        $cancellationReason = $_POST['cancellation_reason'] ?? null;

        if (!$uuid || !$status) {
            echo json_encode(['success' => false, 'message' => 'Missing parameters']);
            exit;
        }

        // If cancelling, require a reason
        if ($status === 'cancelled' && empty($cancellationReason)) {
            echo json_encode(['success' => false, 'message' => 'Cancellation reason is required']);
            exit;
        }

        $response = VetSync\Models\Appointments::updateStatusWithReason($uuid, $status, $cancellationReason);
        echo json_encode($response);
        exit;
    }

    if ($_POST['action'] === 'reschedule') {
        $uuid = $_POST['uuid'] ?? null;
        $newDate = $_POST['new_date'] ?? null;
        $reason = $_POST['reason'] ?? '';

        if (!$uuid || !$newDate) {
            echo json_encode(['success' => false, 'message' => 'Missing appointment ID or new date']);
            exit;
        }

        $response = VetSync\Models\Appointments::reschedule($uuid, $newDate, $reason);
        echo json_encode($response);
        exit;
    }

    if ($_POST['action'] === 'delete') {
        $uuid = $_POST['uuid'] ?? null;
        if (!$uuid) {
            echo json_encode(['success' => false, 'message' => 'Missing appointment ID']);
            exit;
        }
        $response = VetSync\Models\Appointments::delete($uuid);
        echo json_encode($response);
        exit;
    }
}

// Handle booking (POST without action) AFTER admin actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action'])) {
    // Ensure user is logged in
    if (!$session->has()) {
        echo json_encode(['success' => false, 'message' => 'You must be logged in to book an appointment.']);
        exit;
    }

    $userData = $session->get();

    // Check user verification status
    $isVerified = \VetSync\Models\Users::isUserVerified($userData['uuid']);
    if (!$isVerified) {
        echo json_encode([
            'success' => false,
            'message' => 'Your account is pending for verification. Please wait until verified to complete this action.'
        ]);
        exit;
    }

    // Validate required fields
    $serviceUuid = $_POST['service_uuid'] ?? null;
    $customServiceRequest = $_POST['custom_service_request'] ?? null;
    $petUuid = $_POST['pet_uuid'] ?? null;
    $date = $_POST['date'] ?? null;

    if (!$serviceUuid) {
        echo json_encode(['success' => false, 'message' => 'Please select a service.']);
        exit;
    }

    // Handle "others" service option
    if ($serviceUuid === 'others') {
        if (empty($customServiceRequest)) {
            echo json_encode(['success' => false, 'message' => 'Please describe the custom service you need.']);
            exit;
        }
        // For "others", we'll set service_uuid to null and store the custom request in the note
        $serviceUuid = null;
    }

    if (!$petUuid) {
        echo json_encode(['success' => false, 'message' => 'Please select a pet.']);
        exit;
    }

    if (!$date) {
        echo json_encode(['success' => false, 'message' => 'Please select an appointment date.']);
        exit;
    }

    // Validate date format and ensure it's not in the past
    $appointmentDate = DateTime::createFromFormat('Y-m-d', $date);
    if (!$appointmentDate) {
        echo json_encode(['success' => false, 'message' => 'Invalid date format.']);
        exit;
    }

    if ($appointmentDate < new DateTime('today')) {
        echo json_encode(['success' => false, 'message' => 'Appointment date cannot be in the past.']);
        exit;
    }

    $note = $_POST['special_request'] ?? null;

    // If it's a custom service, prepend the custom request to the note
    if ($_POST['service_uuid'] === 'others' && !empty($customServiceRequest)) {
        $note = "CUSTOM SERVICE REQUEST: " . $customServiceRequest .
            (!empty($note) ? "\n\nSpecial Instructions: " . $note : "");
    }

    $data = [
        'uuid' => uuid(),
        'service_uuid' => $serviceUuid, // This will be null for "others"
        'user_uuid' => $userData['uuid'],
        'pet_uuid' => $petUuid,
        'date' => $date,
        'note' => $note,
    ];

    $response = Appointments::store($data);
    echo json_encode($response);
    exit;
}

// Handle GET requests (fetch appointments)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = Appointments::all();

    // Format the data to include pet images
    if ($result['success']) {
        $result['data'] = array_map(function ($item) {
            $formattedData = [
                'pet_image' => $item['pet_uuid'] ? media($item['pet_uuid']) : asset('img/placeholders/image.png'),
            ];
            return array_merge($item, $formattedData);
        }, $result['data'] ?? []);
    }

    $response = $result;
    echo json_encode($response);
    exit;
}

// Invalid request method
echo json_encode(['success' => false, 'message' => 'Invalid request method']);
?>
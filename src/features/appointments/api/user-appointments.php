<?php

include '../../../core/app.php';
apiHeaders();

use VetSync\Models\Appointments;

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Ensure user is logged in
    if (!$session->has()) {
        echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
        exit;
    }

    $userData = $session->get();
    $userUuid = $userData['uuid'];

    $result = Appointments::getByUserUuid($userUuid);

    // Format the data to include pet images (same pattern as admin)
    if ($result['success']) {
        $result['data'] = array_map(function ($item) {
            // Add pet image using media() function
            $formattedData = [
                'pet_image' => $item['pet_uuid'] ? media($item['pet_uuid']) : asset('img/placeholders/image.png'),
                'formatted_date' => date('F j, Y', strtotime($item['date'])),
                'formatted_time' => date('g:i A', strtotime($item['date'])),
            ];

            // Merge formatted data with the original item
            return array_merge($item, $formattedData);
        }, $result['data'] ?? []);
    }

    $response = $result;
    echo json_encode($response);
    exit;
}

// Handle POST requests (cancel and delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    // Ensure user is logged in
    if (!$session->has()) {
        echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
        exit;
    }

    $userData = $session->get();
    $userUuid = $userData['uuid'];

    if ($_POST['action'] === 'cancel') {
        $appointmentUuid = $_POST['uuid'] ?? null;
        $reason = $_POST['cancellation_reason'] ?? 'Cancelled by patient - no reason provided';

        if (!$appointmentUuid) {
            echo json_encode(['success' => false, 'message' => 'Missing appointment ID']);
            exit;
        }

        $response = Appointments::updateStatusWithReason($appointmentUuid, 'cancelled', $reason);
        echo json_encode($response);
        exit;
    }

    if ($_POST['action'] === 'delete') {
        $appointmentUuid = $_POST['uuid'] ?? null;
        if (!$appointmentUuid) {
            echo json_encode(['success' => false, 'message' => 'Missing appointment ID']);
            exit;
        }

        // Verify appointment belongs to current user (using direct query to avoid warnings)
        try {
            $stmt = $conn->prepare('SELECT user_uuid, status FROM appointments WHERE uuid = ? LIMIT 1');
            $stmt->execute([$appointmentUuid]);
            $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$appointment || $appointment['user_uuid'] !== $userUuid) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized or appointment not found']);
                exit;
            }

            // Only allow deletion of cancelled appointments
            if ($appointment['status'] !== 'cancelled') {
                echo json_encode(['success' => false, 'message' => 'Only cancelled appointments can be deleted']);
                exit;
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error']);
            exit;
        }

        $response = Appointments::delete($appointmentUuid);
        echo json_encode($response);
        exit;
    }

    // Invalid action
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
    exit;
}

// Invalid request method
echo json_encode(['success' => false, 'message' => 'Invalid request method']);
?>
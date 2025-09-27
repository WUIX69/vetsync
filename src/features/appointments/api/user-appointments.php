<?php

include '../../../core/app.php';
apiHeaders();

use VetSync\Models\Appointments;
use VetSync\Services\SessionManager;
use Exception; // Add this line

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

// Handle POST requests 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    // Ensure user is logged in
    if (!$session->has()) {
        echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
        exit;
    }

    $userData = $session->get();
    $userUuid = $userData['uuid'];

    // NEW: Handle appointment booking (add action)
    if ($_POST['action'] === 'add') {
        $service_uuid = $_POST['service_uuid'] ?? null;
        $pet_uuid = $_POST['pet_uuid'] ?? null;
        $date = $_POST['date'] ?? null;
        $note = $_POST['note'] ?? '';
        $custom_service_request = $_POST['custom_service_request'] ?? null;

        // Validate required fields
        if (!$pet_uuid || !$date) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            exit;
        }

        // Validate service selection
        if (!$service_uuid || $service_uuid === '') {
            echo json_encode(['success' => false, 'message' => 'Please select a service']);
            exit;
        }

        // Handle "Others" custom service request
        if ($service_uuid === 'others') {
            if (!$custom_service_request || trim($custom_service_request) === '') {
                echo json_encode(['success' => false, 'message' => 'Please describe the custom service you need']);
                exit;
            }

            // Set service_uuid to NULL for custom services
            $service_uuid = null;
            // Prepend identifier to note
            $note = "CUSTOM SERVICE REQUEST: " . trim($custom_service_request) .
                ($note ? "\n\nAdditional Notes: " . $note : "");
        }

        // Validate pet belongs to user
        try {
            $stmt = $conn->prepare('SELECT user_uuid FROM pets WHERE uuid = ? AND user_uuid = ? LIMIT 1');
            $stmt->execute([$pet_uuid, $userUuid]);
            $pet = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$pet) {
                echo json_encode(['success' => false, 'message' => 'Invalid pet selection']);
                exit;
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error']);
            exit;
        }

        // Generate UUID for the appointment using the correct function
        $appointmentUuid = uuid(); // FIXED: Use uuid() instead of generateUuid()

        // Create appointment data with all required fields
        $appointmentData = [
            'uuid' => $appointmentUuid,
            'service_uuid' => $service_uuid,      // Can be null for custom services
            'user_uuid' => $userUuid,
            'pet_uuid' => $pet_uuid,
            'date' => $date,
            'note' => $note
        ];

        $response = Appointments::store($appointmentData);

        // Send confirmation email after successful booking
        if ($response['success']) {
            try {
                // Get user and pet details for email
                $emailStmt = $conn->prepare("
                    SELECT 
                        u.firstname, u.lastname, u.email,
                        p.name as pet_name,
                        s.name as service_name
                    FROM users u
                    JOIN pets p ON p.uuid = ? AND p.user_uuid = u.uuid
                    LEFT JOIN services s ON s.uuid = ?
                    WHERE u.uuid = ?
                ");
                $emailStmt->execute([$pet_uuid, $service_uuid, $userUuid]);
                $emailData = $emailStmt->fetch(PDO::FETCH_ASSOC);

                if ($emailData && $emailData['email']) {
                    $emailService = new \VetSync\Services\Email();
                    $userName = $emailData['firstname'] . ' ' . $emailData['lastname'];
                    $serviceName = $emailData['service_name'] ?: 'Custom Service Request';

                    $emailResult = $emailService->sendAppointmentConfirmation(
                        $emailData['email'],
                        $userName,
                        $emailData['pet_name'],
                        $serviceName,
                        $date,
                        null // time not set yet
                    );

                    // Log email result (optional)
                    if (!$emailResult['success']) {
                        error_log("Failed to send appointment confirmation email: " . $emailResult['message']);
                    }
                }
            } catch (Exception $e) {
                error_log("Email notification error: " . $e->getMessage());
                // Don't fail the appointment booking if email fails
            }
        }

        echo json_encode($response);
        exit;
    }

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
<?php
// Clean any output buffering
while (ob_get_level()) {
    ob_end_clean();
}

include '../../../core/app.php';

// Set headers
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

use VetSync\Models\Appointments;

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Ensure user is logged in
        global $session;
        if (!$session->has()) {
            echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
            exit;
        }

        $userData = $session->get();
        $userUuid = $userData['uuid'];

        $result = Appointments::getByUserUuid($userUuid);

        // Format the data to include additional info
        if ($result['success'] && !empty($result['data'])) {
            $result['data'] = array_map(function ($item) {
                // Safe formatting
                $formattedData = [
                    'pet_image' => !empty($item['pet_uuid']) ? media($item['pet_uuid']) : asset('img/placeholders/image.png'),
                    'formatted_date' => !empty($item['date']) ? date('F j, Y', strtotime($item['date'])) : 'Date not set',
                    'formatted_time' => !empty($item['time']) ? date('g:i A', strtotime($item['time'])) : 'No time set',
                ];

                // Merge formatted data with the original item
                return array_merge($item, $formattedData);
            }, $result['data']);
        }

        echo json_encode($result);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        global $session;

        // Ensure user is logged in
        if (!$session->has()) {
            echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
            exit;
        }

        $userData = $session->get();
        $userUuid = $userData['uuid'];

        if ($_POST['action'] === 'add_multiple') {
            $service_uuids = json_decode($_POST['service_uuids'] ?? '[]', true);
            $pet_uuid = $_POST['pet_uuid'] ?? null;
            $date = $_POST['date'] ?? null;
            $time = $_POST['time'] ?? null;
            $note = $_POST['note'] ?? '';
            $custom_service_request = $_POST['custom_service_request'] ?? null;

            // Validate required fields
            if (!$pet_uuid || !$date) {
                echo json_encode(['success' => false, 'message' => 'Missing required fields']);
                exit;
            }

            // Validate service selection
            if (empty($service_uuids) || !is_array($service_uuids)) {
                echo json_encode(['success' => false, 'message' => 'Please select at least one service']);
                exit;
            }

            // Validate pet belongs to user
            global $conn;
            $stmt = $conn->prepare('SELECT user_uuid FROM pets WHERE uuid = ? AND user_uuid = ? LIMIT 1');
            $stmt->execute([$pet_uuid, $userUuid]);
            $pet = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$pet) {
                echo json_encode(['success' => false, 'message' => 'Invalid pet selection']);
                exit;
            }

            // **NEW: Check if user already has an appointment for this date**
            $checkStmt = $conn->prepare('
                SELECT COUNT(*) as booking_count 
                FROM appointments 
                WHERE user_uuid = ? 
                AND DATE(date) = ? 
                AND status != "cancelled"
                LIMIT 1
            ');
            $checkStmt->execute([$userUuid, $date]);
            $existingBooking = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($existingBooking && $existingBooking['booking_count'] > 0) {
                echo json_encode([
                    'success' => false,
                    'message' => 'You already have an appointment booked for this date. Please choose a different date or contact us to modify your existing appointment.'
                ]);
                exit;
            }

            // Generate a shared booking group ID for multiple services
            $bookingGroupId = uuid();
            $appointmentCount = 0;
            $successCount = 0;

            try {
                // Begin transaction
                $conn->beginTransaction();

                foreach ($service_uuids as $service_uuid) {
                    $appointmentCount++;
                    $appointmentNote = $note;
                    $actualServiceUuid = $service_uuid;

                    // Handle "Others" custom service request
                    if ($service_uuid === 'others') {
                        if (!$custom_service_request || trim($custom_service_request) === '') {
                            throw new Exception('Please describe the custom service you need');
                        }
                        $actualServiceUuid = null;
                        $appointmentNote = "CUSTOM SERVICE REQUEST: " . trim($custom_service_request) .
                            ($note ? "\n\nAdditional Notes: " . $note : "");
                    }

                    // Generate UUID for each appointment
                    $appointmentUuid = uuid();

                    // Create appointment data
                    $appointmentData = [
                        'uuid' => $appointmentUuid,
                        'booking_group_id' => $bookingGroupId,
                        'service_uuid' => $actualServiceUuid,
                        'user_uuid' => $userUuid,
                        'pet_uuid' => $pet_uuid,
                        'date' => $date,
                        'time' => $time,
                        'note' => $appointmentNote
                    ];

                    $response = Appointments::storeWithGroup($appointmentData);
                    if ($response['success']) {
                        $successCount++;
                    }
                }

                // Commit transaction
                $conn->commit();

                if ($successCount === $appointmentCount) {
                    echo json_encode([
                        'success' => true,
                        'message' => $appointmentCount > 1
                            ? "Successfully booked {$appointmentCount} appointments! We will contact you to confirm."
                            : "Appointment booked successfully! We will contact you to confirm.",
                        'booking_group_id' => $bookingGroupId,
                        'count' => $successCount
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => "Only {$successCount} of {$appointmentCount} appointments were booked successfully."
                    ]);
                }
            } catch (Exception $e) {
                $conn->rollBack();
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            exit;
        }

        if ($_POST['action'] === 'add') {
            $service_uuid = $_POST['service_uuid'] ?? null;
            $pet_uuid = $_POST['pet_uuid'] ?? null;
            $date = $_POST['date'] ?? null;
            $time = $_POST['time'] ?? null;
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

                $service_uuid = null;
                $note = "CUSTOM SERVICE REQUEST: " . trim($custom_service_request) .
                    ($note ? "\n\nAdditional Notes: " . $note : "");
            }

            // Validate pet belongs to user
            global $conn;
            $stmt = $conn->prepare('SELECT user_uuid FROM pets WHERE uuid = ? AND user_uuid = ? LIMIT 1');
            $stmt->execute([$pet_uuid, $userUuid]);
            $pet = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$pet) {
                echo json_encode(['success' => false, 'message' => 'Invalid pet selection']);
                exit;
            }

            // Generate UUID for the appointment
            $appointmentUuid = uuid();

            // Create appointment data
            $appointmentData = [
                'uuid' => $appointmentUuid,
                'service_uuid' => $service_uuid,
                'user_uuid' => $userUuid,
                'pet_uuid' => $pet_uuid,
                'date' => $date,
                'time' => $time,
                'note' => $note
            ];

            $response = Appointments::store($appointmentData);
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

            // Check if appointment is within 2 days - PREVENT CANCELLATION
            global $conn;
            $stmt = $conn->prepare('SELECT date FROM appointments WHERE uuid = ? AND user_uuid = ? LIMIT 1');
            $stmt->execute([$appointmentUuid, $userUuid]);
            $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$appointment) {
                echo json_encode(['success' => false, 'message' => 'Appointment not found']);
                exit;
            }

            // Calculate days until appointment
            $appointmentDate = new DateTime($appointment['date']);
            $today = new DateTime();
            $today->setTime(0, 0, 0);
            $appointmentDate->setTime(0, 0, 0);

            $daysUntil = $today->diff($appointmentDate)->days;
            $isPast = $appointmentDate < $today;

            // Prevent cancellation if within 2 days or past
            if (!$isPast && $daysUntil < 2) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Cannot cancel appointment within 2 days of the scheduled date. Please contact us directly if you need to make changes.'
                ]);
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

            // Verify appointment belongs to current user
            global $conn;
            $stmt = $conn->prepare('SELECT user_uuid, status FROM appointments WHERE uuid = ? LIMIT 1');
            $stmt->execute([$appointmentUuid]);
            $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$appointment || $appointment['user_uuid'] !== $userUuid) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized or appointment not found']);
                exit;
            }

            if ($appointment['status'] !== 'cancelled') {
                echo json_encode(['success' => false, 'message' => 'Only cancelled appointments can be deleted']);
                exit;
            }

            $response = Appointments::delete($appointmentUuid);
            echo json_encode($response);
            exit;
        }

        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Invalid request method']);

} catch (Exception $e) {
    error_log("User Appointments API Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred: ' . $e->getMessage()
    ]);
} catch (Error $e) {
    error_log("User Appointments API Fatal Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'System error occurred'
    ]);
}
exit;
?>
<?php

// Prevent any output before JSON
ob_start();
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

include '../../../core/app.php';

// Clean any output that might have been generated
ob_clean();

// Set JSON headers
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

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

    if ($_POST['action'] === 'get_report_data') {
        $uuid = $_POST['uuid'] ?? null;
        if (!$uuid) {
            echo json_encode(['success' => false, 'message' => 'Missing appointment ID']);
            exit;
        }

        try {
            // Direct database query to avoid model issues
            global $conn;

            // Check if appointment exists and is completed
            $stmt = $conn->prepare("SELECT uuid, date, time, status FROM appointments WHERE uuid = ? AND status = 'completed' LIMIT 1");
            $stmt->execute([$uuid]);
            $basicAppointment = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$basicAppointment) {
                echo json_encode(['success' => false, 'message' => 'Completed appointment not found']);
                exit;
            }

            // Get full appointment details
            $stmt = $conn->prepare("
                SELECT 
                    a.uuid,
                    a.date,
                    a.time,
                    a.status,
                    a.note,
                    a.user_uuid,
                    a.pet_uuid,
                    a.service_uuid
                FROM appointments a
                WHERE a.uuid = ?
                LIMIT 1
            ");
            $stmt->execute([$uuid]);
            $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

            // Get user details
            $user = null;
            if ($appointment['user_uuid']) {
                $stmt = $conn->prepare("SELECT firstname, lastname, email, telephone FROM users WHERE uuid = ?");
                $stmt->execute([$appointment['user_uuid']]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            // Get pet details
            $pet = null;
            if ($appointment['pet_uuid']) {
                $stmt = $conn->prepare("SELECT name, breed, species, dob FROM pets WHERE uuid = ?");
                $stmt->execute([$appointment['pet_uuid']]);
                $pet = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            // Get service details
            $service = null;
            if ($appointment['service_uuid']) {
                $stmt = $conn->prepare("SELECT s.name, s.description, c.name as category_name FROM services s LEFT JOIN categories c ON s.category_id = c.id WHERE s.uuid = ?");
                $stmt->execute([$appointment['service_uuid']]);
                $service = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            // Calculate pet age
            $petAge = 'N/A';
            if ($pet && $pet['dob']) {
                try {
                    $dob = new DateTime($pet['dob']);
                    $now = new DateTime();
                    $age = $now->diff($dob)->y;
                    $petAge = $age . ' years old';
                } catch (Exception $e) {
                    $petAge = 'Born: ' . date('M j, Y', strtotime($pet['dob']));
                }
            }

            // Combine date and time
            $appointmentDateTime = $appointment['date'];
            if ($appointment['time']) {
                $appointmentDateTime .= ' ' . $appointment['time'];
            }

            // Prepare response
            $reportData = [
                'uuid' => $appointment['uuid'],
                'appointment_date' => $appointmentDateTime,
                'status' => $appointment['status'],
                'service_name' => $service ? $service['name'] : 'Custom Service',
                'service_description' => $service ? $service['description'] : null,
                'category_name' => $service ? $service['category_name'] : 'Custom',
                'owner_name' => $user ? ($user['firstname'] . ' ' . $user['lastname']) : 'N/A',
                'owner_email' => $user ? $user['email'] : 'N/A',
                'owner_phone' => $user ? $user['telephone'] : 'N/A',
                'pet_name' => $pet ? $pet['name'] : 'N/A',
                'pet_breed' => $pet ? $pet['breed'] : 'N/A',
                'pet_species' => $pet ? $pet['species'] : 'N/A',
                'pet_age' => $petAge,
                'note' => $appointment['note']
            ];

            echo json_encode(['success' => true, 'data' => $reportData]);
            exit;

        } catch (Exception $e) {
            error_log("Report Error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Database error occurred']);
            exit;
        }
    }
}

// Handle GET requests for fetching appointments
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? null;

    if ($action === 'get_by_date') {
        $date = $_GET['date'] ?? null;
        if (!$date) {
            echo json_encode(['success' => false, 'message' => 'Date parameter is required']);
            exit;
        }

        try {
            global $conn;

            // Query to get appointments for specific date with user and pet info (NO pet image)
            $stmt = $conn->prepare("
                SELECT 
                    a.uuid,
                    a.booking_group_id,
                    a.date,
                    a.time,
                    a.status,
                    a.note,
                    a.user_uuid,
                    a.pet_uuid,
                    a.service_uuid,
                    u.firstname as user_firstname,
                    u.lastname as user_lastname,
                    u.email as user_email,
                    p.name as pet_name,
                    s.name as service_name
                FROM appointments a
                LEFT JOIN users u ON a.user_uuid = u.uuid
                LEFT JOIN pets p ON a.pet_uuid = p.uuid  
                LEFT JOIN services s ON a.service_uuid = s.uuid
                WHERE DATE(a.date) = ?
                ORDER BY a.booking_group_id DESC, a.time ASC
            ");

            $stmt->execute([$date]);
            $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Format the data with default placeholder image
            $formattedAppointments = array_map(function ($appointment) {
                return [
                    'uuid' => $appointment['uuid'],
                    'booking_group_id' => $appointment['booking_group_id'] ?? null,
                    'date' => $appointment['date'],
                    'time' => $appointment['time'],
                    'status' => $appointment['status'],
                    'note' => $appointment['note'] ?? '',
                    'user_uuid' => $appointment['user_uuid'],
                    'pet_uuid' => $appointment['pet_uuid'],
                    'service_uuid' => $appointment['service_uuid'],
                    'user_name' => trim(($appointment['user_firstname'] ?? '') . ' ' . ($appointment['user_lastname'] ?? '')),
                    'user_email' => $appointment['user_email'] ?? '',
                    'pet_name' => $appointment['pet_name'] ?? 'Unknown Pet',
                    'pet_image' => '/public/img/placeholders/image.png', // Always use placeholder
                    'service_name' => $appointment['service_name'] ?? 'Custom Service',
                    'formatted_time' => $appointment['time'] ? date('g:i A', strtotime($appointment['time'])) : 'No time set',
                    'formatted_date' => $appointment['date'] ? date('F j, Y', strtotime($appointment['date'])) : 'No date set'
                ];
            }, $appointments);

            echo json_encode(['success' => true, 'data' => $formattedAppointments]);
            exit;

        } catch (Exception $e) {
            error_log("Get appointments by date error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }

    // Default GET behavior - get all appointments
    $response = VetSync\Models\Appointments::all();

    // Format time field for display
    if ($response['success'] && !empty($response['data'])) {
        $response['data'] = array_map(function ($appointment) {
            $appointment['formatted_time'] = !empty($appointment['time'])
                ? date('g:i A', strtotime($appointment['time']))
                : 'No time set';
            $appointment['formatted_date'] = !empty($appointment['date'])
                ? date('F j, Y', strtotime($appointment['date']))
                : 'No date set';
            return $appointment;
        }, $response['data']);
    }

    echo json_encode($response);
    exit;
}

// Invalid request method
echo json_encode(['success' => false, 'message' => 'Invalid request method']);
?>
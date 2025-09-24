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
    $response = VetSync\Models\Appointments::all();
    echo json_encode($response);
    exit;
}

// Invalid request method
echo json_encode(['success' => false, 'message' => 'Invalid request method']);
?>
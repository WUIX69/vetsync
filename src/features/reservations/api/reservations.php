<?php
// Debug - remove any output buffering and ensure clean output
while (ob_get_level()) {
    ob_end_clean();
}

include '../../../core/app.php';

// Set headers
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// Add error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors in output, log them instead

use VetSync\Models\Reservations;

try {
    // Handle GET requests (fetch reservations)
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $result = Reservations::all();

        // Ensure we have a valid result
        if (!is_array($result)) {
            throw new Exception('Invalid result from Reservations::all()');
        }

        echo json_encode($result);
        exit;
    }

    // Handle POST requests
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            if ($_POST['action'] === 'update_status') {
                $id = $_POST['id'] ?? null;
                $status = $_POST['status'] ?? null;
                $rejectionReason = $_POST['rejection_reason'] ?? null;
                $pickupNotes = $_POST['pickup_notes'] ?? '';
                $isNoShow = isset($_POST['is_no_show']) && $_POST['is_no_show'] == 1;

                if (!$id || !$status) {
                    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
                    exit;
                }

                // If rejecting/cancelling, require a reason
                if (($status === 'rejected' || $status === 'cancelled') && empty($rejectionReason)) {
                    echo json_encode(['success' => false, 'message' => 'Reason is required']);
                    exit;
                }

                // Handle different status updates
                if ($status === 'picked_up') {
                    $response = Reservations::markAsPickedUp($id, $pickupNotes);
                } else {
                    $response = Reservations::updateStatus($id, $status, $rejectionReason, $isNoShow);
                }

                echo json_encode($response);
                exit;
            }

            if ($_POST['action'] === 'cancel_reservation') {
                global $session;

                if (!$session->has()) {
                    echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
                    exit;
                }

                $userData = $session->get();
                $reservationId = $_POST['reservation_id'] ?? null;

                if (!$reservationId) {
                    echo json_encode(['success' => false, 'message' => 'Reservation ID is required']);
                    exit;
                }

                $response = Reservations::updateStatus($reservationId, 'rejected', 'Cancelled by user');
                if ($response['success']) {
                    $response['message'] = 'Reservation cancelled successfully';
                }
                echo json_encode($response);
                exit;
            }
        } else {
            // Handle reservation creation (from cart)
            global $session;

            if (!$session->has()) {
                echo json_encode(['success' => false, 'message' => 'You must be logged in to make a reservation.']);
                exit;
            }

            $userData = $session->get();
            $products = $_POST['products'] ?? null;
            $preferredDate = $_POST['preferred_date'] ?? null;
            $preferredTime = $_POST['preferred_time'] ?? null;

            if (!$products || !$preferredDate || !$preferredTime) {
                echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
                exit;
            }

            $data = [
                'id' => time() . rand(100, 999),
                'user_uuid' => $userData['uuid'],
                'products' => $products,
                'preferred_date' => $preferredDate,
                'preferred_time' => $preferredTime,
                'delivery_method' => $_POST['delivery_method'] ?? 'pickup',
                'notes' => $_POST['notes'] ?? null,
                'total_amount' => $_POST['total_amount'] ?? 0,
            ];

            $response = Reservations::store($data);
            echo json_encode($response);
            exit;
        }
    }

    // Default response for unsupported methods
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);

} catch (Exception $e) {
    error_log("Reservations API Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred: ' . $e->getMessage()
    ]);
} catch (Error $e) {
    error_log("Reservations API Fatal Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'System error occurred'
    ]);
}
exit;

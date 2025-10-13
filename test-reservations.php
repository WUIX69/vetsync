<?php

include '../../../core/app.php';
apiHeaders();

use VetSync\Models\Reservations;
use Exception;

$response = [];

try {
    // Handle admin actions (update_status)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        if ($_POST['action'] === 'update_status') {
            $id = $_POST['id'] ?? null;
            $status = $_POST['status'] ?? null;
            $rejectionReason = $_POST['rejection_reason'] ?? null;
            $pickupNotes = $_POST['pickup_notes'] ?? '';

            if (!$id || !$status) {
                echo json_encode(['success' => false, 'message' => 'Missing parameters']);
                exit;
            }

            // If rejecting, require a reason
            if ($status === 'rejected' && empty($rejectionReason)) {
                echo json_encode(['success' => false, 'message' => 'Rejection reason is required']);
                exit;
            }

            // Handle different status updates
            if ($status === 'picked_up') {
                $response = Reservations::markAsPickedUp($id, $pickupNotes);
            } else {
                $response = Reservations::updateStatus($id, $status, $rejectionReason);
            }

            echo json_encode($response);
            exit;
        }

        // Handle user cancellation using "rejected" status
        if ($_POST['action'] === 'cancel_reservation') {
            global $session;

            if (!$session->has()) {
                echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
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

            $reservationId = $_POST['reservation_id'] ?? null;

            if (!$reservationId) {
                echo json_encode(['success' => false, 'message' => 'Reservation ID is required']);
                exit;
            }

            // Verify the reservation belongs to the user and is pending
            $reservation = Reservations::getById($reservationId);
            if (!$reservation['success']) {
                echo json_encode(['success' => false, 'message' => 'Reservation not found']);
                exit;
            }

            if ($reservation['data']['user_uuid'] !== $userData['uuid']) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                exit;
            }

            if ($reservation['data']['status'] !== 'pending') {
                echo json_encode(['success' => false, 'message' => 'Only pending reservations can be cancelled']);
                exit;
            }

            // Update status to "rejected" with cancellation reason
            $response = Reservations::updateStatus($reservationId, 'rejected', 'Cancelled by user');
            if ($response['success']) {
                $response['message'] = 'Reservation cancelled successfully';
            }
            echo json_encode($response);
            exit;
        }
    }

    // Handle reservation creation (from cart)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action'])) {
        global $session;

        if (!$session->has()) {
            echo json_encode(['success' => false, 'message' => 'You must be logged in to make a reservation.']);
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
        $products = $_POST['products'] ?? null;
        $preferredDate = $_POST['preferred_date'] ?? null;
        $preferredTime = $_POST['preferred_time'] ?? null;

        if (!$products) {
            echo json_encode(['success' => false, 'message' => 'No products selected.']);
            exit;
        }

        if (!$preferredDate) {
            echo json_encode(['success' => false, 'message' => 'Please select a preferred date.']);
            exit;
        }

        if (!$preferredTime) {
            echo json_encode(['success' => false, 'message' => 'Please select a preferred time.']);
            exit;
        }

        $data = [
            'id' => time() . rand(100, 999), // Simple ID generation
            'user_uuid' => $userData['uuid'],
            'products' => $products, // Already JSON encoded from frontend
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

    // Handle GET requests (fetch reservations)
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $result = Reservations::all();
        echo json_encode($result);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Invalid request method']);

} catch (Exception $e) {
    error_log("Reservations API Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred.'
    ]);
} catch (Error $e) {
    error_log("Reservations API Fatal Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'System error occurred.'
    ]);
}
exit;
php:test-reservations.php
<code_block_to_apply_changes_from>
<?php
include_once __DIR__ . '/src/core/app.php';

echo "<h2>Testing Reservations</h2>";

try {
    use VetSync\Models\Reservations;
    
    echo "<h3>1. Testing Database Connection</h3>";
    global $conn;
    if ($conn) {
        echo "✅ Database connection OK<br>";
    } else {
        echo "❌ Database connection failed<br>";
        exit;
    }
    
    echo "<h3>2. Testing Reservations Model</h3>";
    $result = Reservations::all();
    
    echo "Result: ";
    echo "<pre>";
    var_dump($result);
    echo "</pre>";
    
    if ($result['success']) {
        echo "✅ Reservations model working<br>";
        echo "Found " . count($result['data']) . " reservations<br>";
        
        if (!empty($result['data'])) {
            echo "<h3>3. Sample Reservation Data</h3>";
            echo "<pre>";
            print_r($result['data'][0]);
            echo "</pre>";
        }
    } else {
        echo "❌ Reservations model error: " . $result['message'] . "<br>";
    }
    
    echo "<h3>4. Testing Raw Query</h3>";
    $stmt = $conn->prepare('SELECT COUNT(*) as count FROM reservations');
    $stmt->execute();
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Raw count query result: " . $count['count'] . " reservations in database<br>";
    
    // Test table structure
    echo "<h3>5. Table Structure</h3>";
    $stmt = $conn->prepare('DESCRIBE reservations');
    $stmt->execute();
    $structure = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($structure);
    echo "</pre>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "Stack trace:<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>

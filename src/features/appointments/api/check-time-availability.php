<?php

include '../../../core/app.php';
apiHeaders();

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        global $conn;

        $date = $_GET['date'] ?? '';

        if (empty($date)) {
            throw new Exception('Date is required');
        }

        // Define all available time slots (9 AM - 8 PM, excluding 12 PM lunch)
        $allTimeSlots = [
            '09:00:00' => '9:00 AM',
            '10:00:00' => '10:00 AM',
            '11:00:00' => '11:00 AM',
            // 12:00 PM is lunch - skipped
            '13:00:00' => '1:00 PM',
            '14:00:00' => '2:00 PM',
            '15:00:00' => '3:00 PM',
            '16:00:00' => '4:00 PM',
            '17:00:00' => '5:00 PM',
            '18:00:00' => '6:00 PM',
            '19:00:00' => '7:00 PM',
            '20:00:00' => '8:00 PM'
        ];

        // Get booked time slots for the specified date
        $stmt = $conn->prepare("
            SELECT time, COUNT(*) as booking_count
            FROM appointments
            WHERE DATE(date) = ?
            AND status != 'cancelled'
            AND time IS NOT NULL
            GROUP BY time
        ");

        $stmt->execute([$date]);
        $bookedSlots = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        // Build available slots array
        $availableSlots = [];
        foreach ($allTimeSlots as $time => $label) {
            $availableSlots[] = [
                'value' => $time,
                'label' => $label,
                'available' => !isset($bookedSlots[$time]) // If booked, mark as unavailable
            ];
        }

        $response = [
            'success' => true,
            'time_slots' => $availableSlots
        ];

    } catch (Exception $e) {
        error_log("Check time availability error: " . $e->getMessage());
        $response = [
            'success' => false,
            'message' => 'Error checking time availability: ' . $e->getMessage()
        ];
    }
} else {
    $response = ['success' => false, 'message' => 'Invalid request method'];
}

echo json_encode($response);
exit;

<?php

include '../../../core/app.php';
apiHeaders();

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        global $conn;

        // Get dates from today onwards for the next 90 days
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime('+90 days'));

        // Check which dates are fully booked (all time slots taken)
        // We'll calculate this based on total booked minutes vs available minutes
        $stmt = $conn->prepare("
            SELECT 
                DATE(a.date) as appointment_date,
                SUM(COALESCE(s.duration, 60)) as total_booked_minutes
            FROM appointments a
            LEFT JOIN services s ON a.service_uuid = s.uuid
            WHERE DATE(a.date) BETWEEN ? AND ?
            AND a.status != 'cancelled'
            GROUP BY DATE(a.date)
            HAVING SUM(COALESCE(s.duration, 60)) >= 600
        ");

        $stmt->execute([$startDate, $endDate]);
        $fullyBookedDates = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Convert to array of dates
        // 600 minutes = 10 hours (9 AM - 8 PM with 1 hour lunch = 10 available hours)
        $disabledDates = array_map(function ($row) {
            return $row['appointment_date'];
        }, $fullyBookedDates);

        $response = [
            'success' => true,
            'disabled_dates' => $disabledDates,
            'capacity_type' => 'time_based',
            'available_hours_per_day' => 10
        ];

    } catch (PDOException $e) {
        error_log("Check availability error: " . $e->getMessage());
        $response = [
            'success' => false,
            'message' => 'Error checking availability: ' . $e->getMessage()
        ];
    }
} else {
    $response = ['success' => false, 'message' => 'Invalid request method'];
}

echo json_encode($response);
exit;
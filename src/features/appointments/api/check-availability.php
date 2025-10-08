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

        // Count unique booking groups per date (grouped appointments count as 1)
        // For appointments without a booking_group_id, count them individually
        $stmt = $conn->prepare("
            SELECT 
                DATE(date) as appointment_date,
                COUNT(DISTINCT COALESCE(booking_group_id, uuid)) as appointment_count
            FROM appointments
            WHERE DATE(date) BETWEEN ? AND ?
            AND status != 'cancelled'
            GROUP BY DATE(date)
            HAVING COUNT(DISTINCT COALESCE(booking_group_id, uuid)) >= 5
        ");

        $stmt->execute([$startDate, $endDate]);
        $fullyBookedDates = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Convert to array of dates
        $disabledDates = array_map(function ($row) {
            return $row['appointment_date'];
        }, $fullyBookedDates);

        $response = [
            'success' => true,
            'disabled_dates' => $disabledDates,
            'max_per_day' => 5
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
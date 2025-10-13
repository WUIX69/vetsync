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

// Handle only GET requests for calendar data
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    // Get month and year from query parameters
    $month = $_GET['month'] ?? date('n'); // Current month if not specified
    $year = $_GET['year'] ?? date('Y');   // Current year if not specified

    // Validate month and year
    $month = intval($month);
    $year = intval($year);

    if ($month < 1 || $month > 12 || $year < 2020 || $year > 2030) {
        echo json_encode(['success' => false, 'message' => 'Invalid month or year']);
        exit;
    }

    // Get database connection
    global $conn;
    if (!$conn) {
        echo json_encode(['success' => false, 'message' => 'Database connection not available']);
        exit;
    }

    // Query to get appointment counts by date for the specified month/year
    $stmt = $conn->prepare("
        SELECT 
            DATE(date) as appointment_date,
            COUNT(*) as appointment_count,
            GROUP_CONCAT(DISTINCT status) as statuses
        FROM appointments 
        WHERE YEAR(date) = ? AND MONTH(date) = ?
        GROUP BY DATE(date)
        ORDER BY DATE(date)
    ");

    $stmt->execute([$year, $month]);
    $appointmentCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format the data for easy lookup by day
    $calendarData = [];
    foreach ($appointmentCounts as $row) {
        $day = intval(date('j', strtotime($row['appointment_date'])));
        $calendarData[$day] = [
            'count' => intval($row['appointment_count']),
            'date' => $row['appointment_date'],
            'statuses' => explode(',', $row['statuses'])
        ];
    }

    // Get month info
    $monthName = date('F Y', mktime(0, 0, 0, $month, 1, $year));
    $daysInMonth = date('t', mktime(0, 0, 0, $month, 1, $year));
    $firstDayOfWeek = date('w', mktime(0, 0, 0, $month, 1, $year)); // 0 = Sunday

    echo json_encode([
        'success' => true,
        'data' => [
            'month' => $month,
            'year' => $year,
            'monthName' => $monthName,
            'daysInMonth' => $daysInMonth,
            'firstDayOfWeek' => $firstDayOfWeek,
            'appointments' => $calendarData
        ]
    ]);

} catch (Exception $e) {
    error_log("Calendar API Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error loading calendar data']);
}
?>
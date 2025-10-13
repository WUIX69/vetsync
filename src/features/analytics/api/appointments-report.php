<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display, but log
ini_set('log_errors', 1);

include '../../../core/app.php';

header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

try {
    global $conn;

    if (!$conn) {
        throw new Exception('Database connection not available');
    }

    // Get month filter (format: YYYY-MM, default to current month)
    $filterMonth = $_GET['month'] ?? date('Y-m');
    $monthYear = explode('-', $filterMonth);
    $filterYear = intval($monthYear[0]);
    $filterMonthNum = intval($monthYear[1]);

    // Validate month and year
    if ($filterMonthNum < 1 || $filterMonthNum > 12 || $filterYear < 2020 || $filterYear > 2030) {
        throw new Exception('Invalid month or year');
    }

    // Fetch all appointments for the selected month
    $stmt = $conn->prepare("
        SELECT 
            a.uuid,
            DATE_FORMAT(a.date, '%b %d, %Y') as appointment_date,
            COALESCE(a.time, 'Not set') as time,
            a.status,
            a.note,
            COALESCE(p.name, 'Unknown') as pet_name,
            COALESCE(CONCAT(u.firstname, ' ', u.lastname), u.email) as user_name,
            u.email as user_email,
            COALESCE(s.name, 'Custom Service') as service_name
        FROM appointments a
        LEFT JOIN pets p ON a.pet_uuid = p.uuid
        LEFT JOIN users u ON a.user_uuid = u.uuid
        LEFT JOIN services s ON a.service_uuid = s.uuid
        WHERE MONTH(a.date) = ? AND YEAR(a.date) = ?
        ORDER BY a.date ASC, 
                 CASE WHEN a.time IS NULL THEN 1 ELSE 0 END,
                 a.time ASC
    ");

    $stmt->execute([$filterMonthNum, $filterYear]);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $appointments,
        'count' => count($appointments),
        'month' => $filterMonth
    ]);

} catch (PDOException $e) {
    error_log("Appointments Report PDO Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage(),
        'error_type' => 'PDO'
    ]);
} catch (Exception $e) {
    error_log("Appointments Report API Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error_type' => 'General'
    ]);
}
?>
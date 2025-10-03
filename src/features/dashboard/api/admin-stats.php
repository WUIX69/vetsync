<?php

include '../../../core/app.php';
apiHeaders();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    // Get overall pending count (pending appointments + pending reservations)
    $pendingAppointmentsStmt = $conn->prepare("SELECT COUNT(*) as count FROM appointments WHERE status = 'pending'");
    $pendingAppointmentsStmt->execute();
    $pendingAppointments = $pendingAppointmentsStmt->fetch(PDO::FETCH_ASSOC)['count'];

    $pendingReservationsStmt = $conn->prepare("SELECT COUNT(*) as count FROM reservations WHERE status = 'pending'");
    $pendingReservationsStmt->execute();
    $pendingReservations = $pendingReservationsStmt->fetch(PDO::FETCH_ASSOC)['count'];

    $overallPending = $pendingAppointments + $pendingReservations;

    // Get pending appointments this month
    $firstDayOfMonth = date('Y-m-01');
    $lastDayOfMonth = date('Y-m-t');

    $pendingAppointmentsMonthStmt = $conn->prepare("
        SELECT COUNT(*) as count 
        FROM appointments 
        WHERE status = 'pending'
        AND date >= ? 
        AND date <= ?
    ");
    $pendingAppointmentsMonthStmt->execute([$firstDayOfMonth, $lastDayOfMonth]);
    $pendingAppointmentsMonth = $pendingAppointmentsMonthStmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Get pending product reservations this month
    $pendingReservationsMonthStmt = $conn->prepare("
        SELECT COUNT(*) as count 
        FROM reservations 
        WHERE status = 'pending'
        AND created_at >= ? 
        AND created_at <= ?
    ");
    $pendingReservationsMonthStmt->execute([$firstDayOfMonth, $lastDayOfMonth . ' 23:59:59']);
    $pendingReservationsMonth = $pendingReservationsMonthStmt->fetch(PDO::FETCH_ASSOC)['count'];

    $response = [
        'success' => true,
        'data' => [
            'overall_pending' => $overallPending,
            'pending_appointments_month' => $pendingAppointmentsMonth,
            'pending_reservations_month' => $pendingReservationsMonth
        ]
    ];

} catch (PDOException $e) {
    error_log("Admin Stats Error: " . $e->getMessage());
    $response = [
        'success' => false,
        'message' => 'Database error occurred'
    ];
} catch (Exception $e) {
    error_log("Admin Stats General Error: " . $e->getMessage());
    $response = [
        'success' => false,
        'message' => 'An error occurred while fetching stats'
    ];
}

echo json_encode($response);
exit;
?>
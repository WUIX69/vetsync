<?php

include '../../../core/app.php';
apiHeaders();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    // Get pending appointments only (last 5)
    $stmt = $conn->prepare("
        SELECT 
            a.uuid,
            a.date,
            a.time,
            a.status,
            a.created_at,
            p.name as pet_name,
            s.name as service_name,
            u.firstname,
            u.lastname,
            CONCAT(u.firstname, ' ', u.lastname) as user_name
        FROM appointments a
        LEFT JOIN pets p ON a.pet_uuid = p.uuid
        LEFT JOIN services s ON a.service_uuid = s.uuid
        LEFT JOIN users u ON a.user_uuid = u.uuid
        WHERE a.status = 'pending'
        ORDER BY a.created_at DESC
        LIMIT 5
    ");

    $stmt->execute();
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format the appointments
    $formattedAppointments = [];
    foreach ($appointments as $appointment) {
        $formattedAppointments[] = [
            'uuid' => $appointment['uuid'],
            'pet_name' => $appointment['pet_name'] ?: 'Unknown Pet',
            'service_name' => $appointment['service_name'] ?: 'Custom Service',
            'user_name' => $appointment['user_name'] ?: 'Unknown User',
            'date' => $appointment['date'],
            'time' => $appointment['time'],
            'status' => $appointment['status'],
            'created_at' => $appointment['created_at']
        ];
    }

    $response = [
        'success' => true,
        'data' => $formattedAppointments
    ];

} catch (PDOException $e) {
    error_log("Pending Appointments Error: " . $e->getMessage());
    $response = [
        'success' => false,
        'message' => 'Database error occurred'
    ];
} catch (Exception $e) {
    error_log("Pending Appointments General Error: " . $e->getMessage());
    $response = [
        'success' => false,
        'message' => 'An error occurred while fetching pending appointments'
    ];
}

echo json_encode($response);
exit;
?>
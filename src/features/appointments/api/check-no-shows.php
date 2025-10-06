<?php

include '../../../core/app.php';
apiHeaders();

$response = [];

// This endpoint should be called by a cron job or manually by admin
// It checks for appointments that passed their date and are still "accepted" status

try {
    global $conn;

    // Get all accepted appointments that are past their date
    $stmt = $conn->prepare("
        SELECT uuid, user_uuid, date, service_uuid
        FROM appointments
        WHERE status = 'accepted'
        AND DATE(date) < CURDATE()
    ");

    $stmt->execute();
    $noShows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $processedCount = 0;

    foreach ($noShows as $appointment) {
        // Mark as no-show
        $updateStmt = $conn->prepare("
            UPDATE appointments 
            SET status = 'cancelled',
                cancellation_reason = 'NO SHOW - Did not attend scheduled appointment',
                updated_at = NOW()
            WHERE uuid = ?
        ");

        $updateStmt->execute([$appointment['uuid']]);

        // Reduce user health by 20%
        $healthStmt = $conn->prepare("
            UPDATE users 
            SET health = GREATEST(0, health - 20)
            WHERE uuid = ?
        ");

        $healthStmt->execute([$appointment['user_uuid']]);

        $processedCount++;
    }

    $response = [
        'success' => true,
        'message' => "Processed $processedCount no-show appointments",
        'processed' => $processedCount
    ];

} catch (PDOException $e) {
    error_log("No-show check error: " . $e->getMessage());
    $response = [
        'success' => false,
        'message' => 'Error checking no-shows: ' . $e->getMessage()
    ];
}

echo json_encode($response);
exit;

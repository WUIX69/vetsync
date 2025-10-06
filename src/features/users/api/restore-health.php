<?php

include '../../../core/app.php';
apiHeaders();

// This should be called by a cron job daily or triggered manually by admin

$response = [];

try {
    global $conn;

    // Restore 2% health per day for all users below 100%
    // But only if they haven't had a no-show in the last 7 days

    $stmt = $conn->prepare("
        UPDATE users 
        SET health = LEAST(100, health + 2)
        WHERE health < 100
        AND uuid NOT IN (
            SELECT DISTINCT user_uuid 
            FROM appointments 
            WHERE status = 'cancelled' 
            AND (cancellation_reason LIKE '%NO SHOW%' OR cancellation_reason LIKE '%no show%')
            AND updated_at > DATE_SUB(NOW(), INTERVAL 7 DAY)
        )
        AND uuid NOT IN (
            SELECT DISTINCT user_uuid 
            FROM reservations 
            WHERE cancellation_reason LIKE '%NO SHOW%'
            AND updated_at > DATE_SUB(NOW(), INTERVAL 7 DAY)
        )
    ");

    $stmt->execute();
    $affectedUsers = $stmt->rowCount();

    $response = [
        'success' => true,
        'message' => "Health restored for $affectedUsers users (+2% daily recovery)",
        'affected' => $affectedUsers
    ];

} catch (PDOException $e) {
    error_log("Health restoration error: " . $e->getMessage());
    $response = [
        'success' => false,
        'message' => 'Error restoring health: ' . $e->getMessage()
    ];
}

echo json_encode($response);
exit;

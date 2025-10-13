<?php
include '../../../core/app.php';
header('Content-Type: application/json');

try {
    global $conn;

    // Simple test query
    $stmt = $conn->prepare('SELECT * FROM reservations LIMIT 5');
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'message' => 'Database connection working',
        'data' => $results,
        'count' => count($results)
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>
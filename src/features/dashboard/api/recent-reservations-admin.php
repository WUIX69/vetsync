<?php

include '../../../core/app.php';
apiHeaders();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    // Get pending reservations only (last 8)
    $stmt = $conn->prepare("
        SELECT 
            r.id,
            r.total_amount,
            r.status,
            r.created_at,
            r.products,
            u.firstname,
            u.lastname,
            CONCAT(u.firstname, ' ', u.lastname) as user_name
        FROM reservations r
        LEFT JOIN users u ON r.user_uuid = u.uuid
        WHERE r.status = 'pending'
        ORDER BY r.created_at DESC
        LIMIT 8
    ");

    $stmt->execute();
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format the reservations
    $formattedReservations = [];
    foreach ($reservations as $reservation) {
        // Parse products to get first product name
        $products = [];
        if ($reservation['products']) {
            try {
                $products = json_decode($reservation['products'], true);
            } catch (Exception $e) {
                // Handle JSON decode error
            }
        }

        $productName = 'Unknown Product';
        $productCount = count($products);

        if ($productCount > 0) {
            $productName = $products[0]['name'] ?? 'Unknown Product';
            if ($productCount > 1) {
                $productName .= " (+" . ($productCount - 1) . " more)";
            }
        }

        $formattedReservations[] = [
            'id' => $reservation['id'],
            'user_name' => $reservation['user_name'] ?: 'Unknown User',
            'product_name' => $productName,
            'total_amount' => $reservation['total_amount'],
            'status' => $reservation['status'],
            'created_at' => $reservation['created_at'],
            'time_ago' => getTimeAgo($reservation['created_at'])
        ];
    }

    $response = [
        'success' => true,
        'data' => $formattedReservations
    ];

} catch (PDOException $e) {
    error_log("Pending Reservations Error: " . $e->getMessage());
    $response = [
        'success' => false,
        'message' => 'Database error occurred'
    ];
} catch (Exception $e) {
    error_log("Pending Reservations General Error: " . $e->getMessage());
    $response = [
        'success' => false,
        'message' => 'An error occurred while fetching pending reservations'
    ];
}

function getTimeAgo($datetime)
{
    $time = time() - strtotime($datetime);

    if ($time < 60)
        return 'Just now';
    if ($time < 3600)
        return floor($time / 60) . ' mins ago';
    if ($time < 86400)
        return floor($time / 3600) . ' hours ago';
    if ($time < 2592000)
        return floor($time / 86400) . ' days ago';
    if ($time < 31536000)
        return floor($time / 2592000) . ' months ago';
    return floor($time / 31536000) . ' years ago';
}

echo json_encode($response);
exit;
?>
<?php
include '../../../core/app.php';

header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

try {
    global $conn;

    $analytics = [];

    // 1. ALL APPOINTMENTS
    $allAppointmentsStmt = $conn->prepare("SELECT COUNT(*) as total FROM appointments WHERE status != 'cancelled'");
    $allAppointmentsStmt->execute();
    $analytics['all_appointments'] = intval($allAppointmentsStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

    // 2. PENDING APPOINTMENTS THIS MONTH
    $pendingMonthStmt = $conn->prepare("
        SELECT COUNT(*) as total 
        FROM appointments 
        WHERE status = 'pending'
        AND MONTH(date) = MONTH(NOW()) 
        AND YEAR(date) = YEAR(NOW())
    ");
    $pendingMonthStmt->execute();
    $analytics['pending_month'] = intval($pendingMonthStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

    // 3. PENDING APPOINTMENTS TODAY
    $pendingTodayStmt = $conn->prepare("
        SELECT COUNT(*) as total 
        FROM appointments 
        WHERE status = 'pending'
        AND DATE(date) = CURDATE()
    ");
    $pendingTodayStmt->execute();
    $analytics['pending_today'] = intval($pendingTodayStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

    // 4. COMPLETED APPOINTMENTS (ALL TIME)
    $completedAllStmt = $conn->prepare("SELECT COUNT(*) as total FROM appointments WHERE status = 'completed'");
    $completedAllStmt->execute();
    $analytics['completed_all'] = intval($completedAllStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

    // 5. COMPLETED APPOINTMENTS THIS MONTH
    $completedMonthStmt = $conn->prepare("
        SELECT COUNT(*) as total 
        FROM appointments 
        WHERE status = 'completed'
        AND MONTH(date) = MONTH(NOW()) 
        AND YEAR(date) = YEAR(NOW())
    ");
    $completedMonthStmt->execute();
    $analytics['completed_month'] = intval($completedMonthStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

    // 6. COMPLETED APPOINTMENTS TODAY
    $completedTodayStmt = $conn->prepare("
        SELECT COUNT(*) as total 
        FROM appointments 
        WHERE status = 'completed'
        AND DATE(date) = CURDATE()
    ");
    $completedTodayStmt->execute();
    $analytics['completed_today'] = intval($completedTodayStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

    // 7. CANCELLED APPOINTMENTS (ALL TIME)
    $cancelledAllStmt = $conn->prepare("SELECT COUNT(*) as total FROM appointments WHERE status = 'cancelled'");
    $cancelledAllStmt->execute();
    $analytics['cancelled_all'] = intval($cancelledAllStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

    // 8. CANCELLED APPOINTMENTS THIS MONTH
    $cancelledMonthStmt = $conn->prepare("
        SELECT COUNT(*) as total 
        FROM appointments 
        WHERE status = 'cancelled'
        AND MONTH(date) = MONTH(NOW()) 
        AND YEAR(date) = YEAR(NOW())
    ");
    $cancelledMonthStmt->execute();
    $analytics['cancelled_month'] = intval($cancelledMonthStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

    // 9. CANCELLED APPOINTMENTS TODAY
    $cancelledTodayStmt = $conn->prepare("
        SELECT COUNT(*) as total 
        FROM appointments 
        WHERE status = 'cancelled'
        AND DATE(date) = CURDATE()
    ");
    $cancelledTodayStmt->execute();
    $analytics['cancelled_today'] = intval($cancelledTodayStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

    // TOTAL ORDERS (for charts)
    $ordersStmt = $conn->prepare("SELECT COUNT(*) as total_orders FROM reservations");
    $ordersStmt->execute();
    $analytics['total_reservations'] = intval($ordersStmt->fetch(PDO::FETCH_ASSOC)['total_orders'] ?? 0);

    // TOP SALES - Most Sold Products by Total Quantity
    $reservationsStmt = $conn->prepare("
        SELECT products, total_amount
        FROM reservations 
        WHERE status IN ('picked_up', 'ready_for_pickup', 'accepted')
        AND products IS NOT NULL
    ");
    $reservationsStmt->execute();
    $reservationsResults = $reservationsStmt->fetchAll(PDO::FETCH_ASSOC);

    $productSales = [];

    foreach ($reservationsResults as $reservation) {
        $products = json_decode($reservation['products'], true);
        if ($products && is_array($products)) {
            foreach ($products as $product) {
                $productName = $product['name'] ?? 'Unknown Product';
                $quantity = intval($product['qty'] ?? 1);

                if (!isset($productSales[$productName])) {
                    $productSales[$productName] = [
                        'product_name' => $productName,
                        'total_quantity' => 0,
                    ];
                }

                $productSales[$productName]['total_quantity'] += $quantity;
            }
        }
    }

    // Sort and get top 5
    usort($productSales, function ($a, $b) {
        return $b['total_quantity'] - $a['total_quantity'];
    });
    $analytics['top_sales'] = array_slice($productSales, 0, 5);

    // MOST BOOKED SERVICES
    $servicesStmt = $conn->prepare("
        SELECT s.name as service_name, COUNT(a.uuid) as booking_count
        FROM services s
        LEFT JOIN appointments a ON s.uuid = a.service_uuid
        WHERE a.status != 'cancelled'
        GROUP BY s.uuid, s.name
        ORDER BY booking_count DESC
        LIMIT 5
    ");
    $servicesStmt->execute();
    $analytics['top_services'] = $servicesStmt->fetchAll(PDO::FETCH_ASSOC);

    // ORDER STATUS DISTRIBUTION
    $statusStmt = $conn->prepare("
        SELECT status, COUNT(*) as count
        FROM reservations
        GROUP BY status
    ");
    $statusStmt->execute();
    $analytics['order_status'] = $statusStmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $analytics]);
} catch (Exception $e) {
    error_log("Analytics API Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error fetching analytics data']);
}
?>
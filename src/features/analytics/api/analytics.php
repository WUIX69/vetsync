<?php
include '../../../core/app.php';

header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

try {
    global $conn;

    // Get month filter from query parameter (format: YYYY-MM)
    $filterMonth = $_GET['month'] ?? date('Y-m'); // Default to current month
    $monthYear = explode('-', $filterMonth);
    $filterYear = intval($monthYear[0]);
    $filterMonthNum = intval($monthYear[1]);

    $analytics = [];

    // 1. ALL APPOINTMENTS IN SELECTED MONTH
    $allAppointmentsStmt = $conn->prepare("
        SELECT COUNT(*) as total FROM appointments 
        WHERE status != 'cancelled'
        AND MONTH(date) = ? AND YEAR(date) = ?
    ");
    $allAppointmentsStmt->execute([$filterMonthNum, $filterYear]);
    $analytics['all_appointments'] = intval($allAppointmentsStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

    // 2. PENDING APPOINTMENTS IN SELECTED MONTH
    $pendingMonthStmt = $conn->prepare("
        SELECT COUNT(*) as total 
        FROM appointments 
        WHERE status = 'pending'
        AND MONTH(date) = ? AND YEAR(date) = ?
    ");
    $pendingMonthStmt->execute([$filterMonthNum, $filterYear]);
    $analytics['pending_month'] = intval($pendingMonthStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

    // 3. COMPLETED APPOINTMENTS IN SELECTED MONTH
    $completedAllStmt = $conn->prepare("
        SELECT COUNT(*) as total FROM appointments 
        WHERE status = 'completed'
        AND MONTH(date) = ? AND YEAR(date) = ?
    ");
    $completedAllStmt->execute([$filterMonthNum, $filterYear]);
    $analytics['completed_all'] = intval($completedAllStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

    // 4. CANCELLED APPOINTMENTS IN SELECTED MONTH
    $cancelledAllStmt = $conn->prepare("
        SELECT COUNT(*) as total FROM appointments 
        WHERE status = 'cancelled'
        AND MONTH(date) = ? AND YEAR(date) = ?
    ");
    $cancelledAllStmt->execute([$filterMonthNum, $filterYear]);
    $analytics['cancelled_all'] = intval($cancelledAllStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

    // BOTTOM ROW STATS - ALL BASED ON SELECTED MONTH NOW

    // 5. PENDING ON FIRST DAY OF SELECTED MONTH
    $pendingTodayStmt = $conn->prepare("
        SELECT COUNT(*) as total 
        FROM appointments 
        WHERE status = 'pending'
        AND MONTH(date) = ? AND YEAR(date) = ?
        AND DAY(date) = 1
    ");
    $pendingTodayStmt->execute([$filterMonthNum, $filterYear]);
    $analytics['pending_today'] = intval($pendingTodayStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

    // 6. COMPLETED IN SELECTED MONTH (same as #3)
    $analytics['completed_month'] = $analytics['completed_all'];

    // 7. COMPLETED ON FIRST DAY OF SELECTED MONTH
    $completedTodayStmt = $conn->prepare("
        SELECT COUNT(*) as total 
        FROM appointments 
        WHERE status = 'completed'
        AND MONTH(date) = ? AND YEAR(date) = ?
        AND DAY(date) = 1
    ");
    $completedTodayStmt->execute([$filterMonthNum, $filterYear]);
    $analytics['completed_today'] = intval($completedTodayStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

    // 8. CANCELLED IN SELECTED MONTH (same as #4)
    $analytics['cancelled_month'] = $analytics['cancelled_all'];

    // 9. CANCELLED ON FIRST DAY OF SELECTED MONTH
    $cancelledTodayStmt = $conn->prepare("
        SELECT COUNT(*) as total 
        FROM appointments 
        WHERE status = 'cancelled'
        AND MONTH(date) = ? AND YEAR(date) = ?
        AND DAY(date) = 1
    ");
    $cancelledTodayStmt->execute([$filterMonthNum, $filterYear]);
    $analytics['cancelled_today'] = intval($cancelledTodayStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

    // TOP SALES - FILTERED BY SELECTED MONTH
    $reservationsStmt = $conn->prepare("
        SELECT products, total_amount
        FROM reservations 
        WHERE status IN ('picked_up', 'ready_for_pickup', 'accepted')
        AND products IS NOT NULL
        AND MONTH(created_at) = ? AND YEAR(created_at) = ?
    ");
    $reservationsStmt->execute([$filterMonthNum, $filterYear]);
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

    usort($productSales, function ($a, $b) {
        return $b['total_quantity'] - $a['total_quantity'];
    });
    $analytics['top_sales'] = array_slice($productSales, 0, 5);

    // MOST BOOKED SERVICES - FILTERED BY SELECTED MONTH
    $servicesStmt = $conn->prepare("
        SELECT s.name as service_name, COUNT(a.uuid) as booking_count
        FROM services s
        LEFT JOIN appointments a ON s.uuid = a.service_uuid 
            AND a.status != 'cancelled'
            AND MONTH(a.date) = ? AND YEAR(a.date) = ?
        GROUP BY s.uuid, s.name
        HAVING booking_count > 0
        ORDER BY booking_count DESC
        LIMIT 5
    ");
    $servicesStmt->execute([$filterMonthNum, $filterYear]);
    $analytics['top_services'] = $servicesStmt->fetchAll(PDO::FETCH_ASSOC);

    // ORDER STATUS DISTRIBUTION - FILTERED BY SELECTED MONTH
    $statusStmt = $conn->prepare("
        SELECT status, COUNT(*) as count
        FROM reservations 
        WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?
        GROUP BY status
    ");
    $statusStmt->execute([$filterMonthNum, $filterYear]);
    $analytics['order_status'] = $statusStmt->fetchAll(PDO::FETCH_ASSOC);

    // Total reservations for the month
    $ordersStmt = $conn->prepare("
        SELECT COUNT(*) as total_orders 
        FROM reservations
        WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?
    ");
    $ordersStmt->execute([$filterMonthNum, $filterYear]);
    $analytics['total_reservations'] = intval($ordersStmt->fetch(PDO::FETCH_ASSOC)['total_orders'] ?? 0);

    echo json_encode(['success' => true, 'data' => $analytics]);
} catch (Exception $e) {
    error_log("Analytics API Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error fetching analytics data']);
}
?>
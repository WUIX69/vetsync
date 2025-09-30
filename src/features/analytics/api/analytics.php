<?php
include '../../../core/app.php';

header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

try {
    global $conn;

    $analytics = [];

    // ✅ 1. TOTAL REVENUE (from reservations)
    $revenueStmt = $conn->prepare("SELECT SUM(total_amount) as total_revenue FROM reservations WHERE status = 'picked_up'");
    $revenueStmt->execute();
    $revenueResult = $revenueStmt->fetch(PDO::FETCH_ASSOC);
    $analytics['total_revenue'] = floatval($revenueResult['total_revenue'] ?? 0);

    // ✅ 2. TOTAL USERS
    $usersStmt = $conn->prepare("SELECT COUNT(*) as total_users FROM users");
    $usersStmt->execute();
    $usersResult = $usersStmt->fetch(PDO::FETCH_ASSOC);
    $analytics['total_users'] = intval($usersResult['total_users'] ?? 0);

    // ✅ 3. TOTAL APPOINTMENTS
    $appointmentsStmt = $conn->prepare("SELECT COUNT(*) as total_appointments FROM appointments");
    $appointmentsStmt->execute();
    $appointmentsResult = $appointmentsStmt->fetch(PDO::FETCH_ASSOC);
    $analytics['total_appointments'] = intval($appointmentsResult['total_appointments'] ?? 0);

    // ✅ 4. TOTAL RESERVATIONS
    $reservationsStmt = $conn->prepare("SELECT COUNT(*) as total_reservations FROM reservations");
    $reservationsStmt->execute();
    $reservationsResult = $reservationsStmt->fetch(PDO::FETCH_ASSOC);
    $analytics['total_reservations'] = intval($reservationsResult['total_reservations'] ?? 0);

    // ✅ 5. NEW USERS THIS MONTH
    $thisMonthStmt = $conn->prepare("
        SELECT COUNT(*) as new_users 
        FROM users 
        WHERE MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())
    ");
    $thisMonthStmt->execute();
    $thisMonthResult = $thisMonthStmt->fetch(PDO::FETCH_ASSOC);
    $analytics['new_users_this_month'] = intval($thisMonthResult['new_users'] ?? 0);

    // ✅ 6. ORDERS TODAY (reservations)
    $todayStmt = $conn->prepare("
        SELECT COUNT(*) as orders_today 
        FROM reservations 
        WHERE DATE(created_at) = CURDATE()
    ");
    $todayStmt->execute();
    $todayResult = $todayStmt->fetch(PDO::FETCH_ASSOC);
    $analytics['orders_today'] = intval($todayResult['orders_today'] ?? 0);

    // ✅ 7. REVENUE TODAY
    $revenueTodayStmt = $conn->prepare("
        SELECT SUM(total_amount) as revenue_today 
        FROM reservations 
        WHERE DATE(created_at) = CURDATE() AND status = 'picked_up'
    ");
    $revenueTodayStmt->execute();
    $revenueTodayResult = $revenueTodayStmt->fetch(PDO::FETCH_ASSOC);
    $analytics['revenue_today'] = floatval($revenueTodayResult['revenue_today'] ?? 0);

    // ✅ 8. TOP SALES - Most Sold Products by Total Quantity
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
                $price = floatval($product['price'] ?? 0);

                if (!isset($productSales[$productName])) {
                    $productSales[$productName] = [
                        'product_name' => $productName,
                        'total_quantity' => 0,
                        'total_sales' => 0,
                        'reservations_count' => 0
                    ];
                }

                $productSales[$productName]['total_quantity'] += $quantity;
                $productSales[$productName]['total_sales'] += ($price * $quantity);
                $productSales[$productName]['reservations_count'] += 1;
            }
        }
    }

    // Sort by total quantity sold (descending)
    usort($productSales, function ($a, $b) {
        return $b['total_quantity'] - $a['total_quantity'];
    });

    // Take top 5 and format for output
    $analytics['top_sales'] = array_slice($productSales, 0, 5);

    // ✅ 9. WEEKLY ACTIVITY (last 7 days)
    $weeklyStmt = $conn->prepare("
        SELECT 
            DATE(created_at) as date,
            COUNT(*) as count
        FROM reservations 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        GROUP BY DATE(created_at)
        ORDER BY date ASC
    ");
    $weeklyStmt->execute();
    $weeklyResults = $weeklyStmt->fetchAll(PDO::FETCH_ASSOC);

    $analytics['weekly_activity'] = [];
    foreach ($weeklyResults as $day) {
        $analytics['weekly_activity'][] = [
            'date' => $day['date'],
            'count' => intval($day['count'])
        ];
    }

    echo json_encode([
        'success' => true,
        'data' => $analytics
    ]);

} catch (Exception $e) {
    error_log("Analytics Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch analytics: ' . $e->getMessage()
    ]);
}
?>
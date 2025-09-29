<?php
include 'src/core/app.php';

try {
    global $conn;
    echo "<h2>🧪 Analytics API Test</h2>";

    // Test each query individually
    echo "<h3>1. Total Revenue:</h3>";
    $revenueStmt = $conn->prepare("SELECT SUM(total_amount) as total_revenue FROM reservations WHERE status = 'picked_up'");
    $revenueStmt->execute();
    $revenueResult = $revenueStmt->fetch(PDO::FETCH_ASSOC);
    echo "Revenue: ₱" . ($revenueResult['total_revenue'] ?? 0) . "<br>";

    echo "<h3>2. Total Users:</h3>";
    $usersStmt = $conn->prepare("SELECT COUNT(*) as total_users FROM users");
    $usersStmt->execute();
    $usersResult = $usersStmt->fetch(PDO::FETCH_ASSOC);
    echo "Users: " . ($usersResult['total_users'] ?? 0) . "<br>";

    echo "<h3>3. Total Appointments:</h3>";
    $appointmentsStmt = $conn->prepare("SELECT COUNT(*) as total_appointments FROM appointments");
    $appointmentsStmt->execute();
    $appointmentsResult = $appointmentsStmt->fetch(PDO::FETCH_ASSOC);
    echo "Appointments: " . ($appointmentsResult['total_appointments'] ?? 0) . "<br>";

    echo "<h3>4. Total Reservations:</h3>";
    $reservationsStmt = $conn->prepare("SELECT COUNT(*) as total_reservations FROM reservations");
    $reservationsStmt->execute();
    $reservationsResult = $reservationsStmt->fetch(PDO::FETCH_ASSOC);
    echo "Reservations: " . ($reservationsResult['total_reservations'] ?? 0) . "<br>";

    echo "<h3>✅ Analytics API Test Complete!</h3>";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
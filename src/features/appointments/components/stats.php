<?php
// Simple appointment stats - no complex code
$stats = [
    'total' => 0,
    'today' => 0,
    'this_month' => 0
];

try {
    // Get database connection
    global $conn;
    include_once dirname(__FILE__) . '/../../../core/conn.php';

    if ($conn) {
        // Get total appointments (excluding cancelled)
        $total_stmt = $conn->prepare("SELECT COUNT(*) as count FROM appointments WHERE status != 'cancelled'");
        $total_stmt->execute();
        $stats['total'] = $total_stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Get today's appointments (excluding cancelled)
        $today_stmt = $conn->prepare("SELECT COUNT(*) as count FROM appointments WHERE date = CURDATE() AND status != 'cancelled'");
        $today_stmt->execute();
        $stats['today'] = $today_stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Get appointments this month (excluding cancelled)
        $firstDayOfMonth = date('Y-m-01');
        $lastDayOfMonth = date('Y-m-t');
        $month_stmt = $conn->prepare("SELECT COUNT(*) as count FROM appointments WHERE date >= ? AND date <= ? AND status != 'cancelled'");
        $month_stmt->execute([$firstDayOfMonth, $lastDayOfMonth]);
        $stats['this_month'] = $month_stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }
} catch (Exception $e) {
    // Keep default values if database fails
}
?>

<section class="stats">
    <div class="container">
        <div class="row g-4">
            <!-- Total Appointments -->
            <div class="col-md-4 col-sm-4">
                <div class="box stat-card">
                    <div class="info">
                        <h6 class="text-muted mb-2">
                            Total Appointments
                        </h6>
                        <h2><?= $stats['total'] ?></h2>
                    </div>
                </div>
            </div>

            <!-- Today's Appointments -->
            <div class="col-md-4 col-sm-4">
                <div class="box stat-card">
                    <div class="info">
                        <h6 class="text-muted mb-2">
                            Today's Schedule
                        </h6>
                        <h2><?= $stats['today'] ?></h2>
                    </div>
                </div>
            </div>

            <!-- Appointments This Month -->
            <div class="col-md-4 col-sm-4">
                <div class="box stat-card">
                    <div class="info">
                        <h6 class="text-muted mb-2">
                            Appointments This Month
                        </h6>
                        <h2><?= $stats['this_month'] ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
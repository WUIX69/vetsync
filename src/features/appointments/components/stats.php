<?php
// Simple appointment stats - no complex code
$stats = [
    'total' => 0,
    'today' => 0,
    'completed' => 0,
    'pending' => 0
];

try {
    // Get database connection
    global $conn;
    include_once dirname(__FILE__) . '/../../../core/conn.php';

    if ($conn) {
        // Get total appointments
        $total_stmt = $conn->prepare("SELECT COUNT(*) as count FROM appointments");
        $total_stmt->execute();
        $stats['total'] = $total_stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Get today's appointments
        $today_stmt = $conn->prepare("SELECT COUNT(*) as count FROM appointments WHERE date = CURDATE()");
        $today_stmt->execute();
        $stats['today'] = $today_stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Get completed appointments
        $completed_stmt = $conn->prepare("SELECT COUNT(*) as count FROM appointments WHERE status = 'completed'");
        $completed_stmt->execute();
        $stats['completed'] = $completed_stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Get pending appointments
        $pending_stmt = $conn->prepare("SELECT COUNT(*) as count FROM appointments WHERE status = 'pending'");
        $pending_stmt->execute();
        $stats['pending'] = $pending_stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }
} catch (Exception $e) {
    // Keep default values if database fails
}

// Calculate simple percentages for progress circles
$total_percent = min($stats['total'] * 2, 100); // Scale for visualization
$today_percent = min($stats['today'] * 25, 100); // Scale for visualization  
$completed_percent = $stats['total'] > 0 ? round(($stats['completed'] / $stats['total']) * 100) : 0;
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
                        <small class="text-primary">All time</small>
                    </div>
                    <div class="progress-circle">
                        <svg viewBox="0 0 36 36" class="circular-chart">
                            <path d="M18 2.0845
                                                    a 15.9155 15.9155 0 0 1 0 31.831
                                                    a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#eee"
                                stroke-width="3" />
                            <path d="M18 2.0845
                                                    a 15.9155 15.9155 0 0 1 0 31.831
                                                    a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#20c997"
                                stroke-width="4.3" stroke-dasharray="<?= $total_percent ?>, 100" />
                            <text x="18" y="20.35" class="percentage">
                                <?= $stats['total'] ?>
                            </text>
                        </svg>
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
                        <small class="text-danger">Scheduled today</small>
                    </div>
                    <div class="progress-circle">
                        <svg viewBox="0 0 36 36" class="circular-chart">
                            <path d="M18 2.0845
                                                    a 15.9155 15.9155 0 0 1 0 31.831
                                                    a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#eee"
                                stroke-width="3" />
                            <path d="M18 2.0845
                                                    a 15.9155 15.9155 0 0 1 0 31.831
                                                    a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#ff0060"
                                stroke-width="4.3" stroke-dasharray="<?= $today_percent ?>, 100" />
                            <text x="18" y="20.35" class="percentage">
                                <?= $stats['today'] ?>
                            </text>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Completed vs Pending -->
            <div class="col-md-4 col-sm-4">
                <div class="box stat-card">
                    <div class="info">
                        <h6 class="text-muted mb-2">
                            Completion Rate
                        </h6>
                        <h2><?= $stats['completed'] ?></h2>
                        <small class="text-success"><?= $stats['pending'] ?> pending</small>
                    </div>
                    <div class="progress-circle">
                        <svg viewBox="0 0 36 36" class="circular-chart">
                            <path d="M18 2.0845
                                                    a 15.9155 15.9155 0 0 1 0 31.831
                                                    a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#eee"
                                stroke-width="3" />
                            <path d="M18 2.0845
                                                    a 15.9155 15.9155 0 0 1 0 31.831
                                                    a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#6c9bcf"
                                stroke-width="4.3" stroke-dasharray="<?= $completed_percent ?>, 100" />
                            <text x="18" y="20.35" class="percentage">
                                <?= $completed_percent ?>%
                            </text>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
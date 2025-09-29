<?php
// Simple services stats - no complex code
$stats = [
    'total_services' => 0,
    'available_services' => 0,
    'unavailable_services' => 0,
    'total_bookings' => 0
];

try {
    // Get database connection
    global $conn;
    include_once dirname(__FILE__) . '/../../../core/conn.php';

    if ($conn) {
        // Get total services
        $total_stmt = $conn->prepare("SELECT COUNT(*) as count FROM services");
        $total_stmt->execute();
        $stats['total_services'] = $total_stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Get available services
        $available_stmt = $conn->prepare("SELECT COUNT(*) as count FROM services WHERE status = 'available'");
        $available_stmt->execute();
        $stats['available_services'] = $available_stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Get unavailable services
        $unavailable_stmt = $conn->prepare("SELECT COUNT(*) as count FROM services WHERE status = 'unavailable'");
        $unavailable_stmt->execute();
        $stats['unavailable_services'] = $unavailable_stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Get total bookings for all services
        $bookings_stmt = $conn->prepare("SELECT COUNT(*) as count FROM appointments WHERE service_uuid IS NOT NULL");
        $bookings_stmt->execute();
        $stats['total_bookings'] = $bookings_stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }
} catch (Exception $e) {
    // Keep default values if database fails
}

// Calculate simple percentages for progress circles
$total_percent = min($stats['total_services'] * 8, 100); // Scale for visualization
$available_percent = $stats['total_services'] > 0 ? round(($stats['available_services'] / $stats['total_services']) * 100) : 0;
$unavailable_percent = $stats['total_services'] > 0 ? round(($stats['unavailable_services'] / $stats['total_services']) * 100) : 0;
$bookings_percent = min($stats['total_bookings'] * 2, 100); // Scale for visualization
?>

<!-- Service Stats -->
<section class="stats">
    <div class="container">
        <div class="row g-4">
            <!-- Total Services -->
            <div class="col-md-3 col-sm-3">
                <div class="box stat-card">
                    <div class="info">
                        <h6 class="text-muted mb-2">
                            Total Services
                        </h6>
                        <h2><?= $stats['total_services'] ?></h2>
                        <small class="text-primary">All services</small>
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
                                <?= $stats['total_services'] ?>
                            </text>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Available Services -->
            <div class="col-md-3 col-sm-3">
                <div class="box stat-card">
                    <div class="info">
                        <h6 class="text-muted mb-2">
                            Available Services
                        </h6>
                        <h2><?= $stats['available_services'] ?></h2>
                        <small class="text-success"><?= $available_percent ?>% available</small>
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
                                stroke-width="4.3" stroke-dasharray="<?= $available_percent ?>, 100" />
                            <text x="18" y="20.35" class="percentage">
                                <?= $available_percent ?>%
                            </text>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Unavailable Services -->
            <div class="col-md-3 col-sm-3">
                <div class="box stat-card">
                    <div class="info">
                        <h6 class="text-muted mb-2">
                            Unavailable Services
                        </h6>
                        <h2><?= $stats['unavailable_services'] ?></h2>
                        <small class="text-warning"><?= $unavailable_percent ?>% unavailable</small>
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
                                stroke-width="4.3" stroke-dasharray="<?= $unavailable_percent ?>, 100" />
                            <text x="18" y="20.35" class="percentage">
                                <?= $unavailable_percent ?>%
                            </text>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Bookings -->
            <div class="col-md-3 col-sm-3">
                <div class="box stat-card">
                    <div class="info">
                        <h6 class="text-muted mb-2">
                            Total Bookings
                        </h6>
                        <h2><?= $stats['total_bookings'] ?></h2>
                        <small class="text-info">All time bookings</small>
                    </div>
                    <div class="progress-circle">
                        <svg viewBox="0 0 36 36" class="circular-chart">
                            <path d="M18 2.0845
                                                    a 15.9155 15.9155 0 0 1 0 31.831
                                                    a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#eee"
                                stroke-width="3" />
                            <path d="M18 2.0845
                                                    a 15.9155 15.9155 0 0 1 0 31.831
                                                    a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#17a2b8"
                                stroke-width="4.3" stroke-dasharray="<?= $bookings_percent ?>, 100" />
                            <text x="18" y="20.35" class="percentage">
                                <?= $stats['total_bookings'] ?>
                            </text>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
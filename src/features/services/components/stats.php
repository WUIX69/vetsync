<?php
// Simple services stats - no complex code
$stats = [
    'total_services' => 0,
    'available_services' => 0,
    'unavailable_services' => 0
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
    }
} catch (Exception $e) {
    // Keep default values if database fails
}
?>

<!-- Service Stats -->
<section class="stats">
    <div class="container">
        <div class="row g-4">
            <!-- Total Services -->
            <div class="col-md-4 col-sm-4">
                <div class="box stat-card">
                    <div class="info">
                        <h6 class="text-muted mb-2">
                            Total Services
                        </h6>
                        <h2><?= $stats['total_services'] ?></h2>
                    </div>
                </div>
            </div>

            <!-- Available Services -->
            <div class="col-md-4 col-sm-4">
                <div class="box stat-card">
                    <div class="info">
                        <h6 class="text-muted mb-2">
                            Available Services
                        </h6>
                        <h2><?= $stats['available_services'] ?></h2>
                    </div>
                </div>
            </div>

            <!-- Unavailable Services -->
            <div class="col-md-4 col-sm-4">
                <div class="box stat-card">
                    <div class="info">
                        <h6 class="text-muted mb-2">
                            Unavailable Services
                        </h6>
                        <h2><?= $stats['unavailable_services'] ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
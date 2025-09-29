<?php
// Simple products stats - no complex code
$stats = [
    'total_products' => 0,
    'available_products' => 0,
    'unavailable_products' => 0,
    'total_stock' => 0
];

try {
    // Get database connection
    global $conn;
    include_once dirname(__FILE__) . '/../../../core/conn.php';

    if ($conn) {
        // Get total products
        $total_stmt = $conn->prepare("SELECT COUNT(*) as count FROM products");
        $total_stmt->execute();
        $stats['total_products'] = $total_stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Get available products
        $available_stmt = $conn->prepare("SELECT COUNT(*) as count FROM products WHERE status = 'available'");
        $available_stmt->execute();
        $stats['available_products'] = $available_stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Get unavailable products
        $unavailable_stmt = $conn->prepare("SELECT COUNT(*) as count FROM products WHERE status = 'unavailable'");
        $unavailable_stmt->execute();
        $stats['unavailable_products'] = $unavailable_stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Get total stock
        $stock_stmt = $conn->prepare("SELECT SUM(stock) as total FROM products WHERE stock IS NOT NULL");
        $stock_stmt->execute();
        $result = $stock_stmt->fetch(PDO::FETCH_ASSOC);
        $stats['total_stock'] = $result['total'] ?: 0;
    }
} catch (Exception $e) {
    // Keep default values if database fails
}

// Calculate simple percentages for progress circles
$total_percent = min($stats['total_products'] * 8, 100); // Scale for visualization
$available_percent = $stats['total_products'] > 0 ? round(($stats['available_products'] / $stats['total_products']) * 100) : 0;
$unavailable_percent = $stats['total_products'] > 0 ? round(($stats['unavailable_products'] / $stats['total_products']) * 100) : 0;
$stock_percent = min($stats['total_stock'] / 10, 100); // Scale for visualization (assuming 1000 max stock)
?>

<!-- Product Stats -->
<section class="stats">
    <div class="container">
        <div class="row g-4">
            <!-- Total Products -->
            <div class="col-md-3 col-sm-3">
                <div class="box stat-card">
                    <div class="info">
                        <h6 class="text-muted mb-2">
                            Total Products
                        </h6>
                        <h2><?= $stats['total_products'] ?></h2>
                        <small class="text-primary">All products</small>
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
                                <?= $stats['total_products'] ?>
                            </text>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Available Products -->
            <div class="col-md-3 col-sm-3">
                <div class="box stat-card">
                    <div class="info">
                        <h6 class="text-muted mb-2">
                            Available Products
                        </h6>
                        <h2><?= $stats['available_products'] ?></h2>
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

            <!-- Unavailable Products -->
            <div class="col-md-3 col-sm-3">
                <div class="box stat-card">
                    <div class="info">
                        <h6 class="text-muted mb-2">
                            Unavailable Products
                        </h6>
                        <h2><?= $stats['unavailable_products'] ?></h2>
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

            <!-- Total Stock -->
            <div class="col-md-3 col-sm-3">
                <div class="box stat-card">
                    <div class="info">
                        <h6 class="text-muted mb-2">
                            Total Stock
                        </h6>
                        <h2><?= $stats['total_stock'] ?></h2>
                        <small class="text-info">Items in stock</small>
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
                                stroke-width="4.3" stroke-dasharray="<?= $stock_percent ?>, 100" />
                            <text x="18" y="20.35" class="percentage">
                                <?= $stats['total_stock'] ?>
                            </text>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
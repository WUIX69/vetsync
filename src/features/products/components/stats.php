<?php
// Simple products stats - no complex code
$stats = [
    'total_products' => 0,
    'available_products' => 0,
    'unavailable_products' => 0
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
    }
} catch (Exception $e) {
    // Keep default values if database fails
}
?>

<!-- Product Stats -->
<section class="stats">
    <div class="container">
        <div class="row g-4">
            <!-- Total Products -->
            <div class="col-md-4 col-sm-4">
                <div class="box stat-card">
                    <div class="info">
                        <h6 class="text-muted mb-2">
                            Total Products
                        </h6>
                        <h2><?= $stats['total_products'] ?></h2>
                    </div>
                </div>
            </div>

            <!-- Available Products -->
            <div class="col-md-4 col-sm-4">
                <div class="box stat-card">
                    <div class="info">
                        <h6 class="text-muted mb-2">
                            Available Products
                        </h6>
                        <h2><?= $stats['available_products'] ?></h2>
                    </div>
                </div>
            </div>

            <!-- Unavailable Products -->
            <div class="col-md-4 col-sm-4">
                <div class="box stat-card">
                    <div class="info">
                        <h6 class="text-muted mb-2">
                            Unavailable Products
                        </h6>
                        <h2><?= $stats['unavailable_products'] ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
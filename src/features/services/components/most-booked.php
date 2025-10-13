<?php
// Simple fetch of most booked services - no complex code
$most_booked_services = [];

try {
    // Get database connection
    global $conn;
    include_once dirname(__FILE__) . '/../../../core/conn.php';

    if ($conn) {
        // Simple query to get most booked services
        $stmt = $conn->prepare("
            SELECT 
                s.name AS service_name,
                COUNT(a.uuid) AS booking_count,
                c.name AS category_name
            FROM appointments a
            LEFT JOIN services s ON a.service_uuid = s.uuid
            LEFT JOIN categories c ON s.category_id = c.id
            WHERE s.name IS NOT NULL
            GROUP BY s.uuid, s.name, c.name
            ORDER BY booking_count DESC
            LIMIT 3
        ");
        $stmt->execute();
        $most_booked_services = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
    // If database fails, use empty array
    $most_booked_services = [];
}

// Simple function to get service icon based on category or name
function getServiceIcon($service_name, $category_name)
{
    $service_lower = strtolower($service_name);
    $category_lower = strtolower($category_name);

    // Simple icon mapping
    if (strpos($service_lower, 'vaccination') !== false || strpos($service_lower, 'vaccine') !== false) {
        return 'syringe';
    } elseif (strpos($service_lower, 'groom') !== false || strpos($category_lower, 'groom') !== false) {
        return 'paw';
    } elseif (strpos($service_lower, 'dental') !== false || strpos($service_lower, 'teeth') !== false) {
        return 'tooth';
    } elseif (strpos($service_lower, 'surgery') !== false || strpos($category_lower, 'surgery') !== false) {
        return 'cut';
    } elseif (strpos($service_lower, 'checkup') !== false || strpos($service_lower, 'check') !== false) {
        return 'heart';
    } else {
        return 'heartbeat'; // Default icon
    }
}
?>

<style>
    /* Most Booked Services Section - Card Style */
    main section.most-booked-services {
        margin: 2rem 0;
    }

    main section.most-booked-services .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--color-dark);
    }

    main section.most-booked-services .most-booked-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem;
    }

    main section.most-booked-services .most-booked-card {
        background-color: var(--color-white);
        border-radius: 0.6rem;
        padding: 1rem 1.25rem;
        box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.03);
        border-left: 4px solid var(--color-primary);
        display: flex;
        align-items: center;
        transition: transform 0.2s, box-shadow 0.2s;
        min-width: 0;
    }

    main section.most-booked-services .most-booked-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.3rem 0.8rem rgba(0, 0, 0, 0.08);
    }

    main section.most-booked-services .most-booked-card.top-service {
        border-left: 4px solid #1976d2;
        background: linear-gradient(90deg, #e3f2fd 0%, #fff 100%);
        box-shadow: 0 4px 24px rgba(25, 118, 210, 0.10);
        transform: scale(1.03);
        z-index: 1;
    }

    main section.most-booked-services .most-booked-icon {
        font-size: 2rem;
        margin-right: 1rem;
        color: #1976d2;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
    }

    main section.most-booked-services .most-booked-info {
        flex: 1;
        min-width: 0;
    }

    main section.most-booked-services .most-booked-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.2rem;
        color: var(--color-dark);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    main section.most-booked-services .most-booked-desc {
        font-size: 0.9rem;
        color: var(--color-dark-variant);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .no-services {
        text-align: center;
        padding: 2rem;
        color: var(--color-dark-variant);
        grid-column: 1 / -1;
    }
</style>

<section class="most-booked-services">
    <h2 class="title">Most Booked Services</h2>
    <div class="container most-booked-grid">

        <?php if (empty($most_booked_services)): ?>
            <div class="no-services">
                <i class="icon calendar times outline" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                <p>No booking data available</p>
            </div>
        <?php else: ?>
            <?php foreach ($most_booked_services as $index => $service): ?>
                <div class="most-booked-card <?= $index === 0 ? 'top-service' : '' ?>">
                    <div class="most-booked-icon">
                        <i class="<?= getServiceIcon($service['service_name'], $service['category_name'] ?: '') ?> icon"></i>
                    </div>
                    <div class="most-booked-info">
                        <div class="most-booked-title"><?= htmlspecialchars($service['service_name']) ?></div>
                        <div class="most-booked-desc"><?= $service['booking_count'] ?> bookings total</div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</section>
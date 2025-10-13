<?php
use VetSync\Models\Services;

// Get random services for related section (excluding current service)
$current_service = $GLOBALS['service'] ?? null;
$current_uuid = $current_service['uuid'] ?? '';

$services_result = Services::all();
$all_services = $services_result['data'] ?? [];

// Filter out current service and get 3 random ones
$related_services = array_filter($all_services, function ($service) use ($current_uuid) {
    return $service['uuid'] !== $current_uuid;
});

// Shuffle and take first 3
shuffle($related_services);
$related_services = array_slice($related_services, 0, 3);

// Service icons mapping
$service_icons = [
    'vaccination' => 'bx-injection',
    'surgery' => 'bx-plus-medical',
    'grooming' => 'bx-cut',
    'checkup' => 'bx-health',
    'dental' => 'bx-smile',
    'emergency' => 'bx-first-aid',
    'boarding' => 'bx-home',
    'training' => 'bx-brain',
    'default' => 'bx-heart'
];

function getServiceIcon($serviceName, $iconMap)
{
    $name = strtolower($serviceName);
    foreach ($iconMap as $key => $icon) {
        if (strpos($name, $key) !== false) {
            return $icon;
        }
    }
    return $iconMap['default'];
}

function getServiceStatus($status)
{
    $statuses = [
        'available' => ['class' => 'green', 'icon' => 'bx-check-circle', 'text' => 'Available'],
        'unavailable' => ['class' => 'red', 'icon' => 'bx-x-circle', 'text' => 'Unavailable'],
        'busy' => ['class' => 'yellow', 'icon' => 'bx-time', 'text' => 'Busy'],
        'soon' => ['class' => 'blue', 'icon' => 'bx-calendar-plus', 'text' => 'Coming Soon']
    ];

    return $statuses[$status] ?? $statuses['available'];
}
?>
<style>
    main section.related .service-card {
        background: var(--color-white);
        padding: 0 !important;
        border-radius: 0.6rem;
        border: 1px solid var(--bs-card-border-color) !important;
        transition: all 0.3s ease;
        height: 100%;
    }

    main section.related .header .related-tag {
        display: inline-block;
        background: var(--color-dark-variant);
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 5px;
        text-transform: uppercase;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    main section.related .header .related-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 2rem;
        line-height: 1.3;
    }

    main section.related .service-card .card-img {
        position: relative;
        overflow: hidden;
        height: 260px;
        border-radius: 0;
    }

    main section.related .service-card .card-img .service-status {
        position: absolute;
        top: 1rem;
        right: 1rem;
    }

    main section.related .service-card .card-img .service-status span {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
        border-radius: 60px;
        background: var(--bs-primary);
        color: var(--bs-white);

        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.3rem;
    }

    main section.related .service-card .card-img .service-status span i {
        font-size: 1rem;
    }

    main section.related .service-card .card-img .service-tag {
        position: absolute;
        bottom: 1rem;
        left: 1rem;
    }

    main section.related .service-card .card-img .service-tag span {
        font-size: 0.8rem;
    }

    main section.related .service-card .card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }

    main section.related .service-card .card-body {
        padding: 1.6rem;
        font-size: 0.95rem;

        display: flex;
        flex-direction: column;
    }

    main section.related .service-card .card-body .service-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    main section.related .service-card .card-body .service-header h4 {
        font-weight: bold;
        margin: 0;
        font-size: 1.4rem;
    }

    main section.related .service-card .card-body .service-header i {
        font-size: 24px;
        background: #eff6ff;
        padding: 10px;
        border-radius: 50%;
        color: #031224;
    }

    main section.related .service-card .card-body .service-details {
        margin-bottom: 20px;
    }

    main section.related .service-card .card-body .service-details p {
        color: #667;
        line-height: 1.5;
        margin-bottom: 15px;
    }

    main section.related .service-card .card-body .service-meta {
        flex: 1;

        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 1px solid #f3f3f3;
    }

    main section.related .service-card .card-body .service-meta .price {
        font-weight: 600;
        font-size: 18px;
        color: #031224;
    }

    main section.related .service-card .card-body .service-meta .service-btn {
        border: none !important;
        color: #fff !important;
        background: #031224 !important;
        padding: 10px 20px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        font-weight: 500;
    }

    main section.related .service-card .card-body .service-meta .service-btn:hover {
        background: #062451 !important;
        color: #fff !important;
    }

    main section.related .service-card .card-body .service-meta .service-btn:focus {
        background: #062451 !important;
        color: #fff !important;
    }
</style>
<section class="related py-5">
    <div class="container-xl">
        <div class="header">
            <div class="related-tag">RELATED SERVICES</div>
            <h2 class="related-title">You May Also Be Interested In</h2>
        </div>
        <!-- Services -->
        <div class="row g-4">
            <?php if (!empty($related_services)): ?>
                <?php foreach ($related_services as $service): ?>
                    <?php
                    $status_info = getServiceStatus($service['status'] ?? 'available');
                    $icon = getServiceIcon($service['name'], $service_icons);
                    ?>
                    <div class="col-lg-4">
                        <div class="service-card card">
                            <div class="card-img">
                                <div class="service-status">
                                    <span class="ui <?= $status_info['class'] ?> label status-avail">
                                        <i class='bx <?= $status_info['icon'] ?>'></i> <?= $status_info['text'] ?>
                                    </span>
                                </div>
                                <img src="<?= media($service['uuid']) ?>"
                                    alt="<?= htmlspecialchars($service['name'], ENT_QUOTES, 'UTF-8') ?>">
                                <div class="service-tag">
                                    <span class="ui primary tag label">Featured</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="service-header">
                                    <h4><?= htmlspecialchars($service['name'], ENT_QUOTES, 'UTF-8') ?></h4>
                                    <i class='bx <?= $icon ?>'></i>
                                </div>
                                <div class="service-details">
                                    <p><?= htmlspecialchars(substr($service['description'], 0, 120), ENT_QUOTES, 'UTF-8') ?>...
                                    </p>
                                    <p>Duration: <?= htmlspecialchars($service['duration'], ENT_QUOTES, 'UTF-8') ?></p>
                                </div>
                                <div class="service-meta">
                                    <span class="price">₱<?= number_format(floatval($service['price']), 2) ?></span>
                                    <a href="/src/app/user/service-single-view.php?uuid=<?= $service['uuid'] ?>"
                                        class="service-btn">
                                        <?= $status_info['text'] === 'Available' ? 'Book Now' : 'View Details' ?> <i
                                            class='bx bx-right-arrow-alt'></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback if no services available -->
                <div class="col-12">
                    <p class="text-center">No related services available at the moment.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>
<?php
global $session;

if (!$session->has()) {
    return;
}

$userData = $session->get();

use VetSync\Models\Appointments;
use VetSync\Models\Reservations;

// Get completed appointments and picked up reservations
$completedAppointments = Appointments::getCompletedByUser($userData['uuid']);
$pickedUpReservations = Reservations::getPickedUpByUser($userData['uuid']);
?>

<style>
    .completed-items {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .completed-items h3 {
        margin-bottom: 1.5rem;
        color: var(--color-dark);
        font-weight: 600;
    }

    .items-tabs {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid #f8f9fa;
    }

    .tab-btn {
        padding: 0.75rem 1rem;
        background: none;
        border: none;
        color: #6c757d;
        font-weight: 500;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        transition: all 0.3s;
    }

    .tab-btn.active {
        color: var(--color-primary);
        border-bottom-color: var(--color-primary);
    }

    .completed-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        margin-bottom: 1rem;
        transition: box-shadow 0.3s;
    }

    .completed-item:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .item-info {
        flex: 1;
    }

    .item-name {
        font-weight: 600;
        color: var(--color-dark);
        margin-bottom: 0.25rem;
    }

    .item-date {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .item-actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .review-btn {
        background: var(--color-primary);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: background 0.3s;
    }

    .review-btn:hover {
        background: var(--color-primary-dark);
    }

    .reviewed-badge {
        background: #28a745;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .no-items {
        text-align: center;
        color: #6c757d;
        padding: 2rem;
    }
</style>

<div class="completed-items">
    <h3>Completed Items - Leave a Review</h3>

    <div class="items-tabs">
        <button class="tab-btn active" data-tab="services">Completed Services</button>
        <button class="tab-btn" data-tab="products">Picked Up Products</button>
    </div>

    <!-- Completed Services Tab -->
    <div class="tab-content" id="services-tab">
        <?php if ($completedAppointments['success'] && !empty($completedAppointments['data'])): ?>
            <?php foreach ($completedAppointments['data'] as $appointment): ?>
                <div class="completed-item">
                    <div class="item-info">
                        <div class="item-name">
                            <?= htmlspecialchars($appointment['service_name'] ?: 'Custom Service') ?>
                        </div>
                        <div class="item-date">
                            Completed on: <?= date('F j, Y', strtotime($appointment['date'])) ?>
                        </div>
                    </div>
                    <div class="item-actions">
                        <?php if ($appointment['review_id']): ?>
                            <span class="reviewed-badge">Reviewed</span>
                        <?php else: ?>
                            <button class="review-btn" onclick="goToReview('<?= $appointment['service_uuid'] ?>', 'services')">
                                Leave Review
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-items">
                <p>No completed services to review.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Picked Up Products Tab -->
    <div class="tab-content" id="products-tab" style="display: none;">
        <?php if ($pickedUpReservations['success'] && !empty($pickedUpReservations['data'])): ?>
            <?php foreach ($pickedUpReservations['data'] as $reservation): ?>
                <div class="completed-item">
                    <div class="item-info">
                        <div class="item-name">
                            <?= $reservation['products_count'] ?> Product<?= $reservation['products_count'] > 1 ? 's' : '' ?>
                            Reservation
                        </div>
                        <div class="item-date">
                            Picked up on: <?= $reservation['formatted_pickup_date'] ?>
                        </div>
                    </div>
                    <div class="item-actions">
                        <?php if ($reservation['review_id']): ?>
                            <span class="reviewed-badge">Reviewed</span>
                        <?php else: ?>
                            <button class="review-btn"
                                onclick="goToReview('<?= $reservation['reservation_uuid'] ?>', 'reservations')">
                                Leave Review
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-items">
                <p>No picked up products to review.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        setupItemsTabs();
    });

    function setupItemsTabs() {
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const targetTab = this.dataset.tab;

                // Update active tab button
                tabBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                // Show target tab content
                tabContents.forEach(content => {
                    if (content.id === targetTab + '-tab') {
                        content.style.display = 'block';
                    } else {
                        content.style.display = 'none';
                    }
                });
            });
        });
    }

    function goToReview(uuid, type) {
        if (type === 'services') {
            window.location.href = `/src/app/user/service-single-view.php?uuid=${uuid}#reviews-section`;
        } else if (type === 'reservations') {
            // For reservations, we need to find a product from that reservation
            // For now, redirect to products page - you might want to create a specific review page
            alert('Please visit the individual product pages to leave reviews for your purchased items.');
        }
    }
</script>
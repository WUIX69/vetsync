<style>
    .recent-reservations {
        background: var(--color-white);
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: var(--box-shadow);
        margin-top: 1rem;
    }

    .reservation-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f1f1;
    }

    .reservation-item:last-child {
        border-bottom: none;
    }

    .reservation-info h6 {
        margin: 0;
        color: var(--color-dark);
        font-weight: 600;
    }

    .reservation-info small {
        color: var(--color-info-dark);
    }

    .reservation-amount {
        font-weight: 600;
        color: var(--color-success);
    }
</style>

<section class="recent-reservations">
    <h3>Recent Product Orders</h3>
    <div id="recent-reservations-list">
        <div class="text-center p-3">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    <div class="text-center mt-3">
        <a href="reservations.php" class="btn btn-outline-primary btn-sm">View All Orders</a>
    </div>
</section>
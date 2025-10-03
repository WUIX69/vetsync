<style>
    .recent-appointments {
        background: var(--color-white);
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: var(--box-shadow);
        margin-top: 1rem;
    }

    .appointment-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f1f1;
    }

    .appointment-item:last-child {
        border-bottom: none;
    }

    .appointment-info h6 {
        margin: 0;
        color: var(--color-dark);
        font-weight: 600;
    }

    .appointment-info small {
        color: var(--color-info-dark);
    }

    .appointment-status {
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-accepted {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-completed {
        background: #d4edda;
        color: #155724;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }
</style>

<section class="recent-appointments">
    <h3>Pending Appointments</h3>
    <div id="recent-appointments-list">
        <div class="text-center p-3">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    <div class="text-center mt-3">
        <a href="appointments.php" class="btn btn-outline-primary btn-sm">View All Appointments</a>
    </div>
</section>
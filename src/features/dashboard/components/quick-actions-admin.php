<style>
    .quick-actions {
        background: var(--color-white);
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: var(--box-shadow);
        margin-top: 1rem;
    }

    .action-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        margin-bottom: 0.75rem;
        background: #f8f9fa;
        border: none;
        border-radius: 0.5rem;
        text-decoration: none;
        color: var(--color-dark);
        transition: all 0.3s ease;
        width: 100%;
    }

    .action-btn:hover {
        background: var(--color-primary);
        color: white;
        transform: translateY(-2px);
    }

    .action-btn i {
        font-size: 1.5rem;
        width: 24px;
    }
</style>

<section class="quick-actions">
    <h3>Quick Actions</h3>

    <a href="appointments.php" class="action-btn">
        <i class="bx bx-calendar-plus"></i>
        <div>
            <div class="fw-bold">New Appointment</div>
            <small class="text-muted">Schedule appointment</small>
        </div>
    </a>

    <a href="users.php" class="action-btn">
        <i class="bx bx-user-plus"></i>
        <div>
            <div class="fw-bold">Add User</div>
            <small class="text-muted">Register new user</small>
        </div>
    </a>

    <a href="services.php" class="action-btn">
        <i class="bx bx-plus-medical"></i>
        <div>
            <div class="fw-bold">Add Service</div>
            <small class="text-muted">Create new service</small>
        </div>
    </a>

    <a href="products.php" class="action-btn">
        <i class="bx bx-package"></i>
        <div>
            <div class="fw-bold">Add Product</div>
            <small class="text-muted">Add new product</small>
        </div>
    </a>

    <a href="reservations.php" class="action-btn">
        <i class="bx bx-list-check"></i>
        <div>
            <div class="fw-bold">View Reservations</div>
            <small class="text-muted">Manage orders</small>
        </div>
    </a>
</section>
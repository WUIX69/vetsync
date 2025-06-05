<style>
    /* Service Stats */
    .service-stats {
        margin-bottom: 2rem;
    }

    .service-stats .box {
        padding: 1.5rem;
        border-radius: 0.5rem;
        background: var(--color-white);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .service-stats .stat-card {
        text-align: center;
        padding: 1.5rem 1rem;
    }

    .service-stats .stat-card .icon {
        font-size: 2rem;
        margin-bottom: 1rem;
        color: var(--bs-primary);
    }

    .service-stats .stat-card .count {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .service-stats .stat-card .label {
        color: #6b7280;
        font-size: 0.9rem;
    }
</style>
<!-- Service Stats -->
<section class="service-stats">
    <div class="row g-4">
        <div class="col-md-3">
            <div class="box stat-card">
                <div class="icon">
                    <i class="check circle icon"></i>
                </div>
                <div class="count">12</div>
                <div class="label">Available Services</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box stat-card">
                <div class="icon">
                    <i class="times circle icon"></i>
                </div>
                <div class="count">3</div>
                <div class="label">Unavailable Services</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box stat-card">
                <div class="icon">
                    <i class="calendar check icon"></i>
                </div>
                <div class="count">254</div>
                <div class="label">Bookings This Month</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box stat-card">
                <div class="icon">
                    <i class="dollar sign icon"></i>
                </div>
                <div class="count">$5,420</div>
                <div class="label">Revenue This Month</div>
            </div>
        </div>
    </div>
</section>
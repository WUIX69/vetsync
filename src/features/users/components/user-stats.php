<style>
    .user-stats {
        margin-bottom: 2rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 1rem;
        padding: 2rem 1.5rem;
        box-shadow: var(--box-shadow);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-info {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        width: 100%;
    }

    .stat-info h6 {
        color: var(--color-info-dark);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .stat-info h2 {
        color: var(--color-dark);
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }
</style>

<section class="user-stats">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <h6>Total Users</h6>
                <h2 id="total-users-count">Loading...</h2>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h6>Active Users</h6>
                <h2 id="active-users-count">Loading...</h2>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h6>New Users (Today)</h6>
                <h2 id="new-users-today-count">Loading...</h2>
            </div>
        </div>
    </div>
</section>
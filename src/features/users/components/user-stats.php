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
        padding: 1.5rem;
        box-shadow: var(--box-shadow);
        display: flex;
        align-items: center;
        justify-content: space-between;
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

    .stat-progress {
        width: 60px;
        height: 60px;
    }

    .circular-chart {
        width: 100%;
        height: 100%;
    }

    .percentage {
        font-size: 0.7rem;
        font-weight: 600;
        text-anchor: middle;
        dominant-baseline: middle;
    }
</style>

<section class="user-stats">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <h6>Total Users</h6>
                <h2 id="total-users-count">Loading...</h2>
            </div>
            <div class="stat-progress">
                <svg viewBox="0 0 36 36" class="circular-chart">
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none"
                        stroke="#eee" stroke-width="3" />
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none"
                        stroke="#20c997" stroke-width="4.3" stroke-dasharray="0, 100" id="total-users-progress" />
                    <text x="18" y="20.35" class="percentage" id="total-users-percentage">0%</text>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h6>Active Users</h6>
                <h2 id="active-users-count">Loading...</h2>
            </div>
            <div class="stat-progress">
                <svg viewBox="0 0 36 36" class="circular-chart">
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none"
                        stroke="#eee" stroke-width="3" />
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none"
                        stroke="#007bff" stroke-width="4.3" stroke-dasharray="0, 100" id="active-users-progress" />
                    <text x="18" y="20.35" class="percentage" id="active-users-percentage">0%</text>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h6>New Users (Today)</h6>
                <h2 id="new-users-today-count">Loading...</h2>
            </div>
            <div class="stat-progress">
                <svg viewBox="0 0 36 36" class="circular-chart">
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none"
                        stroke="#eee" stroke-width="3" />
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none"
                        stroke="#ff0060" stroke-width="4.3" stroke-dasharray="0, 100" id="new-users-today-progress" />
                    <text x="18" y="20.35" class="percentage" id="new-users-today-percentage">0%</text>
                </svg>
            </div>
        </div>
    </div>
</section>
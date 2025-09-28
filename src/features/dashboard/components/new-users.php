<style>
    .new-users {
        background: var(--color-white);
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: var(--box-shadow);
        margin-top: 1rem;
    }

    .user-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-top: 1rem;
    }

    .user-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 1rem;
        border-radius: 0.5rem;
        background: #f8f9fa;
        transition: transform 0.2s ease;
    }

    .user-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        margin-bottom: 0.5rem;
        font-size: 1.2rem;
    }

    .user-avatar img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }

    .user-name {
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--color-dark);
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }

    .user-time {
        font-size: 0.75rem;
        color: var(--color-info-dark);
    }

    @media (max-width: 768px) {
        .user-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .user-card {
            padding: 0.75rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
    }
</style>

<section class="new-users">
    <h3>New Users</h3>
    <div id="new-users-grid" class="user-grid">
        <div class="text-center p-3" style="grid-column: 1 / -1;">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
</section>
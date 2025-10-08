<style>
    .new-users {
        background: var(--color-white);
        border-radius: 1rem;
        padding: 4.5rem;
        box-shadow: var(--box-shadow);
        margin-top: 1rem;
    }

    .new-users h3 {
        margin: 0 0 1rem 0;
        font-weight: 600;
        font-size: 1.125rem;
        /* Add this to match appointments */
    }

    .user-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.8rem;
        margin-top: 0;
    }

    .user-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 0.8rem;
        border-radius: 0.5rem;
        background: #f8f9fa;
        transition: transform 0.2s ease;
    }

    .user-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .user-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        margin-bottom: 0.4rem;
        font-size: 1.1rem;
    }

    .user-avatar img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }

    .user-name {
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--color-dark);
        margin-bottom: 0.2rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }

    .user-time {
        font-size: 0.7rem;
        color: var(--color-info-dark);
    }

    @media (max-width: 768px) {
        .user-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.6rem;
        }

        .user-card {
            padding: 0.6rem;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            font-size: 0.95rem;
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
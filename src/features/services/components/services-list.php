<style>
    /* Services Management Styles */
    .services-container {
        margin-bottom: 2rem;
    }

    .services-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .services-header h2 {
        margin: 0;
        font-size: 1.5rem;
    }

    .services-cards {
        margin-bottom: 2rem;
    }

    .service-card {
        background: var(--color-white);
        border-radius: 0.6rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .service-card .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.2rem 1.5rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .service-card .card-header .title {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .service-card .card-header .title h3 {
        margin: 0;
        font-size: 1.2rem;
    }

    .service-card .card-header .title .icon {
        background: #f3f4f6;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .service-card .card-header .status {
        font-size: 0.8rem;
        padding: 0.3rem 0.8rem;
        border-radius: 50px;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .service-card .card-header .status.available {
        background: #e6f7ed;
        color: #0d9344;
    }

    .service-card .card-header .status.unavailable {
        background: #fbe9e9;
        color: #c53030;
    }

    .service-card .card-body {
        padding: 1.5rem;
    }

    .service-card .card-body .description {
        color: #6b7280;
        font-size: 0.95rem;
        margin-bottom: 1.2rem;
        line-height: 1.6;
    }

    .service-card .card-body .meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .service-card .card-body .meta .item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #6b7280;
        font-size: 0.9rem;
    }

    .service-card .card-footer {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        padding: 1rem 1.5rem;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }

    /* Services Filters */
    .services-filters {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .services-filters .ui.input {
        width: 300px;
    }

    @media (max-width: 768px) {
        .services-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .services-filters {
            flex-direction: column;
            align-items: flex-start;
        }

        .services-filters .ui.input {
            width: 100%;
        }
    }
</style>
<section class="services-container box">
    <div class="services-header">
        <h2>Services Management</h2>
        <button class="ui primary button" id="addServiceBtn">
            <i class="plus icon"></i> Add New Service
        </button>
    </div>

    <div class="services-filters">
        <div class="ui icon input">
            <input type="text" placeholder="Search services...">
            <i class="search icon"></i>
        </div>

        <div class="ui compact selection dropdown">
            <input type="hidden" name="status-filter">
            <i class="filter icon"></i>
            <div class="default text">All Statuses</div>
            <div class="menu">
                <div class="item" data-value="all">All Statuses</div>
                <div class="item" data-value="available">Available</div>
                <div class="item" data-value="unavailable">Unavailable</div>
            </div>
        </div>

        <div class="ui compact selection dropdown">
            <input type="hidden" name="category-filter">
            <i class="filter icon"></i>
            <div class="default text">All Categories</div>
            <div class="menu">
                <div class="item" data-value="all">All Categories</div>
                <div class="item" data-value="examination">Examination</div>
                <div class="item" data-value="treatment">Treatment</div>
                <div class="item" data-value="surgery">Surgery</div>
                <div class="item" data-value="grooming">Grooming</div>
            </div>
        </div>
    </div>

    <div class="services-cards">
        <!-- Service Card -->
        <div class="service-card">
            <div class="card-header">
                <div class="title">
                    <div class="icon">
                        <i class="stethoscope icon"></i>
                    </div>
                    <h3>General Examination</h3>
                </div>
                <div class="status available">
                    <i class="check circle icon"></i>
                    Available
                </div>
            </div>
            <div class="card-body">
                <div class="description">
                    Comprehensive check-up of your pet's overall health status, including weight,
                    temperature, heart and lung sounds, and more.
                </div>
                <div class="meta">
                    <div class="item">
                        <i class="dollar sign icon"></i>
                        <span>$45.00</span>
                    </div>
                    <div class="item">
                        <i class="clock outline icon"></i>
                        <span>30 minutes</span>
                    </div>
                    <div class="item">
                        <i class="tag icon"></i>
                        <span>Examination</span>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="ui basic button">
                    <i class="eye icon"></i>
                    View
                </button>
                <button class="ui primary basic button">
                    <i class="edit icon"></i>
                    Edit
                </button>
                <button class="ui red basic button">
                    <i class="times circle icon"></i>
                    Set Unavailable
                </button>
            </div>
        </div>

        <!-- Service Card 2 -->
        <div class="service-card">
            <div class="card-header">
                <div class="title">
                    <div class="icon">
                        <i class="shower icon"></i>
                    </div>
                    <h3>Pet Grooming</h3>
                </div>
                <div class="status unavailable">
                    <i class="times circle icon"></i>
                    Unavailable
                </div>
            </div>
            <div class="card-body">
                <div class="description">
                    Professional grooming service including bath, hair trimming, nail clipping, ear
                    cleaning, and more based on your pet's needs.
                </div>
                <div class="meta">
                    <div class="item">
                        <i class="dollar sign icon"></i>
                        <span>$65.00</span>
                    </div>
                    <div class="item">
                        <i class="clock outline icon"></i>
                        <span>60 minutes</span>
                    </div>
                    <div class="item">
                        <i class="tag icon"></i>
                        <span>Grooming</span>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="ui basic button">
                    <i class="eye icon"></i>
                    View
                </button>
                <button class="ui primary basic button">
                    <i class="edit icon"></i>
                    Edit
                </button>
                <button class="ui green basic button">
                    <i class="check circle icon"></i>
                    Set Available
                </button>
            </div>
        </div>

        <!-- Service Card 3 -->
        <div class="service-card">
            <div class="card-header">
                <div class="title">
                    <div class="icon">
                        <i class="medkit icon"></i>
                    </div>
                    <h3>Vaccination</h3>
                </div>
                <div class="status available">
                    <i class="check circle icon"></i>
                    Available
                </div>
            </div>
            <div class="card-body">
                <div class="description">
                    Essential vaccinations to protect your pet against common diseases. Based on
                    age, lifestyle, and previous vaccination history.
                </div>
                <div class="meta">
                    <div class="item">
                        <i class="dollar sign icon"></i>
                        <span>$35.00</span>
                    </div>
                    <div class="item">
                        <i class="clock outline icon"></i>
                        <span>15 minutes</span>
                    </div>
                    <div class="item">
                        <i class="tag icon"></i>
                        <span>Treatment</span>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="ui basic button">
                    <i class="eye icon"></i>
                    View
                </button>
                <button class="ui primary basic button">
                    <i class="edit icon"></i>
                    Edit
                </button>
                <button class="ui red basic button">
                    <i class="times circle icon"></i>
                    Set Unavailable
                </button>
            </div>
        </div>
    </div>
</section>
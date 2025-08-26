<?php
?>
<style>
    main section.services {
        position: relative;
        padding-bottom: 3rem;
        margin: 0;
    }

    /* Header */
    main section.services .header {
        display: flex;
        justify-content: end;
        gap: 1rem;
        align-items: center;
        margin-bottom: 2.3rem;
        flex-wrap: nowrap;
    }

    @media screen and (max-width: 768px) {
        main section.services .header {
            flex-direction: column;
        }
    }

    main section.services .header .ui.dropdown {
        background: var(--color-white) !important;
    }

    main section.services .header .ui.search input {
        background: var(--color-white) !important;
    }

    /* Card Styles */
    main section.services .service-card {
        background: var(--color-white);
        padding: 0 !important;
        border-radius: 0.6rem;
        border: 1px solid var(--bs-card-border-color) !important;
        transition: all 0.3s ease;
        height: 100%;
    }

    main section.services .service-card .card-img {
        position: relative;
        overflow: hidden;
        height: 260px;
        border-radius: 0;
    }

    main section.services .service-card .card-img .service-status {
        position: absolute;
        top: 1rem;
        right: 1rem;
    }

    main section.services .service-card .card-img .service-status span {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
        border-radius: 60px;
        background: var(--bs-primary);
        color: var(--bs-white);
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.3rem;
    }

    main section.services .service-card .card-img .service-status span i {
        font-size: 1rem;
    }

    main section.services .service-card .card-img .service-tag {
        position: absolute;
        bottom: 1rem;
        left: 1rem;
    }

    main section.services .service-card .card-img .service-tag span {
        font-size: 0.8rem;
    }

    main section.services .service-card .card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }

    main section.services .service-card .card-body {
        padding: 1.6rem;
        font-size: 0.95rem;
        display: flex;
        flex-direction: column;
    }

    main section.services .service-card .card-body .service-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    main section.services .service-card .card-body .service-header h4 {
        font-weight: bold;
        margin: 0;
        font-size: 1.4rem;
    }

    main section.services .service-card .card-body .service-header i {
        font-size: 24px;
        background: #eff6ff;
        padding: 10px;
        border-radius: 50%;
        color: #031224;
    }

    main section.services .service-card .card-body .service-details {
        margin-bottom: 20px;
    }

    main section.services .service-card .card-body .service-details p {
        color: #667;
        line-height: 1.5;
        margin-bottom: 15px;
    }

    main section.services .service-card .card-body .service-meta {
        flex: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 1px solid #f3f3f3;
    }

    main section.services .service-card .card-body .service-meta .price {
        font-weight: 600;
        font-size: 18px;
        color: #031224;
    }

    main section.services .service-card .card-body .service-meta button {
        border: none;
        color: #fff;
        background: #031224;
        padding: 10px 20px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    main section.services .service-card .card-body .service-meta button:hover {
        background: #062451;
    }

    main section.services .service-card .card-body .service-meta .service-view-btn {
        background: linear-gradient(90deg, #2e7d4f 0%, #1de9b6 100%);
        border: none;
        border-radius: 6px;
        padding: 0.5rem 1.2rem;
        color: #fff;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        margin-right: 0.5rem;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        justify-content: center;
        box-shadow: 0 2px 8px 0 rgba(46, 125, 79, 0.10);
        margin-bottom: 0.5rem;
    }

    main section.services .service-card .card-body .service-meta .service-view-btn:hover {
        background: linear-gradient(90deg, #1de9b6 0%, #2e7d4f 100%);
        color: #b2dfdb;
        text-decoration: none;
        box-shadow: 0 4px 16px 0 rgba(46, 125, 79, 0.18);
    }

    main section.services .service-card .card-body .service-meta .service-view-btn .eye.icon {
        margin-right: 0.4em;
        color: #b2dfdb;
    }

    main section.services .service-card .card-body .service-meta .service-view-btn-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
    }

    /* Status badge styles */
    .available {
        background: rgb(2, 216, 95);
        /* Your original blue color */
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 0.9em;
    }

    .unavailable {
        background: #d9534f;
        /* Your original red color */
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 0.9em;
    }

    .busy {
        background: #f0ad4e;
        /* Your original orange color */
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 0.9em;
    }

    main section.services .service-card .card-body .service-details .duration {
        color: #031224;
        /* Dark color like your price */
        font-weight: 500;
        /* Semi-bold for better readability */
    }

    main section.services .service-card .card-body .service-details .duration strong {
        font-weight: 600;
        /* Make "Duration:" text bolder */
        color: #031224;
    }
</style>

<section class="services">
    <div class="container-xl">
        <!-- Only section-title if its /landing -->
        <?php if (uriAppPath('landing')): ?>
            <div class="section-title">
                <span class="sub-title">Services</span>
                <h2>What We Offer</h2>
                <p>Comprehensive veterinary care for your beloved pets</p>
            </div>
        <?php endif; ?>

        <!-- Header -->
        <div class="header">
            <!-- Status -->
            <div class="sort-container">
                <div class="ui tiny floating selection compact clearable dropdown status-dropdown">
                    <input type="hidden" name="filter">
                    <i class="dropdown icon"></i>
                    <div class="default text">Status By</div>
                    <div class="menu">
                        <div class="item" data-value="available">
                            <i class="check circle icon"></i>Available
                        </div>
                        <div class="item" data-value="unavailable">
                            <i class="times circle icon"></i>Unavailable
                        </div>
                        <div class="item" data-value="busy">
                            <i class="clock icon"></i>Busy
                        </div>
                        <div class="item" data-value="soon">
                            <i class="calendar plus icon"></i>Coming Soon
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sort -->
            <div class="sort-container">
                <div class="ui tiny floating selection compact clearable dropdown sort-dropdown">
                    <input type="hidden" name="sort">
                    <i class="dropdown icon"></i>
                    <div class="default text">Sort By</div>
                    <div class="menu">
                        <div class="item" data-value="newest">
                            <i class="calendar alternate outline icon"></i>Newest
                        </div>
                        <div class="item" data-value="price-low">
                            <i class="sort amount down icon"></i>Price: Low to High
                        </div>
                        <div class="item" data-value="price-high">
                            <i class="sort amount up icon"></i>Price: High to Low
                        </div>
                        <div class="item" data-value="popular">
                            <i class="fire icon"></i>Most Popular
                        </div>
                        <div class="item" data-value="rating">
                            <i class="star icon"></i>Highest Rated
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search -->
            <div class="ui tiny search">
                <div class="ui icon input">
                    <input class="prompt" type="text" placeholder="Search for services...">
                    <i class="search icon"></i>
                </div>
                <div class="results"></div>
            </div>
        </div>

        <!-- Services Grid - Will be populated by JavaScript -->
        <div class="row g-4">
            <!-- Services will be loaded dynamically -->
        </div>

        <!-- Pagination -->
        <?= shared('components/pagination'); ?>
    </div>
</section>

<?= featured('services/components/booknow-modal'); ?> <!-- Book Now Modal -->
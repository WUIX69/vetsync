<style>
    main section.services {
        position: relative;
        padding-top: 0.3rem;
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
            /* flex-wrap: wrap; */
            flex-direction: column;
        }
    }

    main section.services .header .ui.dropdown {
        background: var(--color-white) !important;
    }

    main section.services .header .ui.search input {
        background: var(--color-white) !important;
    }

    /* Header END */

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
        /* font-size: 14px; */
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
</style>
<div class="section-container">
    <section class="section-wrapper services">
        <div class="section-title">
            <span class="sub-title">Services</span>
            <h2>What We Offer</h2>
            <p>Comprehensive veterinary care for your beloved pets</p>
        </div>

        <div class="header">
            <!-- Filter -->
            <div class="sort-container">
                <div class="ui tiny floating selection compact clearable dropdown sort-dropdown">
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

        <div class="row g-4">
            <!-- Vaccination Service -->
            <div class="col-lg-4">
                <div class="service-card card">
                    <div class="card-img">
                        <div class="service-status">
                            <span class="ui green label status-avail available">
                                <i class='bx bx-check-circle'></i> Available</span>
                        </div>
                        <img src="<?= asset('img/contents/services/vaccination.jpg'); ?>" alt="Vaccination Services">
                        <div class="service-tag">
                            <span class="ui primary tag label">Featured</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="service-header">
                            <h4>Vaccination</h4>
                            <i class='bx bx-injection'></i>
                        </div>
                        <div class="service-details">
                            <p>Essential vaccinations to protect your pet against common diseases. Includes
                                consultation and vaccine administration.</p>
                            <p>Duration: 20-30 minutes</p>
                        </div>
                        <div class="service-meta">
                            <span class="price">₱75.00</span>
                            <button>Book Now <i class='bx bx-right-arrow-alt'></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Surgery Service -->
            <div class="col-lg-4">
                <div class="service-card card">
                    <div class="card-img">
                        <div class="service-status">
                            <span class="ui red label status-avail unavailable">
                                <i class='bx bx-x-circle'></i> Unavailable</span>
                        </div>
                        <img src="<?= asset('img/contents/services/surgery.jpg'); ?>" alt="Surgery Services">
                        <div class="service-tag">
                            <span class="ui primary tag label">Featured</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="service-header">
                            <h4>Surgery</h4>
                            <i class='bx bx-plus-medical'></i>
                        </div>
                        <div class="service-details">
                            <p>Professional surgical procedures performed by experienced veterinarians in a
                                state-of-the-art facility.</p>
                            <p>Duration: Varies by procedure</p>
                        </div>
                        <div class="service-meta">
                            <span class="price">From ₱200.00</span>
                            <button>Consult Now <i class='bx bx-right-arrow-alt'></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grooming Service -->
            <div class="col-lg-4">
                <div class="service-card card">
                    <div class="card-img">
                        <div class="service-status">
                            <span class="ui yellow label status-avail busy">
                                <i class='bx bx-time'></i> Busy</span>
                        </div>
                        <img src="<?= asset('img/contents/services/grooming.jpg'); ?>" alt="Pet Grooming">
                        <div class="service-tag">
                            <span class="ui primary tag label">Featured</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="service-header">
                            <h4>Grooming</h4>
                            <i class='bx bx-cut'></i>
                        </div>
                        <div class="service-details">
                            <p>Professional grooming services including bath, haircut, nail trimming, and
                                ear
                                cleaning. Lorem, ipsum dolor.</p>
                            <p>Duration: 60-120 minutes</p>
                        </div>
                        <div class="service-meta">
                            <span class="price">From ₱65.00</span>
                            <button>Book Now <i class='bx bx-right-arrow-alt'></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pet Foods & Accessories Service -->
            <div class="col-lg-4">
                <div class="service-card card">
                    <div class="card-img">
                        <div class="service-status">
                            <span class="ui blue label status-avail available">
                                <i class='bx bx-check-circle'></i> Available</span>
                        </div>
                        <img src="<?= asset('img/contents/services/accessories.jpg'); ?>" alt="Pet Foods & Accessories">
                        <div class="service-tag">
                            <span class="ui primary tag label">Featured</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="service-header">
                            <h4>Pet Supply</h4>
                            <i class='bx bx-store'></i>
                        </div>
                        <div class="service-details">
                            <p>Premium quality pet foods, nutritional supplements, and a wide range of
                                accessories including collars, leashes, beds, toys, and care products.</p>
                            <p>In-store & Online Shopping Available</p>
                        </div>
                        <div class="service-meta">
                            <span class="price">Varies</span>
                            <button>Shop Now <i class='bx bx-cart'></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pet boarding Service -->
            <div class="col-lg-4">
                <div class="service-card card">
                    <div class="card-img">
                        <div class="service-status">
                            <span class="ui teal label status-avail coming-soon">
                                <i class='bx bx-time-five'></i> Coming Soon</span>
                        </div>
                        <img src="<?= asset('img/contents/services/boarding.jpg'); ?>" alt="Coming Soon Service">
                        <div class="service-tag">
                            <span class="ui primary tag label">New</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="service-header">
                            <h4>Boarding</h4>
                            <i class='bx bx-home-heart'></i>
                        </div>
                        <div class="service-details">
                            <p>Safe and comfortable boarding facilities for your pets while you're away.
                                Includes feeding, daily exercise, and medical monitoring if needed. Lorem ipsum dolor
                                sit amet.</p>
                            <p>Coming in September 2023</p>
                        </div>
                        <div class="service-meta">
                            <span class="price">From ₱45.00/night</span>
                            <button>Notify Me <i class='bx bx-bell'></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deworming Service -->
            <!-- <div class="col-lg-4">
                <div class="service-card card">
                    <div class="card-img">
                        <div class="service-status">
                            <span class="ui green label status-avail available">
                                <i class='bx bx-check-circle'></i> Available</span>
                        </div>
                        <img src="<?= asset('img/contents/services/deworm.jpg'); ?>" alt="Vaccination Services">
                        <div class="service-tag">
                            <span class="ui primary tag label">Featured</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="service-header">
                            <h4>Deworming</h4>
                            <i class='bx bx-injection'></i>
                        </div>
                        <div class="service-details">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Incidunt, sapiente? Lorem ipsum
                                dolor sit amet consectetur adipisicing elit. Fugiat, culpa!</p>
                            <p>Duration: 20-30 minutes</p>
                        </div>
                        <div class="service-meta">
                            <span class="price">₱75.00</span>
                            <button>Book Now <i class='bx bx-right-arrow-alt'></i></button>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- Consultation Service -->
            <!-- <div class="col-lg-4">
                <div class="service-card card">
                    <div class="card-img">
                        <div class="service-status">
                            <span class="ui green label status-avail available">
                                <i class='bx bx-check-circle'></i> Available</span>
                        </div>
                        <img src="<?= asset('img/contents/services/consultation.png'); ?>" alt="Vaccination Services">
                        <div class="service-tag">
                            <span class="ui primary tag label">Featured</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="service-header">
                            <h4>Consultation</h4>
                            <i class='bx bx-injection'></i>
                        </div>
                        <div class="service-details">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat id error
                                porro beatae ab aliquam.</p>
                            <p>Duration: 20-30 minutes</p>
                        </div>
                        <div class="service-meta">
                            <span class="price">₱74.00</span>
                            <button>Book Now <i class='bx bx-right-arrow-alt'></i></button>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- Medication Service -->
            <!-- <div class="col-lg-4">
                <div class="service-card card">
                    <div class="card-img">
                        <div class="service-status">
                            <span class="ui green label status-avail available">
                                <i class='bx bx-check-circle'></i> Available</span>
                        </div>
                        <img src="<?= asset('img/contents/services/medication.jpg'); ?>" alt="Vaccination Services">
                        <div class="service-tag">
                            <span class="ui primary tag label">Featured</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="service-header">
                            <h4>Medication</h4>
                            <i class='bx bx-injection'></i>
                        </div>
                        <div class="service-details">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Perspiciatis,
                                corporis! Lorem ipsum dolor sit amet consectetur adipisicing elit. Eius, et!</p>
                            <p>Duration: 20-30 minutes</p>
                        </div>
                        <div class="service-meta">
                            <span class="price">₱52.00</span>
                            <button>Book Now <i class='bx bx-right-arrow-alt'></i></button>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- Laboratory Service -->
            <!-- <div class="col-lg-4">
                <div class="service-card card">
                    <div class="card-img">
                        <div class="service-status">
                            <span class="ui green label status-avail available">
                                <i class='bx bx-check-circle'></i> Available</span>
                        </div>
                        <img src="<?= asset('img/contents/services/laboratory.jpg'); ?>" alt="Vaccination Services">
                        <div class="service-tag">
                            <span class="ui primary tag label">Featured</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="service-header">
                            <h4>Laboratory</h4>
                            <i class='bx bx-injection'></i>
                        </div>
                        <div class="service-details">
                            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quasi architecto
                                eum alias natus quis sit quia nam reprehenderit!</p>
                            <p>Duration: 20-30 minutes</p>
                        </div>
                        <div class="service-meta">
                            <span class="price">₱50.00</span>
                            <button>Book Now <i class='bx bx-right-arrow-alt'></i></button>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- Home Service -->
            <!-- <div class="col-lg-4">
                <div class="service-card card">
                    <div class="card-img">
                        <div class="service-status">
                            <span class="ui green label status-avail available">
                                <i class='bx bx-check-circle'></i> Available</span>
                        </div>
                        <img src="<?= asset('img/contents/services/home.jpg'); ?>" alt="Vaccination Services">
                        <div class="service-tag">
                            <span class="ui primary tag label">Featured</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="service-header">
                            <h4>Home Service</h4>
                            <i class='bx bx-injection'></i>
                        </div>
                        <div class="service-details">
                            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Architecto fugit
                                reprehenderit maxime hic ipsum nobis asperiores voluptate, accusamus
                                voluptatum consequuntur?</p>
                            <p>Duration: 20-30 minutes</p>
                        </div>
                        <div class="service-meta">
                            <span class="price">₱80.00</span>
                            <button>Book Now <i class='bx bx-right-arrow-alt'></i></button>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- Whelping Service -->
            <!-- <div class="col-lg-4">
                <div class="service-card card">
                    <div class="card-img">
                        <div class="service-status">
                            <span class="ui green label status-avail available">
                                <i class='bx bx-check-circle'></i> Available</span>
                        </div>
                        <img src="<?= asset('img/contents/services/whelping.jpg'); ?>" alt="Vaccination Services">
                        <div class="service-tag">
                            <span class="ui primary tag label">Featured</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="service-header">
                            <h4>Whelping</h4>
                            <i class='bx bx-injection'></i>
                        </div>
                        <div class="service-details">
                            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Itaque, molestiae
                                aspernatur perspiciatis eveniet quo corporis? Accusantium sapiente quo non
                                beatae.</p>
                            <p>Duration: 20-30 minutes</p>
                        </div>
                        <div class="service-meta">
                            <span class="price">₱90.00</span>
                            <button>Book Now <i class='bx bx-right-arrow-alt'></i></button>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>

        <!-- Pagination START -->
        <?= shared('components/pagination'); ?>
        <!-- Pagination END -->
    </section>
</div>
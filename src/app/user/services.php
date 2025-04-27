<?php include_once __DIR__ . '/../../utils/php/functions.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta'); ?> <!-- rcs Meta -->
    <title>Services - VetSync</title>
    <?= shared('elements/styles'); ?> <!-- rcs Styles -->
    <style>
        main section.services {
            position: relative;
            padding-top: 0.3rem;
            padding-bottom: 3rem;
            margin: 0;
        }

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
            min-height: 278px;
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
            color: #666;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        main section.services .service-card .card-body .service-meta {
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
</head>

<body>
    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window'); ?> <!-- Window Spinner -->
        <?= shared('layouts/top-redirect-btn'); ?> <!-- Top Redirect Button -->
    </div>

    <div class="site-cont">
        <!-- Site Header -->
        <?= featured('user/shared/layouts/header'); ?>

        <main class="site-main">
            <!-- Header -->
            <section class="header py-5">
                <div class="container-xl">
                    <h1>Services <span class="emoji">🏥</span></h1>
                    <p>Find the right service for your pet, and manage your services.</p>
                </div>
            </section>

            <!-- Services -->
            <section class="services">
                <div class="container-xl">
                    <div class="row g-4">
                        <!-- Vaccination Service -->
                        <div class="col-lg-4">
                            <div class="service-card card">
                                <div class="card-img">
                                    <div class="service-status">
                                        <span class="ui green label status-avail available">
                                            <i class='bx bx-check-circle'></i> Available</span>
                                    </div>
                                    <img src="<?= asset('img/contents/services/vaccination.jpg'); ?>"
                                        alt="Vaccination Services">
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
                                        <span class="price">$75.00</span>
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
                                    <img src="<?= asset('img/contents/services/surgery.jpg'); ?>"
                                        alt="Surgery Services">
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
                                        <span class="price">From $200.00</span>
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
                                        <span class="price">From $65.00</span>
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
                                    <img src="<?= asset('img/contents/services/accessories.jpg'); ?>"
                                        alt="Pet Foods & Accessories">
                                    <div class="service-tag">
                                        <span class="ui primary tag label">Featured</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="service-header">
                                        <h4>Foods & Accessories</h4>
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
                                    <img src="<?= asset('img/contents/services/boarding.jpg'); ?>"
                                        alt="Coming Soon Service">
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
                                            Includes feeding, daily exercise, and medical monitoring if needed.</p>
                                        <p>Coming in September 2023</p>
                                    </div>
                                    <div class="service-meta">
                                        <span class="price">From $45.00/night</span>
                                        <button>Notify Me <i class='bx bx-bell'></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination START -->
                    <?= shared('components/pagination'); ?>
                    <!-- Pagination END -->
                </div>
            </section>
        </main>
    </div>

    <?= shared('elements/scripts'); ?> <!-- rcs Scripts -->
</body>

</html>
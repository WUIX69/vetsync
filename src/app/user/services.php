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
            border-radius: 0.8rem;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            height: 100%;
            transition: all 0.3s;
            border: 1px solid #e5e7eb;
        }

        main section.services .service-card:hover {
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.10);
            transform: translateY(-4px);
        }

        main section.services .card-img {
            position: relative;
            height: 180px;
            overflow: hidden;
        }

        main section.services .card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        main section.services .service-status {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 2;
        }

        main section.services .service-status span {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            border-radius: 30px;
            font-weight: 500;
            font-size: 0.8rem;
            color: white;
        }

        .status-avail.available {
            background: #16a34a;
        }

        .status-avail.unavailable {
            background: #dc2626;
        }

        .status-avail.busy {
            background: #f59e0b;
        }

        .status-avail.coming-soon {
            background: #0ea5e9;
        }

        main section.services .service-tag {
            position: absolute;
            bottom: 15px;
            left: 15px;
        }

        main section.services .service-tag span {
            font-size: 0.75rem;
            padding: 4px 8px;
            background: #3b82f6;
            color: white;
            border-radius: 4px;
            font-weight: 500;
        }

        main section.services .card-body {
            padding: 1.5rem;
        }

        main section.services .service-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        main section.services .service-header h4 {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            color: #111827;
        }

        main section.services .service-header i {
            font-size: 1.25rem;
            background: #f3f4f6;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #374151;
        }

        main section.services .service-details p {
            color: #6b7280;
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 0.5rem;
        }

        main section.services .service-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.25rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }

        main section.services .price {
            font-weight: 600;
            font-size: 1.125rem;
            color: #111827;
        }

        main section.services button {
            background: #111827;
            color: white;
            border: none;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.95rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: background 0.3s;
        }

        main section.services button:hover {
            background: #1f2937;
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
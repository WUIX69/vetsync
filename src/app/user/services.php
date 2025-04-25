<?php include_once __DIR__ . '/../../utils/php/functions.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta'); ?> <!-- rcs Meta -->
    <title>Services - VetSync</title>
    <?= shared('elements/styles'); ?> <!-- rcs Styles -->
    <style>
        main section.services-header {
            padding: 20px 20px 20px;
            background: #031224;
            border-radius: 0 0 30px 30px;
            margin-bottom: 30px;
        }

        main section.services-header .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            margin-bottom: 20px;
        }

        main section.services-header .header h4 {
            color: #f1f3f2;
            font-weight: 500;
            margin: 0;
        }

        main section.services-content {
            padding: 20px;
        }

        .service-card {
            background: #fefefe;
            padding: 25px;
            border-radius: 24px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .service-card .service-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .service-card .service-header h4 {
            font-weight: 600;
            margin: 0;
        }

        .service-card .service-header i {
            font-size: 24px;
            background: #eff6ff;
            padding: 10px;
            border-radius: 50%;
            color: #031224;
        }

        .service-card .service-details {
            margin-bottom: 20px;
        }

        .service-card .service-details p {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .service-card .service-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid #f3f3f3;
        }

        .service-card .service-meta .price {
            font-weight: 600;
            font-size: 18px;
            color: #031224;
        }

        .service-card .service-meta button {
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

        .service-card .service-meta button:hover {
            background: #062451;
        }

        .service-status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-available {
            background: #dcfce7;
            color: #166534;
        }

        .status-busy {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>

<body>
    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window'); ?> <!-- Window Spinner -->
        <?= shared('layouts/top-redirect-btn'); ?> <!-- Top Redirect Button -->
    </div>

    <div class="container-body">
        <!-- Site Header -->
        <?= featured('user/shared/layouts/header'); ?>

        <main class="site-main">
            <section class="services-header">
                <div class="header">
                    <h4>Available Services</h4>
                    <div class="service-status status-available">
                        Currently Available
                    </div>
                </div>
            </section>

            <section class="services-content">
                <div class="row">
                    <!-- Vaccination Service -->
                    <div class="col-lg-4">
                        <div class="service-card box">
                            <img src="<?= asset('img/contents/services/vaccination.jpg'); ?>"
                                alt="Vaccination Services">
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

                    <!-- Surgery Service -->
                    <div class="col-lg-4">
                        <div class="service-card box">
                            <img src="<?= asset('img/contents/services/surgery.jpg'); ?>" alt="Surgery Services">
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

                    <!-- Grooming Service -->
                    <div class="col-lg-4">
                        <div class="service-card box">
                            <img src="<?= asset('img/contents/services/grooming.jpg'); ?>" alt="Pet Grooming">
                            <div class="service-header">
                                <h4>Pet Grooming</h4>
                                <i class='bx bx-cut'></i>
                            </div>
                            <div class="service-details">
                                <p>Professional grooming services including bath, haircut, nail trimming, and ear
                                    cleaning.</p>
                                <p>Duration: 60-120 minutes</p>
                            </div>
                            <div class="service-meta">
                                <span class="price">From $65.00</span>
                                <button>Book Now <i class='bx bx-right-arrow-alt'></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Pet Foods Service -->
                    <div class="col-lg-4">
                        <div class="service-card box">
                            <img src="<?= asset('img/contents/services/foods.jpg'); ?>" alt="Pet Foods">
                            <div class="service-header">
                                <h4>Pet Foods</h4>
                                <i class='bx bx-bowl-hot'></i>
                            </div>
                            <div class="service-details">
                                <p>Premium quality pet foods and nutritional supplements. Prescription diets and
                                    specialized food plans available.</p>
                                <p>Consultation Available</p>
                            </div>
                            <div class="service-meta">
                                <span class="price">Varies</span>
                                <button>Shop Now <i class='bx bx-cart'></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Pet Accessories -->
                    <div class="col-lg-4">
                        <div class="service-card">
                            <img src="<?= asset('img/contents/services/accessories.jpg'); ?>" alt="Pet Accessories">
                            <div class="service-header">
                                <h4>Pet Accessories</h4>
                                <i class='bx bx-store'></i>
                            </div>
                            <div class="service-details">
                                <p>Wide range of quality pet accessories including collars, leashes, beds, toys,
                                    and care products.</p>
                                <p>In-store & Online</p>
                            </div>
                            <div class="service-meta">
                                <span class="price">From $10.00</span>
                                <button>Shop Now <i class='bx bx-cart'></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <?= shared('elements/scripts'); ?> <!-- rcs Scripts -->
    <?= featured('user/services/scripts'); ?>
</body>

</html>
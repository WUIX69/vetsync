<?php
$service = $GLOBALS['service'] ?? null;
if (!$service) {
    return;
}
?>
<style>
    /*----------- MAIN (Single Service) -----------*/

    main section.service .service-title {
        margin-bottom: 2.5rem;
    }

    main section.service .service-title h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--color-dark);
    }

    main section.service .service-tag {
        display: inline-block;
        background: #f8f9fa;
        padding: 0.5rem 1rem;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 1rem;
        color: var(--color-primary);
    }

    main section.service .service-description {
        color: var(--color-text-muted);
        font-size: 1.1rem;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    main section.service .service-image-main {
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);

        width: 100%;
        height: 456px;
    }

    main section.service .service-image-main img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }

    main section.service .service-benefits {
        margin-bottom: 3rem;
    }

    main section.service .service-benefit-card {
        background-color: var(--color-white);
        border-radius: 10px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        height: 100%;
        border: 1px solid #f3f3f3;

        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        gap: 1.5rem;
    }

    main section.service .benefit-icon {
        margin: 0 auto 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: rgba(var(--color-primary-rgb), 0.1);
        color: var(--color-primary);
    }

    main section.service .benefit-icon span {
        font-size: 3.5rem;
    }

    main section.service .benefit-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--color-dark);
    }

    main section.service .benefit-description {
        color: var(--color-text-muted);
        font-size: 0.9rem;
        line-height: 1.5;
    }
</style>

<section class="service pb-5">
    <div class="container-xl">
        <div class="row">
            <div class="col-lg-8">
                <div class="service-image-main">
                    <img src="<?= media($service['uuid']) ?>"
                        alt="<?= htmlspecialchars($service['name'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="service-benefits">
                    <div class="row gap-2">
                        <div class="col-12">
                            <div class="service-benefit-card">
                                <div class="benefit-icon">
                                    <span class="emoji">🩺</span>
                                </div>
                                <div class="benefit-content">
                                    <h4 class="benefit-title">Professional Care</h4>
                                    <p class="benefit-description">Expert veterinary service provided by our experienced
                                        team</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="service-benefit-card">
                                <div class="benefit-icon">
                                    <span class="emoji">⏱️</span>
                                </div>
                                <div class="benefit-content">
                                    <h4 class="benefit-title">Quick Service</h4>
                                    <p class="benefit-description">Efficient service duration of
                                        <?= htmlspecialchars($service['duration'], ENT_QUOTES, 'UTF-8') ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="service-benefit-card">
                                <div class="benefit-icon">
                                    <span class="emoji">💰</span>
                                </div>
                                <div class="benefit-content">
                                    <h4 class="benefit-title">Affordable Price</h4>
                                    <p class="benefit-description">Starting from
                                        ₱<?= number_format(floatval($service['price']), 2) ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="service-benefit-card">
                                <div class="benefit-icon">
                                    <span class="emoji">🏥</span>
                                </div>
                                <div class="benefit-content">
                                    <h4 class="benefit-title">Modern Facility</h4>
                                    <p class="benefit-description">State-of-the-art equipment and comfortable
                                        environment</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
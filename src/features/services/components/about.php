<?php
$service = $GLOBALS['service'] ?? null;
if (!$service) {
    return;
}
?>
<style>
    /*----------- MAIN (About Section) -----------*/
    main section.about {
        background: var(--color-white);
    }

    main section.about h2 {
        font-size: 1.6rem;
        font-weight: 600;
        color: var(--color-dark);
        position: relative;

        margin-top: 2rem;
        margin-bottom: 0.6rem;
    }

    main section.about .services-tag {
        display: inline-block;
        background: var(--color-dark-variant);
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 5px;
        text-transform: uppercase;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    main section.about .details {
        /* margin-bottom: 3rem; */
    }

    main section.about .details p {
        color: var(--color-text-muted);
        /* margin-bottom: 1.5rem; */
        line-height: 1.6;
        font-size: 0.95rem;
    }

    main section.about .service-specs {
        background-color: var(--color-white);
        border: 1px solid #f3f3f3 !important;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    main section.about .service-specs-item {
        padding: 1.5rem;
        border-bottom: 1px solid #f3f3f3;
        display: flex;
        justify-content: space-between;
    }

    main section.about .service-specs-item:last-child {
        border-bottom: none;
    }

    main section.about .spec-label {
        color: var(--color-text-muted);
        font-weight: 500;
    }

    main section.about .spec-value {
        font-weight: 600;
        color: var(--color-dark);
    }

    main section.about .service-cta {
        background-color: var(--color-white);
        border-radius: 12px;
        padding: 2rem;
        box-shadow: var(--box-shadow);
        border: 1px solid #f3f3f3;
    }

    main section.about .service-cta h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--color-dark);
    }

    main section.about .service-cta p {
        color: var(--color-text-muted);
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }

    main section.about .service-cta .ui.button {
        width: 100%;
        padding: 1rem;
        font-weight: 500;
        border-radius: 10px;
    }

    /* Modern Expectation List Styling */
    main section.about ul.expectation-list {
        list-style: none;
        padding: 0;
        margin: 0;
        margin-top: 1.6rem;

        display: flex;
        align-items: center;
        justify-content: start;
        flex-direction: row;
        flex-wrap: wrap;
        gap: 1rem;
    }

    main section.about .expectation-list li {
        display: flex;
        align-items: center;
        /* margin-bottom: 1rem; */
        padding: 0.75rem 1rem;
        background-color: var(--color-light);
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    main section.about .expectation-list li:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    main section.about .expectation-list li i {
        color: var(--color-primary);
        font-size: 1.2rem;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    main section.about .expectation-list li span {
        color: var(--color-dark);
        font-weight: 500;
        line-height: 1.5;
    }
</style>
<section class="about py-5">
    <div class="container-xl">
        <div class="row">
            <div class="col-lg-7">
                <div class="details">
                    <div class="services-tag">Featured</div>

                    <div class="service-details">
                        <h2 class="mt-1">About <?= htmlspecialchars($service['name']) ?></h2>
                        <p><?= nl2br(htmlspecialchars($service['description'])) ?></p>

                        <p>Our experienced veterinary team provides professional
                            <?= strtolower(htmlspecialchars($service['name'])) ?> services
                            using state-of-the-art equipment and following the highest standards of care. We ensure your
                            pet
                            receives personalized attention and the best possible treatment.
                        </p>
                    </div>

                    <div class="expectation">
                        <h2>What to Expect</h2>
                        <p>Your pet's <?= strtolower(htmlspecialchars($service['name'])) ?> will typically include:</p>
                        <ul class="expectation-list">
                            <li>
                                <i class='bx bx-check-circle'></i>
                                <span>Comprehensive consultation</span>
                            </li>
                            <li>
                                <i class='bx bx-health'></i>
                                <span>Professional assessment</span>
                            </li>
                            <li>
                                <i class='bx bx-time'></i>
                                <span>Efficient service delivery</span>
                            </li>
                            <li>
                                <i class='bx bx-shield-quarter'></i>
                                <span>Safe and comfortable environment</span>
                            </li>
                            <li>
                                <i class='bx bx-heart'></i>
                                <span>Compassionate care</span>
                            </li>
                            <li>
                                <i class='bx bx-medal'></i>
                                <span>Expert veterinary guidance</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">

                <!-- Faqs -->
                <?= featured('services/components/col/faqs'); ?>

                <div class="service-cta">
                    <h3>Schedule <?= htmlspecialchars($service['name']) ?></h3>
                    <p>Book an appointment for <?= strtolower(htmlspecialchars($service['name'])) ?> service.
                        Our experienced veterinary team is ready to provide the best care for your beloved companion.
                    </p>
                    <div class="service-specs mb-4">
                        <div class="service-specs-item">
                            <div class="spec-label">Duration</div>
                            <div class="spec-value"><?= htmlspecialchars($service['duration']) ?></div>
                        </div>
                        <div class="service-specs-item">
                            <div class="spec-label">Price</div>
                            <div class="spec-value">₱<?= number_format($service['price'], 2) ?></div>
                        </div>
                        <div class="service-specs-item">
                            <div class="spec-label">Availability</div>
                            <div class="spec-value">Mon-Sat</div>
                        </div>
                        <div class="service-specs-item">
                            <div class="spec-label">Booking Required</div>
                            <div class="spec-value">Yes</div>
                        </div>
                    </div>
                    <a href="#" class="ui blue button" data-open-modal="#bookNowModal">Book
                        Appointment</a>
                </div>
            </div>
        </div>
    </div>
</section>
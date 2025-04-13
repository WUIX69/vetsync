<style>
    /*----------- MAIN (About Section) -----------*/
    main .section-container:has(section.about-section) {
        background: var(--color-white);
    }

    .services-tag {
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

    .service-details {
        margin-bottom: 3rem;
    }

    .service-details h2 {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: var(--color-dark);
        position: relative;
        padding-bottom: 0.5rem;
    }

    .service-details h2:after {
        content: "";
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 3px;
        background-color: var(--color-primary);
    }

    .service-details p {
        color: var(--color-text-muted);
        margin-bottom: 1.5rem;
        line-height: 1.6;
        font-size: 1.05rem;
    }

    .service-specs {
        background-color: var(--color-white);
        border: 1px solid #f3f3f3 !important;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .service-specs-item {
        padding: 1.5rem;
        border-bottom: 1px solid #f3f3f3;
        display: flex;
        justify-content: space-between;
    }

    .service-specs-item:last-child {
        border-bottom: none;
    }

    .spec-label {
        color: var(--color-text-muted);
        font-weight: 500;
    }

    .spec-value {
        font-weight: 600;
        color: var(--color-dark);
    }

    .service-cta {
        background-color: var(--color-white);
        border-radius: 12px;
        padding: 2rem;
        box-shadow: var(--box-shadow);
        border: 1px solid #f3f3f3;
    }

    .service-cta h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--color-dark);
    }

    .service-cta p {
        color: var(--color-text-muted);
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }

    .service-cta .ui.button {
        width: 100%;
        padding: 1rem;
        font-weight: 500;
        border-radius: 10px;
    }

    /* FAQs section */
    .faq-section {
        margin-bottom: 4rem;
    }

    .faq-section h2 {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 2rem;
        color: var(--color-dark);
        position: relative;
        padding-bottom: 0.5rem;
    }

    .faq-section h2:after {
        content: "";
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 3px;
        background-color: var(--color-primary);
    }

    .accordion-item {
        margin-bottom: 1rem;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: var(--box-shadow);
    }

    .accordion-header {
        background-color: var(--color-white);
        padding: 1.2rem 1.5rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        color: var(--color-dark);
        border-bottom: 1px solid #f3f3f3;
    }

    .accordion-content {
        padding: 1.5rem;
        background-color: var(--color-white);
        color: var(--color-text-muted);
        line-height: 1.6;
    }
</style>
<div class="section-container">
    <section class="section-wrapper about-section">
        <div class="row">
            <div class="col-lg-8">
                <div class="service-details">
                    <div class="services-tag">Featured</div>
                    <h2>About Wellness Checkups</h2>
                    <p>Regular wellness checkups are essential for maintaining your pet's health
                        and detecting potential issues before they become serious. Our
                        comprehensive wellness examinations include a thorough physical
                        assessment, preventive care, and personalized recommendations tailored
                        to your pet's specific needs.</p>

                    <p>During your pet's wellness visit, our experienced veterinarians will
                        examine all body systems, including eyes, ears, mouth, skin, heart,
                        lungs, abdomen, and musculoskeletal system. We'll also discuss your
                        pet's lifestyle, behavior, and any concerns you may have.</p>

                    <h2 class="mt-4">What to Expect</h2>
                    <p>Your pet's wellness checkup will typically include:</p>
                    <ul>
                        <li>Comprehensive physical examination</li>
                        <li>Weight and body condition assessment</li>
                        <li>Vaccinations based on lifestyle and risk factors</li>
                        <li>Parasite prevention recommendations</li>
                        <li>Nutritional counseling</li>
                        <li>Dental health evaluation</li>
                        <li>Behavioral assessment</li>
                        <li>Age-appropriate screening tests</li>
                    </ul>

                    <h2 class="mt-4">Why Regular Checkups Matter</h2>
                    <p>Preventive care is the foundation of good health for your pets. Regular
                        wellness examinations allow us to establish a baseline for your pet's
                        health, monitor changes over time, and catch potential issues early when
                        they're most treatable. For younger pets, we focus on proper growth and
                        development, while senior pets benefit from more frequent monitoring of
                        age-related conditions.</p>
                </div>

                <div class="faq-section">
                    <h2>Frequently Asked Questions</h2>
                    <div class="ui fluid accordion">
                        <div class="accordion-item">
                            <div class="accordion-header">
                                How often should my pet have a wellness checkup?
                                <i class="dropdown icon"></i>
                            </div>
                            <div class="accordion-content">
                                <p>We recommend annual wellness examinations for adult pets in
                                    good health. Puppies and kittens may need more frequent
                                    visits during their first year, and senior pets (typically
                                    those over 7 years old) benefit from semi-annual checkups as
                                    they are more prone to developing health issues.</p>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div class="accordion-header">
                                What vaccinations will my pet need?
                                <i class="dropdown icon"></i>
                            </div>
                            <div class="accordion-content">
                                <p>Vaccination recommendations are tailored to your pet's age,
                                    lifestyle, and risk factors. Core vaccines for dogs
                                    typically include rabies, distemper, parvovirus, and
                                    adenovirus. For cats, core vaccines include rabies,
                                    panleukopenia, calicivirus, and herpesvirus. Additional
                                    vaccines may be recommended based on exposure risk.</p>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div class="accordion-header">
                                Should I bring anything to my pet's appointment?
                                <i class="dropdown icon"></i>
                            </div>
                            <div class="accordion-content">
                                <p>Please bring any medical records if you're a new client, a
                                    list of current medications or supplements, a recent stool
                                    sample if possible, and any questions or concerns you'd like
                                    to discuss. For cats, it's best to bring them in a secure
                                    carrier, and dogs should be on a leash.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="service-cta">
                    <h3>Schedule a Wellness Checkup</h3>
                    <p>Give your pet the gift of preventive care with a comprehensive wellness
                        examination. Our experienced veterinary team is ready to provide
                        personalized care for your beloved companion.</p>
                    <div class="service-specs mb-4">
                        <div class="service-specs-item">
                            <div class="spec-label">Duration</div>
                            <div class="spec-value">30-45 minutes</div>
                        </div>
                        <div class="service-specs-item">
                            <div class="spec-label">Price</div>
                            <div class="spec-value">From $50.00</div>
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
    </section>
</div>
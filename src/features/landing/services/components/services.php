<style>
    /*----------- MAIN (Services) -----------*/
    main .section-container:has(section.services-section) {
        background: var(--color-background-variant);
    }

    main section.services-section .services-card {
        background: var(--color-white);
        position: relative !important;
        overflow: hidden;
        height: 400px;
        cursor: pointer;

        border-radius: 0.8rem !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    main section.services-section .services-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    main section.services-section .visible-content {
        position: absolute;
        left: 1.7rem;
        bottom: 2.5rem;
        display: flex;
        flex-direction: column;
        align-items: start;
        justify-content: center;
        gap: 1rem;
    }

    main section.services-section .visible-content .title {
        font-size: 1.9rem;
        font-weight: 600;
        color: var(--color-white);
        text-transform: capitalize;
        text-shadow: var(--text-shadow);
    }

    main section.services-section .hovered-content {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        border-left: 3px solid var(--color-white) !important;
        padding: 1.7rem;
        display: flex;
        flex-direction: column;
        align-items: start;
        justify-content: center;
        gap: 1.5rem;
        transform: translateX(100%);
        transition: transform 0.3s ease-in-out;
    }

    main section.services-section .services-card:hover .hovered-content {
        transform: translateX(0);
    }

    main section.services-section .hovered-content .title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--color-white);
        margin-bottom: -1rem;
        text-transform: capitalize;
    }

    main section.services-section .hovered-content .paragraph {
        color: var(--color-white);
        line-height: 1.6;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        line-clamp: 3;
        -webkit-box-orient: vertical;
    }

    main section.services-section .hovered-content .actions button {
        display: inline-block;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
    }

    main section.services-section .hovered-content .actions button:hover {
        transform: translateY(-2px);
    }

    main section.services-section .hovered-content .actions button a {
        text-decoration: none;
        color: var(--color-dark);
    }
</style>
<div class="section-container">
    <section class="section-wrapper services-section">
        <div class="section-title">
            <span class="sub-title">Services</span>
            <h2>What We Offer</h2>
            <p>Comprehensive veterinary care for your beloved pets</p>
        </div>
        <div class="services-container">
            <div class="row g-4">
                <div class="col-md-4">
                    <!-- Service Card 1 -->
                    <div class="services-card box">
                        <img src="<?= asset('img/contents/services/grooming.jpg'); ?>" alt="Wellness Checkup"
                            class="services-image">
                        <div class="visible-content">
                            <div class="ui tag label red">Hot</div>
                            <div class="title">Consultation</div>
                        </div>
                        <div class="hovered-content">
                            <h3 class="title">Consultation</h3>
                            <p class="paragraph">
                                Our comprehensive wellness checkups include thorough physical
                                examinations,
                                vaccinations, parasite prevention, and nutritional counseling to
                                keep your
                                pets healthy and happy.
                            </p>
                            <?= featured('landing/services/components/ui/servicesact-btn'); ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Service Card 2 -->
                    <div class="services-card box">
                        <img src="<?= asset('img/contents/services/vaccination.jpg'); ?>" alt="Vaccination"
                            class="services-image">
                        <div class="visible-content">
                            <div class="ui tag label teal">Popular</div>
                            <div class="title">Vaccination</div>
                        </div>
                        <div class="hovered-content">
                            <h3 class="title">Vaccination</h3>
                            <p class="paragraph">
                                We provide all core and recommended vaccinations for dogs and cats,
                                following the latest guidelines to ensure your pets are protected
                                against infectious diseases.
                            </p>
                            <?= featured('landing/services/components/ui/servicesact-btn'); ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Service Card 3 -->
                    <div class="services-card box">
                        <img src="<?= asset('img/contents/services/foods.jpg'); ?>" alt="Pet Foods"
                            class="services-image">
                        <div class="visible-content">
                            <div class="ui tag blue label">Boarding</div>
                            <div class="title">Boarding</div>
                        </div>
                        <div class="hovered-content">
                            <h3 class="title">Pet Foods</h3>
                            <p class="paragraph">
                                Quality pet foods and nutritional supplements to keep your pets
                                healthy.
                                We offer a wide range of prescription diets and premium food
                                options.
                            </p>
                            <?= featured('landing/services/components/ui/servicesact-btn'); ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Service Card 4 -->
                    <div class="services-card box">
                        <img src="<?= asset('img/contents/services/accessories.jpg'); ?>" alt="Pet Accessories"
                            class="services-image">
                        <div class="visible-content">
                            <div class="ui tag label">Upcoming</div>
                            <div class="title">Medication</div>
                        </div>
                        <div class="hovered-content">
                            <h3 class="title">Medication</h3>
                            <p class="paragraph">
                                Browse our selection of high-quality pet accessories, including
                                collars,
                                leashes, toys, and grooming supplies for your furry friends.
                            </p>
                            <?= featured('landing/services/components/ui/servicesact-btn'); ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Service Card 5 -->
                    <div class="services-card box">
                        <img src="<?= asset('img/contents/services/accessories.jpg'); ?>" alt="Pet Accessories"
                            class="services-image">
                        <div class="visible-content">
                            <div class="ui tag label">Upcoming</div>
                            <div class="title">Laboratory</div>
                        </div>
                        <div class="hovered-content">
                            <h3 class="title">Laboratory</h3>
                            <p class="paragraph">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eos ipsum iusto fugiat sit
                                voluptatum iste minus tempore cum qui. Esse explicabo aliquid incidunt odio repellat rem
                                quis eos quaerat soluta!
                            </p>
                            <?= featured('landing/services/components/ui/servicesact-btn'); ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Service Card 6 -->
                    <div class="services-card box">
                        <img src="<?= asset('img/contents/services/accessories.jpg'); ?>" alt="Pet Accessories"
                            class="services-image">
                        <div class="visible-content">
                            <div class="ui tag label">Upcoming</div>
                            <div class="title">Deworming</div>
                        </div>
                        <div class="hovered-content">
                            <h3 class="title">Deworming</h3>
                            <p class="paragraph">
                                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Laboriosam optio at aspernatur
                                qui nisi esse provident dolorum? Omnis porro in rem officia quo reiciendis fugit sequi.
                                Veritatis repellat facilis eos!
                            </p>
                            <?= featured('landing/services/components/ui/servicesact-btn'); ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Service Card 7 -->
                    <div class="services-card box">
                        <img src="<?= asset('img/contents/services/accessories.jpg'); ?>" alt="Pet Accessories"
                            class="services-image">
                        <div class="visible-content">
                            <div class="ui tag label">Upcoming</div>
                            <div class="title">Home Service</div>
                        </div>
                        <div class="hovered-content">
                            <h3 class="title">Home Service</h3>
                            <p class="paragraph">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Eligendi rerum sunt dicta
                                ratione accusamus, beatae architecto vel tempore. Veniam dolore dignissimos et. Harum
                                soluta quo culpa earum! Alias, repudiandae fugit!
                            </p>
                            <?= featured('landing/services/components/ui/servicesact-btn'); ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Service Card 8 -->
                    <div class="services-card box">
                        <img src="<?= asset('img/contents/services/accessories.jpg'); ?>" alt="Pet Accessories"
                            class="services-image">
                        <div class="visible-content">
                            <div class="ui tag label">Upcoming</div>
                            <div class="title">Whelping</div>
                        </div>
                        <div class="hovered-content">
                            <h3 class="title">Whelping</h3>
                            <p class="paragraph">
                                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Eos inventore facere,
                                consectetur est voluptatem pariatur laudantium corrupti facilis quam reprehenderit hic
                                enim fuga quae ipsa velit at molestiae iusto reiciendis.
                            </p>
                            <?= featured('landing/services/components/ui/servicesact-btn'); ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Service Card 9 -->
                    <div class="services-card box">
                        <img src="<?= asset('img/contents/services/accessories.jpg'); ?>" alt="Pet Accessories"
                            class="services-image">
                        <div class="visible-content">
                            <div class="ui tag label">Upcoming</div>
                            <div class="title">Pet Supply</div>
                        </div>
                        <div class="hovered-content">
                            <h3 class="title">Pet Supply</h3>
                            <p class="paragraph">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eius, repellat ipsum. Corporis
                                unde modi excepturi assumenda similique beatae. Nesciunt libero minus enim expedita
                                placeat consequatur sunt vero perspiciatis ab dolor.
                            </p>
                            <?= featured('landing/services/components/ui/servicesact-btn'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
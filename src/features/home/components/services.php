<style>
    /*----------- MAIN (Services) -----------*/
    main section.services-section {
        background: var(--color-background-variant);
    }

    main section.services-section .services-container {
        position: relative;
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

    main section.services-section .hovered-content .read-more-btn {
        display: inline-block;
        padding: 8px 20px;
        background: var(--color-white);
        color: var(--color-dark);
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
    }

    main section.services-section .hovered-content .read-more-btn:hover {
        background: rgba(255, 255, 255, 0.9);
        transform: translateY(-2px);
    }
</style>
<section class="services-section">
    <div class="container-xl">
        <div class="section-title">
            <span class="sub-title">Services</span>
            <h2>What We Offer</h2>
        </div>
        <div class="services-container">
            <div class="row g-4">

                <div class="col-md-6">
                    <!-- Info Card 1 -->
                    <div class="services-card box">
                        <img src="<?= asset('img/contents/services/grooming.jpg'); ?>" alt="Mobile Development"
                            class="services-image">
                        <div class="visible-content">
                            <div class="ui tag label red">Hot</div>
                            <div class="title">Grooming</div>
                        </div>
                        <div class="hovered-content">
                            <h3 class="title">Grooming</h3>
                            <p class="paragraph">
                                Cleaning, trimming, and maintaining a pet’s hygiene under the supervision
                                of a veterinarian or trained staff, ensuring both proper grooming and early
                                detection of health issues.
                            </p>
                            <div class="read-more-btn">Read More</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <!-- Info Card 2 -->
                    <div class="services-card box">
                        <img src="<?= asset('img/contents/services/vaccination.jpg'); ?>" alt="Cloud Solutions"
                            class="services-image">
                        <div class="visible-content">
                            <div class="ui tag label teal">Popular</div>
                            <div class="title">Vaccination</div>
                        </div>
                        <div class="hovered-content">
                            <h3 class="title">Vaccination</h3>
                            <p class="paragraph">
                                giving a vaccine to stimulate the body’s immune system, helping it build protection
                                (immunity) against specific diseases. It prepares the body to recognize and fight
                                infections in the future without causing the illness itself.
                            </p>
                            <div class="read-more-btn">Read More</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Info Card 3 -->
                    <div class="services-card box">
                        <img src="<?= asset('img/contents/services/foods.jpg'); ?>" alt="AI Integration"
                            class="services-image">
                        <div class="visible-content">
                            <div class="ui tag blue label">Featured</div>
                            <div class="title">Foods</div>
                        </div>
                        <div class="hovered-content">
                            <h3 class="title">Foods</h3>
                            <p class="paragraph">
                                Specially prepared diets designed to provide pets with the essential nutrients they need
                                for energy, growth, and overall health. They come in different types such as dry kibble,
                                wet food, raw diets, and specialized formulas for age, breed, or health conditions.
                            </p>
                            <div class="read-more-btn">Read More</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Info Card 3 -->
                    <div class="services-card box">
                        <img src="<?= asset('img/contents/services/accessories.jpg'); ?>" alt="AI Integration"
                            class="services-image">
                        <div class="visible-content">
                            <div class="ui tag label">Upcoming</div>
                            <div class="title">accessories</div>
                        </div>
                        <div class="hovered-content">
                            <h3 class="title">accessories</h3>
                            <p class="paragraph">
                                Items designed to support a pet’s comfort, safety, training, and lifestyle. These
                                include essentials
                                like collars, leashes, harnesses, beds, feeding bowls, toys, carriers, and grooming
                                tools that make
                                pet care easier and enhance a pet’s well-being.
                            </p>
                            <div class="read-more-btn">Read More</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Info Card 4 -->
                    <div class="services-card box">
                        <img src="<?= asset('img/contents/services/grooming.jpg'); ?>" alt="AI Integration"
                            class="services-image">
                        <div class="visible-content">
                            <div class="ui tag label">See more</div>
                            <div class="title">Services</div>
                        </div>
                        <div class="hovered-content">
                            <h3 class="title">Services</h3>
                            <p class="paragraph">
                                Click here to see more Services offered!
                            </p>
                            <a class="read-more-btn" href="/src/app/landing/services/index.php">See More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
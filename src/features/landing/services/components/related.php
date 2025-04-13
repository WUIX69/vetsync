<style>
    /*----------- MAIN (Related Services) -----------*/
    .related-services {
        padding: 4rem 0;
        margin-bottom: 3rem;
    }

    .related-tag {
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

    .related-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 2rem;
        line-height: 1.3;
    }

    .related-card {
        background-color: var(--color-white);
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        height: 100%;
        margin-bottom: 1.5rem;
    }

    .related-card-image {
        height: 200px;
        overflow: hidden;
    }

    .related-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: all 0.3s ease;
    }

    .related-card-content {
        padding: 1.5rem;
    }

    .related-card-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--color-dark);
    }

    .related-card-description {
        color: var(--color-text-muted);
        font-size: 0.9rem;
        margin-bottom: 1rem;
        line-height: 1.5;
    }

    .related-card-price {
        color: var(--color-primary);
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .related-card-link {
        display: inline-block;
        padding: 0.5rem 1rem;
        background-color: var(--color-primary);
        color: white;
        border-radius: 5px;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }
</style>
<div class="section-container">
    <section class="section-wrapper related-services">
        <div class="related-tag">RELATED SERVICES</div>
        <h2 class="related-title">You May Also Be Interested In</h2>

        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="related-card">
                    <div class="related-card-image">
                        <img src="../../../../public/assets/img/contents/services/vaccination.jpg" alt="Vaccinations">
                    </div>
                    <div class="related-card-content">
                        <h3 class="related-card-title">Vaccinations</h3>
                        <p class="related-card-description">Protect your pet against common
                            diseases with our comprehensive vaccination packages.</p>
                        <div class="related-card-price">From $35.00</div>
                        <a href="#" class="related-card-link">Learn More</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="related-card">
                    <div class="related-card-image">
                        <img src="../../../../public/assets/img/contents/services/foods.jpg"
                            alt="Nutritional Counseling">
                    </div>
                    <div class="related-card-content">
                        <h3 class="related-card-title">Nutritional Counseling</h3>
                        <p class="related-card-description">Personalized dietary plans to keep
                            your pet at optimal health for every life stage.</p>
                        <div class="related-card-price">From $40.00</div>
                        <a href="#" class="related-card-link">Learn More</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="related-card">
                    <div class="related-card-image">
                        <img src="../../../../public/assets/img/contents/services/accessories.jpg" alt="Dental Care">
                    </div>
                    <div class="related-card-content">
                        <h3 class="related-card-title">Dental Care</h3>
                        <p class="related-card-description">Comprehensive dental services to
                            maintain your pet's oral health and prevent disease.</p>
                        <div class="related-card-price">From $75.00</div>
                        <a href="#" class="related-card-link">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<style>
    /*----------- MAIN (Testimonials) -----------*/
    main section.testimonials-section {
        background: var(--color-background);
    }

    main section.testimonials-section .testimonials-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    main section.testimonials-section .testimonial-card {
        position: relative;
    }

    main section.testimonials-section .testimonial-content {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    main section.testimonials-section .testimonial-text {
        position: relative;
        color: var(--color-dark-variant);
        line-height: 1.6;
    }

    main section.testimonials-section .testimonial-text .quote {
        color: var(--color-dark);
        font-size: 2rem;
        position: absolute;
        top: -10px;
        left: -10px;
    }

    main section.testimonials-section .testimonial-text p {
        text-indent: 1.7rem;
        margin-top: 1.1rem;
    }

    main section.testimonials-section .testimonial-author {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: -0.3rem;
    }

    main section.testimonials-section .testimonial-author img {
        width: 50px;
        height: 50px;
        border: var(--img-border) !important;
    }

    main section.testimonials-section .testimonial-author .author-info {
        flex: 1;
    }

    main section.testimonials-section .testimonial-author .author-info h4 {
        color: var(--color-dark);
        font-weight: 600;
        margin-bottom: 0.2rem;
        text-wrap: nowrap;
    }

    main section.testimonials-section .testimonial-author .author-info p {
        color: var(--color-dark-variant);
        font-size: 0.9rem;
    }

    main section.testimonials-section .testimonial-author span {
        font-size: 3rem;
        color: #4e54c8;
    }
</style>
<section class="testimonials-section">
    <div class="container-xl">
        <div class="section-title">
            <span class="sub-title">Testimonials</span>
            <h2>What Our Clients Say</h2>
            <p>Read what pet owners have to say about their experience with our veterinary services.
            </p>
        </div>
        <div class="testimonials-container">
            <div class="testimonial-card box">
                <div class="testimonial-content">
                    <div class="testimonial-text">
                        <span class="material-icons-sharp quote">format_quote</span>
                        <p>"The staff at Vetsync are amazing! They took such great care of my dog
                            Max during his surgery. The follow-up care and attention to detail was
                            outstanding."</p>
                    </div>
                    <div class="testimonial-author">
                        <img class="rounded-circle" src="<?= asset('img/avatars/chris.jpg'); ?>" alt="Sarah Johnson">
                        <div class="author-info">
                            <h4>Sarah Johnson</h4>
                            <p>Dog Owner</p>
                        </div>
                        <div class="author-social">
                            <button class="ui circular facebook icon button">
                                <i class="facebook icon"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="testimonial-card box">
                <div class="testimonial-content">
                    <div class="testimonial-text">
                        <span class="material-icons-sharp quote">format_quote</span>
                        <p>"I've been bringing my cats here for years. The veterinarians are
                            knowledgeable and caring. They always take time to explain everything
                            thoroughly."</p>
                    </div>
                    <div class="testimonial-author">
                        <img class="rounded-circle" src="<?= asset('img/avatars/stevie.jpg'); ?>" alt="Michael Chen">
                        <div class="author-info">
                            <h4>Michael Chen</h4>
                            <p>Cat Owner</p>
                        </div>
                        <div class="author-social">
                            <button class="ui circular twitter icon button">
                                <i class="twitter icon"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="testimonial-card box">
                <div class="testimonial-content">
                    <div class="testimonial-text">
                        <span class="material-icons-sharp quote">format_quote</span>
                        <p>"The grooming services are excellent! My pet always comes back looking
                            and smelling wonderful. The groomers are gentle and professional."</p>
                    </div>
                    <div class="testimonial-author">
                        <img class="rounded-circle" src="<?= asset('img/avatars/elliot.jpg'); ?>" alt="Emily Rodriguez">
                        <div class="author-info">
                            <h4>Emily Rodriguez</h4>
                            <p>Pet Parent</p>
                        </div>
                        <div class="author-social">
                            <button class="ui circular instagram icon button">
                                <i class="instagram icon"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="testimonial-card box">
                <div class="testimonial-content">
                    <div class="testimonial-text">
                        <span class="material-icons-sharp quote">format_quote</span>
                        <p>"The staff at Vetsync are amazing! They took such great care of my dog
                            Max during his surgery. The follow-up care and attention to detail was
                            outstanding."</p>
                    </div>
                    <div class="testimonial-author">
                        <img class="rounded-circle" src="<?= asset('img/avatars/chris.jpg'); ?>" alt="Sarah Johnson">
                        <div class="author-info">
                            <h4>Sarah Johnson</h4>
                            <p>Dog Owner</p>
                        </div>
                        <div class="author-social">
                            <button class="ui circular facebook icon button">
                                <i class="facebook icon"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="testimonial-card box">
                <div class="testimonial-content">
                    <div class="testimonial-text">
                        <span class="material-icons-sharp quote">format_quote</span>
                        <p>"I've been bringing my cats here for years. The veterinarians are
                            knowledgeable and caring. They always take time to explain everything
                            thoroughly."</p>
                    </div>
                    <div class="testimonial-author">
                        <img class="rounded-circle" src="<?= asset('img/avatars/stevie.jpg'); ?>" alt="Michael Chen">
                        <div class="author-info">
                            <h4>Michael Chen</h4>
                            <p>Cat Owner</p>
                        </div>
                        <div class="author-social">
                            <button class="ui circular twitter icon button">
                                <i class="twitter icon"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="testimonial-card box">
                <div class="testimonial-content">
                    <div class="testimonial-text">
                        <span class="material-icons-sharp quote">format_quote</span>
                        <p>"The grooming services are excellent! My pet always comes back looking
                            and smelling wonderful. The groomers are gentle and professional."</p>
                    </div>
                    <div class="testimonial-author">
                        <img class="rounded-circle" src="<?= asset('img/avatars/elliot.jpg'); ?>" alt="Emily Rodriguez">
                        <div class="author-info">
                            <h4>Emily Rodriguez</h4>
                            <p>Pet Parent</p>
                        </div>
                        <div class="author-social">
                            <button class="ui circular instagram icon button">
                                <i class="instagram icon"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
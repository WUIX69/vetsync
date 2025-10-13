<style>
    /*----------- MAIN (Features) -----------*/
    main section.features-section {
        background: var(--color-background);
    }

    main section.features-section .features-cont {
        position: relative;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    main section.features-section .feature-card {
        background: var(--color-white);
        border: 1px solid transparent !important;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
        overflow: hidden;
    }

    main section.features-section .feature-card:hover {
        transform: translateY(-5px);
        border: 1px solid var(--color-dark-variant) !important;
        cursor: pointer;
        z-index: 10;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    main section.features-section .feature-header {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    main section.features-section .feature-header span {
        font-size: 3.6rem;
        color: #6c9bcf;
    }

    main section.features-section .feature-header .feature-title {
        font-size: 1.6rem;
        color: var(--color-dark);
        text-wrap: nowrap;
    }

    main section.features-section .feature-description {
        color: var(--color-dark-variant);
        line-height: 1.6;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        line-clamp: 4;
        -webkit-box-orient: vertical;
        text-align: center;
        transition: all 0.3s ease;
        max-height: 6.4em;
        /* 4 lines * 1.6 line-height */
        position: relative;
    }

    /* Simple hover effect - just remove the line clamp */
    main section.features-section .feature-card:hover .feature-description {
        -webkit-line-clamp: unset;
        line-clamp: unset;
        max-height: none;
        overflow: visible;
        text-overflow: unset;
        display: block;
        background: var(--color-white);
        padding: 0.5rem;
        border-radius: 0.3rem;
        margin-top: 0.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
</style>

<section class="features-section">
    <div class="container-xl">
        <div class="section-title">
            <span class="sub-title">Features</span>
            <h2>Why Choose Us</h2>
        </div>
        <div class="features-cont">
            <div class="feature-card box">
                <div class="feature-header">
                    <img src="<?= asset('img/icons/fast.png'); ?>" alt="">
                    <h2 class="feature-title">User Friendly Website</h2>
                </div>
                <p class="feature-description">Easy to use menus help user`s find what they need quickly,
                    Clear fonts and spacing improve uderstanding, A search bar lets users locate information fast,
                    uniform styles build professionalism and trust, Prominent buttons guide users toward key actions.
                </p>
            </div>
            <div class="feature-card box">
                <div class="feature-header">
                    <img src="<?= asset('img/icons/24hr.png'); ?>" alt="">
                    <h2 class="feature-title">24/hr Open</h2>
                </div>
                <p class="feature-description"></p>
            </div>
            <div class="feature-card box">
                <div class="feature-header">
                    <img src="<?= asset('img/icons/support.png'); ?>" alt="">
                    <h2 class="feature-title">Support</h2>
                </div>
                <p class="feature-description">Provide users with assistance through multiple channels such as facebook,
                    email,or contact number. These features ensure customers can quickly resolve issues, get
                    guidance,
                    and feel confident using the website or service.</p>
            </div>
        </div>
    </div>
</section>
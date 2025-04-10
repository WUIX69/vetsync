<style>
    /*----------- MAIN (Hero) -----------*/
    main .section-container:has(section.hero-section) {
        height: 60vh;
        position: relative;
    }

    main section.hero-section {
        position: relative;
        width: 100%;
        height: 100%;
        background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
            url('/public/assets/img/contents/hero/about.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    main section.hero-section .hero-content {
        position: relative;
        z-index: 1;
        color: var(--color-white);
        max-width: 800px;
        padding: 0 20px;
    }

    main section.hero-section .hero-content h1 {
        font-size: 3.5rem;
        margin-bottom: 20px;
        color: var(--color-white);
        font-weight: 600;
        text-shadow: var(--text-shadow);
    }

    main section.hero-section .hero-content p {
        font-size: 1.2rem;
        line-height: 1.6;
        margin: 0;
        color: rgba(255, 255, 255, 0.9);
        text-shadow: var(--text-shadow);
    }
</style>

<div class="section-container">
    <section class="hero-section">
        <div class="hero-content">
            <h1>About J.A.A</h1>
            <p>Providing compassionate veterinary care for your beloved pets since 2010. Your pets'
                health and happiness are our top priority.</p>
        </div>
    </section>
</div>
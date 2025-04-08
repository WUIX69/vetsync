<style>
    /* Hero Section Styles */
    .hero-section {
        position: relative;
        width: 100%;
        height: 400px;
        background-image: url('../../../public/assets/img/contents/hero/about.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 0 20px;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
    }

    .hero-section .hero-content {
        position: relative;
        z-index: 1;
        color: white;
        max-width: 800px;
    }

    .hero-section h1 {
        font-size: 3rem;
        margin-bottom: 20px;
        color: white;
    }

    .hero-section p {
        font-size: 1.2rem;
        line-height: 1.6;
        margin: 0;
        color: rgba(255, 255, 255, 0.9);
    }
</style>
<!-- Simple About Header -->
<div class="hero-section">
    <div class="hero-content">
        <h1>About J.A.A</h1>
        <p>Providing compassionate veterinary care for your beloved pets since 2010. Your pets'
            health and happiness are our top priority.</p>
    </div>
</div>
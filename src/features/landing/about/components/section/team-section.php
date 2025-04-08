<style>
    /*----------- MAIN (About Section) -----------*/
    main .section-container:has(section.about-section) {
        background: var(--color-background-variant);
        padding: 4rem 0;
    }

    main section.about-section .section-title {
        text-align: center;
        margin-bottom: 3rem;
    }

    main section.about-section .section-title .sub-title {
        color: var(--color-primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
    }

    main section.about-section .section-title h2 {
        font-size: 2.5rem;
        color: var(--color-dark);
        margin-bottom: 1rem;
    }

    main section.about-section .about-card {
        background: var(--color-white);
        position: relative !important;
        overflow: hidden;
        height: 400px;
        cursor: pointer;
        border-radius: 0.8rem !important;
        padding: 0 !important;
        margin: 0 !important;
        box-shadow: var(--box-shadow);
        transition: all 0.3s ease;
    }

    main section.about-section .about-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--box-shadow-hover);
    }

    main section.info-section .info-grid {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        gap: 30px;
        margin-top: 50px;
    }

    main section.info-section .info-grid .info-card .info-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--color-dark);

        display: flex;
        justify-content: center;
        align-items: center;
        gap: 1rem;
    }

    main section.info-section .info-grid .info-card .info-title span {
        font-size: 2rem;
        font-weight: 700;
    }

    main section.info-section .info-grid .info-card .info-title .title-text {
        font-size: 1.6rem;
        font-weight: 700;
    }

    /* About Us Section styles */
    .about-section {
        padding: 120px 0 80px;
        background: linear-gradient(135deg, #2c3e50 0%, #8fd3f4 100%);
        min-height: 100vh;
        position: relative;
    }

    .about-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        position: relative;
        z-index: 2;
    }

    .about-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-top: 50px;
    }

    .about-card {
        background: var(--color-white);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .about-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    }

    .about-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .about-content {
        padding: 30px;
    }

    .about-title {
        font-size: 1.5rem;
        margin-bottom: 15px;
        color: var(--color-dark);
    }

    .about-text {
        color: var(--color-dark-variant);
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .section-title {
        text-align: center;
        margin-bottom: 50px;
        color: white;
    }

    .section-title h1 {
        font-size: 2.5rem;
        color: white;
        margin-bottom: 15px;
    }

    .section-title p {
        color: rgba(255, 255, 255, 0.9);
        max-width: 600px;
        margin: 0 auto;
    }

    /* Optional: Add a subtle pattern overlay */
    .about-section::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('/public/assets/images/pattern.png');
        opacity: 0.1;
        pointer-events: none;
    }

    /* Override Reserve Section styles for its image only */
    main .section-container:has(section.reserve-section) {
        background-image: url('../../../public/assets/img/contents/reserves/reserve-2.jpg');
    }

    /* Hero Section Styles
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
    } */

    /* Update your about-header styles */
    .about-header {
        padding: 80px 0;
        background: white;
        text-align: center;
    }

    .about-header h1 {
        color: #333;
        font-size: 2.5rem;
        margin-bottom: 20px;
    }

    .about-description {
        color: #666;
        max-width: 800px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .team-member {
        margin-bottom: 40px;
    }

    .member-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .member-card:hover {
        transform: translateY(-5px);
    }

    .member-card img {
        width: 100%;
        height: 300px;
        object-fit: cover;
    }

    .member-info {
        padding: 20px;
    }

    .member-info h3 {
        color: var(--color-dark);
        margin-bottom: 5px;
    }

    .member-title {
        color: var(--color-primary);
        font-weight: 600;
        margin-bottom: 10px;
    }

    .member-bio {
        color: var(--color-dark-variant);
        margin-bottom: 15px;
    }

    .social-links a {
        color: var(--color-dark-variant);
        margin-right: 15px;
        transition: color 0.3s ease;
    }

    .social-links a:hover {
        color: var(--color-primary);
    }

    .services-overview {
        padding: 60px 0;
        background: var(--color-background-variant);
    }

    .service-item {
        padding: 30px;
        text-align: center;
    }

    .service-item i {
        color: var(--color-primary);
        margin-bottom: 20px;
    }

    .service-item p {
        margin-bottom: 20px;
        color: var(--color-dark-variant);
    }

    .clinic-history {
        padding: 60px 0;
    }

    .history-image {
        width: 100%;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .clinic-history h2 {
        color: var(--color-dark);
        margin-bottom: 20px;
    }

    .clinic-history p {
        color: var(--color-dark-variant);
        line-height: 1.6;
    }
</style>
<!-- Simple About Header
        <div class="hero-section">
            <div class="hero-content">
                <h1>About J.A.A</h1>
                <p>Providing compassionate veterinary care for your beloved pets since 2010. Your pets'
                    health and happiness are our top priority.</p>
            </div>
        </div> -->

<!-- Team Section -->
<div class="team-grid">
    <div class="row">
        <!-- Team Member 1 -->
        <div class="col-md-4 team-member">
            <div class="member-card">
                <img src="../../../public/assets/img/teams/team1.jpg" alt="Dr. Josephine Anne Angeles">
                <div class="member-info">
                    <h3>Dr. Josephine Anne Angeles</h3>
                    <p class="member-title">Lead Veterinarian & Clinic Director</p>
                    <p class="member-bio">With over a decade of experience in veterinary medicine,
                        Dr. Angeles leads our clinic with expertise in small animal care and
                        surgery.</p>
                    <div class="social-links">
                        <a href="#"><i class="facebook f icon"></i></a>
                        <a href="#"><i class="instagram icon"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Member 2 -->
        <div class="col-md-4 team-member">
            <div class="member-card">
                <img src="../../../public/assets/img/teams/team2.jpg" alt="Dr. Maria Santos">
                <div class="member-info">
                    <h3>Dr. Maria Santos</h3>
                    <p class="member-title">Associate Veterinarian</p>
                    <p class="member-bio">Specializing in preventive care and emergency medicine,
                        Dr. Santos brings warmth and expertise to every patient interaction.</p>
                    <div class="social-links">
                        <a href="#"><i class="facebook f icon"></i></a>
                        <a href="#"><i class="instagram icon"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 team-member">
            <div class="member-card">
                <img src="../../../public/assets/img/teams/team3.jpg" alt="Dr. Maria Santos">
                <div class="member-info">
                    <h3>Dr. Santos</h3>
                    <p class="member-title">Associate Veterinarian</p>
                    <p class="member-bio">Specializing in preventive care and emergency medicine,
                        Dr. Santos brings warmth and expertise to every patient interaction.</p>
                    <div class="social-links">
                        <a href="#"><i class="facebook f icon"></i></a>
                        <a href="#"><i class="instagram icon"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Services Icons Section -->
<div class="services-overview">
    <div class="row text-center">
        <div class="col-md-4">
            <div class="service-item">
                <i class="huge heartbeat icon"></i>
                <p>Complete Pet Wellness Exams and Preventive Care</p>
                <button class="ui teal button">Learn More</button>
            </div>
        </div>
        <div class="col-md-4">
            <div class="service-item">
                <i class="huge syringe icon"></i>
                <p>Vaccinations and Parasite Prevention</p>
                <button class="ui teal button">Learn More</button>
            </div>
        </div>
        <div class="col-md-4">
            <div class="service-item">
                <i class="huge stethoscope icon"></i>
                <p>Advanced Diagnostics and Surgery</p>
                <button class="ui teal button">Learn More</button>
            </div>
        </div>
    </div>
</div>

<!-- Clinic History Section -->
<div class="clinic-history">
    <div class="row">
        <div class="col-md-6">
            <img src="../../../public/assets/img/contents/bg_log.jpg" alt="VetSync Clinic" class="history-image">
        </div>
        <div class="col-md-6">
            <h2>Our Journey</h2>
            <p>Founded by Dr. Josephine Anne Angeles in 2010, VetSync has grown from a small
                neighborhood clinic into a trusted veterinary care center in San Jose Del Monte,
                Bulacan. Our commitment to providing exceptional care and building lasting
                relationships with pets and their families has made us one of the most trusted
                veterinary clinics in the region.</p>
            <p>We believe in combining modern veterinary medicine with compassionate care, ensuring
                that every pet receives the highest quality treatment in a warm and welcoming
                environment.</p>
        </div>
    </div>
</div>
</main>
</div>
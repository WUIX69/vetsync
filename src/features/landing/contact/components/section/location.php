<style>
    /*----------- MAIN (Locations) -----------*/
    main .section-container:has(section.locations-section) {
        background: var(--color-background);
    }

    main section.locations-section .location-container {
        position: relative;
    }

    main section.locations-section .location-map-cont .header,
    main section.locations-section .location-gallery-cont .header {
        display: flex;
        justify-content: start;
        align-items: center;
        gap: 0.2rem;
    }

    main section.locations-section .location-map-cont .header span,
    main section.locations-section .location-gallery-cont .header span {
        position: relative;
        font-size: 3rem;
        /* top: -0.2rem; */
        animation: upDown 1.5s infinite;
    }

    main section.locations-section .location-map-cont .header .title,
    main section.locations-section .location-gallery-cont .header .title {
        font-size: 1.5rem;
        font-weight: 800;
    }

    /*----------- MAIN (Locations)[Map] -----------*/
    main section.locations-section .location-map-cont .header span {
        color: #ea4335;
    }

    main section.locations-section .location-map-cont .google-map {
        position: relative;
        margin-top: 1rem;
        width: 100%;
        height: 100%;
    }

    main section.locations-section .location-map-cont .google-map iframe {
        width: 100%;
        height: 71vh;
        border: 0;
        border-radius: 0.8rem !important;
        box-shadow: var(--box-shadow);
    }
</style>
<div class="section-container">
    <section class="section-wrapper locations-section">
        <div class="section-title">
            <span class="sub-title">Locations</span>
            <h2>Visit Our Clinic</h2>
            <p>Our state-of-the-art facility is equipped with the latest technology to provide the
                best care for
                your pets.</p>
        </div>
        <div class="location-container">
            <div class="location-map-cont">
                <div class="header">
                    <span class="material-icons-sharp">location_on</span>
                    <div class="title">121 Einstein Loop N, Bronx, NY 10475, USA
                    </div>
                </div>
                <div class="google-map">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14920.891757756479!2d-73.83496372506556!3d40.8623107607295!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c28cbc17f4a0c3%3A0x9ae0f1e804a817d!2s121%20Einstein%20Loop%20N%2C%20Bronx%2C%20NY%2010475%2C%20USA!5e0!3m2!1sen!2sth!4v1650470337727!5m2!1sen!2sth"
                        allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </section>
</div>
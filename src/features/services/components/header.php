<style>
    main section.services {
        position: relative;
        /* padding-top: 0.3rem; */
        padding-bottom: 3rem;
        margin: 0;
    }

    /* Header */
    main section.services .header {
        display: flex;
        justify-content: end;
        gap: 1rem;
        align-items: center;
        margin-bottom: 2.3rem;
        flex-wrap: nowrap;
    }

    @media screen and (max-width: 768px) {
        main section.services .header {
            /* flex-wrap: wrap; */
            flex-direction: column;
        }
    }

    main section.services .header .ui.dropdown {
        background: var(--color-white) !important;
    }

    main section.services .header .ui.search input {
        background: var(--color-white) !important;
    }

    /* Header END */
</style>
<section class="header py-5">
    <div class="container-xl">
        <h1>Services <span class="emoji">🏥</span></h1>
        <p>Find the right service for your pet, and manage your services.</p>
    </div>
</section>
<style>
    /*----------- MAIN (Products) -----------*/
    main section.products {
        background: var(--color-background);
        padding-top: 1rem;
        padding-bottom: 3rem;
    }

    /* Products Title */
    .products-title {
        margin-bottom: 2rem;
    }

    .products-title h1 {
        font-size: 2.5rem;
        font-weight: 600;
        color: var(--color-dark);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .products-title h1 i {
        font-size: 2rem;
        color: var(--color-primary);
    }

    .products-title p {
        font-size: 1.1rem;
        color: var(--color-text);
        margin: 0;
    }

    /* Header Layout */
    .header {
        display: flex;
        justify-content: end;
        gap: 1rem;
        align-items: center;
        margin-bottom: 2.3rem;
        flex-wrap: nowrap;
    }

    @media screen and (max-width: 768px) {
        .header {
            flex-direction: column;
        }
    }

    /* Header - Filter Bar */
    main section.products .header .filter-bar {
        display: flex;
        justify-content: start;
        align-items: center;
        padding: 0;
        margin: 0;
        flex: 1;
    }

    @media screen and (max-width: 768px) {
        main section.products .header .filter-bar {
            padding: 0;
            margin: 0;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 0.6rem;
        }
    }

    main section.products .header .filter-bar button {
        padding: 0.8rem 1.5rem;
        margin: 0 0.3rem;
        border-radius: 0.3rem;
        background-color: var(--color-white);
        color: var(--color-dark);
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        text-wrap: nowrap;
    }

    main section.products .header .filter-bar button:hover {
        transform: translateY(-3px);
        box-shadow: var(--box-shadow);
    }

    main section.products .header .filter-bar button.active {
        background-color: var(--color-dark);
        color: var(--color-white);
    }

    /* Header - Filter Bar END */

    main section.products .header .ui.dropdown {
        background: var(--color-white) !important;
    }

    main section.products .header .ui.search input {
        background: var(--color-white) !important;
    }

    /* Header END */

    /* Product Card START */
    main section.products .product-listing {
        position: relative;
        border: 1px solid var(--bs-card-border-color) !important;
        height: 100%;
        border-radius: 0.8rem;
        padding: 0 !important;
        margin: 0;
    }

    /* Product Card - Content 1 */
    main section.products .product-listing .content-1 {
        height: 264px;
        overflow: hidden;
        position: relative;
    }

    main section.products .product-listing .content-1 img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center;
        transition: transform 0.5s ease;
    }

    main section.products .product-listing .content-1:hover img {
        transform: scale(1.1);
    }

    main section.products .product-listing .content-1 .product-tag {
        position: absolute;
        bottom: 15px;
        left: 15px;
    }

    main section.products .product-listing .content-1 .product-price {
        position: absolute;
        top: 15px;
        right: 15px;
        color: var(--color-dark);
        font-weight: 700;
        font-size: 1.2rem;
        background-color: var(--color-background-variant);
        padding: 5px 10px;
        border-radius: 3px;
    }

    /* Product Card - Content 2 */
    main section.products .product-listing .content-2 {
        padding: 1.3rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: start;
        gap: 1rem;
    }

    main section.products .product-listing .content-2 .meta {
        display: flex;
        flex-direction: row;
        gap: 0.5rem;
        background-color: rgba(255, 255, 255, 0.85);
        border-radius: 3px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    main section.products .product-listing .content-2 .meta .vr-line {
        width: 1px;
        background-color: var(--color-dark);
    }

    main section.products .product-listing .content-2 .product-title {
        font-size: 1.4rem;
        font-weight: bold;
        color: var(--color-dark);
    }

    main section.products .product-listing .content-2 .paragraph {
        font-size: 0.95rem;
    }

    main section.products .product-listing .content-2 .product-specs {
        display: flex;
        flex-wrap: wrap;
        gap: 10px 20px;
        line-height: 1.3;
        border-bottom: 1px solid var(--bs-card-border-color) !important;
        padding-bottom: 1.6rem;
    }

    main section.products .product-listing .content-2 .product-spec-item {
        display: flex;
        align-items: center;
        font-size: 0.9rem;
        color: var(--color-text);
    }

    main section.products .product-listing .content-2 .product-spec-item i {
        margin-right: 5px;
        color: var(--color-primary);
    }

    main section.products .product-listing .content-2 .product-footer {
        margin-top: 0.6rem;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    main section.products .product-listing .content-2 .product-footer .learnmore {
        text-wrap: nowrap;
        flex: 1;
    }

    main section.products .product-listing .content-2 .product-footer .add-to-cart-btn {
        text-wrap: nowrap;
        width: 26%;
        margin-left: 0.6rem;
    }

    /* Status Badges */
    .status-badge {
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.9em;
        display: flex;
        align-items: center;
        gap: 0.3rem;
        color: white;
    }

    .status-badge.available {
        background: rgb(2, 216, 95);
    }

    .status-badge.unavailable {
        background: #d9534f;
    }

    .status-badge.busy {
        background: #f0ad4e;
    }

    .status-badge i {
        font-size: 0.8em;
    }
</style>

<!-- Products Section -->
<section class="products">
    <div class="container-xl">
        <!-- Header with filters -->
        <div class="header">
            <!-- Sort -->
            <div class="sort-container">
                <div class="ui tiny floating selection compact clearable dropdown sort-dropdown">
                    <input type="hidden" name="sort">
                    <i class="dropdown icon"></i>
                    <div class="default text">Sort By</div>
                    <div class="menu">
                        <div class="item" data-value="newest">
                            <i class="calendar alternate outline icon"></i>Newest
                        </div>
                        <div class="item" data-value="price-low">
                            <i class="sort amount down icon"></i>Price: Low to High
                        </div>
                        <div class="item" data-value="price-high">
                            <i class="sort amount up icon"></i>Price: High to Low
                        </div>
                        <div class="item" data-value="popular">
                            <i class="fire icon"></i>Most Popular
                        </div>
                        <div class="item" data-value="rating">
                            <i class="star icon"></i>Highest Rated
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search -->
            <div class="ui tiny search">
                <div class="ui icon input">
                    <input class="prompt" type="text" placeholder="Search for products...">
                    <i class="search icon"></i>
                </div>
                <div class="results"></div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="products-grid">
            <div class="row g-4">
                <!-- Dynamic products will be inserted here by user-products-list.js -->
            </div>
        </div>

        <!-- Pagination -->
        <?= shared('components/pagination'); ?>
    </div>
</section>
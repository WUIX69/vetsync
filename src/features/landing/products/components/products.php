<style>
    /*----------- MAIN (Products) -----------*/
    main .section-container:has(section.products-section) {
        background: var(--color-background-variant);
        padding: 4rem 0;
    }

    /* Filter Bar */
    .filter-bar {
        display: flex;
        justify-content: center;
        margin-bottom: 2rem;
    }

    .filter-bar .filter-button {
        padding: 0.8rem 1.5rem;
        margin: 0 0.3rem;
        border-radius: 0.3rem;
        background-color: var(--color-white);
        color: var(--color-dark);
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .filter-bar .filter-button:hover {
        transform: translateY(-3px);
        box-shadow: var(--box-shadow);
    }

    .filter-bar .filter-button.active {
        background-color: var(--color-primary);
        color: var(--color-white);
    }

    /* Product Card */

    .product-listing:hover {
        transform: translateY(-5px);
        box-shadow: var(--box-shadow-hover);
    }

    .product-image-container {
        height: 240px;
        overflow: hidden;
        position: relative;
    }

    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .product-listing:hover .product-image {
        transform: scale(1.1);
    }

    .product-category {
        position: absolute;
        top: 15px;
        left: 15px;
        padding: 5px 10px;
        background-color: rgba(255, 255, 255, 0.85);
        border-radius: 3px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .product-price {
        position: absolute;
        top: 15px;
        right: 15px;
        color: var(--color-primary);
        font-weight: 700;
        font-size: 1.2rem;
        background-color: rgba(255, 255, 255, 0.85);
        padding: 5px 10px;
        border-radius: 3px;
    }

    .product-details {
        padding: 1.5rem;
    }

    .product-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 0.8rem;
        color: var(--color-dark);
    }

    .product-specs {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 1rem;
        gap: 10px 20px;
    }

    .product-spec-item {
        display: flex;
        align-items: center;
        font-size: 0.9rem;
        color: var(--color-text);
    }

    .product-spec-item i {
        margin-right: 5px;
        color: var(--color-primary);
    }

    .floor-info {
        color: var(--color-text-light);
        margin-bottom: 0.8rem;
    }

    .product-footer {
        border-top: 1px solid #eee;
        padding-top: 1rem;
        display: flex;
        justify-content: center;
    }

    .visit-btn {
        padding: 0.6rem 1.5rem;
        background-color: var(--color-primary);
        color: white;
        border-radius: 5px;
        text-align: center;
        transition: all 0.3s ease;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }

    .visit-btn:hover {
        background-color: var(--color-primary-dark);
        transform: translateY(-2px);
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 3rem;
    }

    .pagination .page-item {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--color-white);
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .pagination .page-item:hover {
        background-color: var(--color-primary-light);
    }

    .pagination .page-item.active {
        background-color: var(--color-primary);
        color: var(--color-white);
    }
</style>



<!-- Products Section -->
<div class="section-container">
    <section class="section-wrapper products-section">
        <div class="section-title">
            <span class="sub-title">Our Shop</span>
            <h2>Pet Products</h2>
            <p>Find everything your pet needs in our comprehensive collection</p>
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar">
            <button class="filter-button active">Show All</button>
            <button class="filter-button">Dog Food</button>
            <button class="filter-button">Cat Food</button>
            <button class="filter-button">Supplements</button>
            <button class="filter-button">Accessories</button>
        </div>

        <!-- Products Grid -->
        <div class="section-container">
            <div class="row g-4">
                <!-- Product 1 -->
                <div class="col-md-4">
                    <div class="product-listing box">
                        <img src="<?= asset('img/contents/products/pdogfood.jpg'); ?>" alt="Premium Dog Food"
                            class="product-image">
                        <div class="visible-content">
                            <div class="ui tag label red">Hot</div>
                            <div class="title">Premium Dry Dog Food</div>
                        </div>
                        <div class="hovered-content">
                            <h3 class="title">Premium Dry Dog Food</h3>
                            <p class="paragraph">
                                High-quality dry dog food with balanced nutrition for adult dogs.
                                Contains chicken, rice, and essential vitamins.
                            </p>
                            <div class="product-specs">
                                <div class="product-spec-item">
                                    <i class="weight icon"></i> Weight: 8 kg
                                </div>
                                <div class="product-spec-item">
                                    <i class="heartbeat icon"></i> Life Stage: Adult
                                </div>
                                <div class="product-spec-item">
                                    <i class="paw icon"></i> Breed: All
                                </div>
                                <div class="product-spec-item">
                                    <i class="food icon"></i> Flavor: Chicken
                                </div>
                            </div>
                            <div class="product-price">$22.99</div>
                            <a href="#" class="read-more-btn">Add to Cart</a>
                            <?= featured('landing/services/components/ui/servicesact-btn'); ?>
                        </div>
                    </div>
                </div>

                <!-- Product 2 -->
                <div class="col-md-4">
                    <div class="product-listing box">
                        <img src="<?= asset('img/contents/products/vitamins.jpg'); ?>" alt="Pet Vitamins"
                            class="product-image">
                        <div class="visible-content">
                            <div class="ui tag label teal">Popular</div>
                            <div class="title">Joint Health Supplements</div>
                        </div>
                        <div class="hovered-content">
                            <h3 class="title">Joint Health Supplements</h3>
                            <p class="paragraph">
                                Support your pet's joint health with these premium supplements.
                                Ideal for senior pets or those with mobility issues.
                            </p>
                            <div class="product-specs">
                                <div class="product-spec-item">
                                    <i class="tablets icon"></i> Count: 60 tablets
                                </div>
                                <div class="product-spec-item">
                                    <i class="heartbeat icon"></i> Life Stage: Senior
                                </div>
                                <div class="product-spec-item">
                                    <i class="paw icon"></i> For: Dogs & Cats
                                </div>
                                <div class="product-spec-item">
                                    <i class="certificate icon"></i> Organic: Yes
                                </div>
                            </div>
                            <div class="product-price">$18.50</div>
                            <a href="#" class="read-more-btn">Add to Cart</a>
                            <?= featured('landing/services/components/ui/servicesact-btn'); ?>
                        </div>
                    </div>
                </div>

                <!-- Product 3 -->
                <div class="col-md-4">
                    <div class="product-listing box">
                        <img src="<?= asset('img/contents/products/petcollar.jpg'); ?>" alt="Pet Collar"
                            class="product-image">
                        <div class="visible-content">
                            <div class="ui tag blue label">Featured</div>
                            <div class="title">Adjustable Comfort Collar</div>
                        </div>
                        <div class="hovered-content">
                            <h3 class="title">Adjustable Comfort Collar</h3>
                            <p class="paragraph">
                                Comfortable, durable collar with adjustable sizing.
                                Available in multiple colors to suit your pet's style.
                            </p>
                            <div class="product-specs">
                                <div class="product-spec-item">
                                    <i class="ruler icon"></i> Size: Medium
                                </div>
                                <div class="product-spec-item">
                                    <i class="palette icon"></i> Colors: 4 options
                                </div>
                                <div class="product-spec-item">
                                    <i class="paw icon"></i> For: Dogs
                                </div>
                                <div class="product-spec-item">
                                    <i class="tag icon"></i> Material: Nylon
                                </div>
                            </div>
                            <div class="product-price">$14.99</div>
                            <a href="#" class="read-more-btn">Add to Cart</a>
                            <?= featured('landing/services/components/ui/servicesact-btn'); ?>
                        </div>
                    </div>
                </div>

                <!-- Product 4 -->
                <div class="col-md-4">
                    <div class="product-listing box">
                        <img src="<?= asset('img/contents/products/supplements.jpg'); ?>" alt="Cat Food"
                            class="product-image">
                        <div class="visible-content">
                            <div class="ui tag label">New</div>
                            <div class="title">Grain-Free Cat Food</div>
                        </div>
                        <div class="hovered-content">
                            <h3 class="title">Grain-Free Cat Food</h3>
                            <p class="paragraph">
                                Premium grain-free formula specially designed for cats of all ages.
                                Rich in salmon for a healthy coat and optimal nutrition.
                            </p>
                            <div class="product-specs">
                                <div class="product-spec-item">
                                    <i class="weight icon"></i> Weight: 5 kg
                                </div>
                                <div class="product-spec-item">
                                    <i class="heartbeat icon"></i> Life Stage: All Ages
                                </div>
                                <div class="product-spec-item">
                                    <i class="paw icon"></i> Breed: All
                                </div>
                                <div class="product-spec-item">
                                    <i class="food icon"></i> Flavor: Salmon
                                </div>
                            </div>
                            <div class="product-price">$25.75</div>
                            <a href="#" class="read-more-btn">Add to Cart</a>
                            <?= featured('landing/services/components/ui/servicesact-btn'); ?>
                        </div>
                    </div>
                </div>

                <!-- Product 5 -->
                <div class="col-md-4">
                    <div class="product-listing box">
                        <img src="<?= asset('img/contents/products/pet-accessories.jpg'); ?>" alt="Pet Bed"
                            class="product-image">
                        <div class="visible-content">
                            <div class="ui tag label orange">Limited</div>
                            <div class="title">Orthopedic Pet Bed</div>
                        </div>
                        <div class="hovered-content">
                            <h3 class="title">Orthopedic Pet Bed</h3>
                            <p class="paragraph">
                                Luxurious orthopedic bed that provides superior comfort and support.
                                Perfect for pets with joint issues or senior animals.
                            </p>
                            <div class="product-specs">
                                <div class="product-spec-item">
                                    <i class="ruler icon"></i> Size: Large
                                </div>
                                <div class="product-spec-item">
                                    <i class="palette icon"></i> Colors: Beige
                                </div>
                                <div class="product-spec-item">
                                    <i class="paw icon"></i> For: Dogs & Cats
                                </div>
                                <div class="product-spec-item">
                                    <i class="home icon"></i> Washable: Yes
                                </div>
                            </div>
                            <div class="product-price">$49.99</div>
                            <a href="#" class="read-more-btn">Add to Cart</a>
                            <?= featured('landing/services/components/ui/servicesact-btn'); ?>
                        </div>
                    </div>
                </div>

                <!-- Product 6 -->
                <div class="col-md-4">
                    <div class="product-listing box">
                        <img src="<?= asset('img/contents/products/petfood.jpg'); ?>" alt="Puppy Food"
                            class="product-image">
                        <div class="visible-content">
                            <div class="ui tag label teal">Popular</div>
                            <div class="title">Puppy Growth Formula</div>
                        </div>
                        <div class="hovered-content">
                            <h3 class="title">Puppy Growth Formula</h3>
                            <p class="paragraph">
                                Specially formulated for growing puppies with essential nutrients
                                to support healthy development and strong immune systems.
                            </p>
                            <div class="product-specs">
                                <div class="product-spec-item">
                                    <i class="weight icon"></i> Weight: 6 kg
                                </div>
                                <div class="product-spec-item">
                                    <i class="heartbeat icon"></i> Life Stage: Puppy
                                </div>
                                <div class="product-spec-item">
                                    <i class="paw icon"></i> Breed: Small/Medium
                                </div>
                                <div class="product-spec-item">
                                    <i class="food icon"></i> Flavor: Beef & Rice
                                </div>
                            </div>
                            <div class="product-price">$28.50</div>
                            <a href="#" class="read-more-btn">Add to Cart</a>
                            <?= featured('landing/services/components/ui/servicesact-btn'); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <div class="page-item active">1</div>
                <div class="page-item">2</div>
                <div class="page-item">3</div>
                <div class="page-item">
                    <i class="angle right icon"></i>
                </div>
            </div>
        </div>
    </section>
</div>
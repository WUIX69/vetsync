<style>
    /*----------- MAIN (Product) -----------*/
    .product-section {
        padding: 3rem 0;
        background-color: #fff;
    }

    .product-title {
        margin-bottom: 2.5rem;
    }

    .product-title h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--color-dark);
    }

    .product-category-tag {
        display: inline-block;
        background: var(--color-primary);
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .product-image-main {
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        height: 500px;
    }

    .product-image-main img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }

    .product-thumbnails {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .thumbnail {
        width: 100px;
        height: 100px;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .thumbnail.active {
        border-color: var(--color-primary);
    }

    .thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-info-card {
        background-color: var(--color-white);
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        border: 1px solid #f3f3f3;
    }

    .product-price {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--color-primary);
        margin-bottom: 1rem;
    }

    .stock-status {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 5px;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    .stock-status.in-stock {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    .stock-status.out-stock {
        background-color: #ffebee;
        color: #c62828;
    }

    .quantity-selector {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .quantity-selector label {
        font-weight: 600;
    }

    .quantity-selector .ui.input {
        width: 100px;
    }

    .product-actions {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .product-actions .ui.button {
        flex: 1;
        padding: 1rem;
    }

    .product-specs {
        margin-top: 2rem;
    }

    .spec-item {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #eee;
    }

    .spec-label {
        color: var(--color-text-muted);
        font-weight: 500;
    }

    .spec-value {
        font-weight: 600;
        color: var(--color-dark);
    }
</style><!-- Product Section -->
<div class="section-container">
    <section class="section-wrapper product-section">
        <div class="container">
            <div class="row">
                <!-- Product Images -->
                <div class="col-lg-7">
                    <div class="product-image-main">
                        <img src="<?= asset('img/contents/products/pdogfood.jpg'); ?>" alt="Premium Dog Food">
                    </div>
                    <div class="product-thumbnails">
                        <div class="thumbnail active">
                            <img src="<?= asset('img/contents/products/pdogfood.jpg'); ?>" alt="Thumbnail 1">
                        </div>
                        <div class="thumbnail">
                            <img src="<?= asset('img/contents/products/pdogfood-2.jpg'); ?>" alt="Thumbnail 2">
                        </div>
                        <div class="thumbnail">
                            <img src="<?= asset('img/contents/products/pdogfood-3.jpg'); ?>" alt="Thumbnail 3">
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="col-lg-5">
                    <div class="product-info-card">
                        <div class="product-category-tag">Pet Food</div>
                        <h1>Premium Adult Dog Food</h1>
                        <div class="product-price">$54.99</div>
                        <div class="stock-status in-stock">
                            <i class="check circle icon"></i> In Stock
                        </div>

                        <div class="quantity-selector">
                            <label>Quantity:</label>
                            <div class="ui input">
                                <input type="number" value="1" min="1">
                            </div>
                        </div>

                        <div class="product-actions">
                            <button class="ui primary button">
                                <i class="shopping cart icon"></i>
                                Add to Cart
                            </button>
                            <button class="ui basic button">
                                <i class="heart outline icon"></i>
                                Wishlist
                            </button>
                        </div>

                        <div class="product-specs">
                            <div class="spec-item">
                                <div class="spec-label">Weight</div>
                                <div class="spec-value">15 lbs</div>
                            </div>
                            <div class="spec-item">
                                <div class="spec-label">Life Stage</div>
                                <div class="spec-value">Adult</div>
                            </div>
                            <div class="spec-item">
                                <div class="spec-label">Brand</div>
                                <div class="spec-value">PetCare Premium</div>
                            </div>
                            <div class="spec-item">
                                <div class="spec-label">SKU</div>
                                <div class="spec-value">DOG-FOOD-001</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
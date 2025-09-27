<?php
use VetSync\Models\Products;

// Get random products for related section (excluding current product)
$current_product = $GLOBALS['product'] ?? null;
$current_uuid = $current_product['uuid'] ?? '';

$products_result = Products::all();
$all_products = $products_result['data'] ?? [];

// Filter out current product and get 3 random ones
$related_products = array_filter($all_products, function($product) use ($current_uuid) {
    return $product['uuid'] !== $current_uuid;
});

// Shuffle and take first 3
shuffle($related_products);
$related_products = array_slice($related_products, 0, 3);
?>
<style>
    /*----------- MAIN (Products) -----------*/
    main section.products {
        background: var(--color-background-variant);
    }

    /* Header */
    main section.products .header {
        font-size: 2rem;
        font-weight: 800;
        text-align: left;
        text-wrap: nowrap;

        border-bottom: 1px solid #ddd !important;
        margin-bottom: 2rem;
        padding-bottom: 1.6rem;
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

        /* background: var(--color-background); */
        /* border-bottom: 1px solid var(--bs-card-border-color) !important; */
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

    /* Product Card - Content 1 END */

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
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    main section.products .product-listing .content-2 .product-footer .learnmore {
        text-wrap: nowrap;
        flex: 0 0 auto;
    }

    main section.products .product-listing .content-2 .product-footer .learnmore-btn {
        background: #26a69a !important;
        color: white !important;
        text-decoration: none;
        padding: 0.7rem 1rem;
        border-radius: 0.28571429rem;
        font-weight: 500;
        transition: background-color 0.3s ease;
        font-size: 0.85rem;
    }

    main section.products .product-listing .content-2 .product-footer .learnmore-btn:hover {
        background: #20918a !important;
    }

    main section.products .product-listing .content-2 .product-footer .quantity-controls {
        display: flex;
        align-items: center;
        flex: 0 0 auto;
        margin: 0 0.5rem;
    }

    main section.products .product-listing .content-2 .product-footer .ui.mini.icon.buttons {
        display: flex;
        background: #f8f9fa;
        border-radius: 0.28571429rem;
        overflow: hidden;
    }

    main section.products .product-listing .content-2 .product-footer .ui.mini.icon.buttons .ui.button {
        background: #f8f9fa !important;
        color: #495057 !important;
        border: 1px solid #dee2e6 !important;
        padding: 0.4rem 0.6rem;
        margin: 0;
        border-radius: 0;
        font-size: 0.8rem;
    }

    main section.products .product-listing .content-2 .product-footer .ui.mini.icon.buttons .ui.button:hover {
        background: #e9ecef !important;
    }

    main section.products .product-listing .content-2 .product-footer .ui.mini.icon.buttons .quantity-value {
        background: white !important;
        border-left: 1px solid #dee2e6 !important;
        border-right: 1px solid #dee2e6 !important;
        min-width: 35px;
        text-align: center;
        font-size: 0.8rem;
    }

    main section.products .product-listing .content-2 .product-footer .add-to-cart-btn {
        background: #007bff !important;
        color: white !important;
        text-wrap: nowrap;
        flex: 1;
        min-width: 100px;
        border: none !important;
        padding: 0.7rem 0.8rem;
        border-radius: 0.28571429rem;
        transition: background-color 0.3s ease;
        font-size: 0.85rem;
        text-align: center;
        cursor: pointer;
    }

    main section.products .product-listing .content-2 .product-footer .add-to-cart-btn:hover {
        background: #0056b3 !important;
    }

    main section.products .product-listing .content-2 .product-footer .add-to-cart-btn:disabled {
        background: #6c757d !important;
        cursor: not-allowed;
    }

    /* Make the footer responsive */
    @media (max-width: 768px) {
        main section.products .product-listing .content-2 .product-footer {
            flex-direction: column;
            align-items: stretch;
        }

        main section.products .product-listing .content-2 .product-footer .quantity-controls {
            justify-content: center;
            margin: 0.5rem 0;
    }

    main section.products .product-listing .content-2 .product-footer .add-to-cart-btn {
            width: 100%;
        }
    }

    /* Product Card - Content 2 END */
</style>

<!-- Products Section -->
<section class="products py-5">
    <div class="container-xl">
        <div class="header">
            Related Products
        </div>
        <!-- Products Grid -->
        <div class="products-grid">
            <div class="row g-4">

                <?php if (!empty($related_products)): ?>
                    <?php foreach ($related_products as $product): ?>
                        <?php
                        // Calculate effective price
                        $effective_price = !empty($product['dc_price']) && floatval($product['dc_price']) > 0 
                            ? floatval($product['dc_price']) 
                            : floatval($product['og_price']);
                        
                        // Parse specs and tags
                        $specs = !empty($product['specs']) ? explode(',', $product['specs']) : [];
                        $tags = !empty($product['tags']) ? explode(',', $product['tags']) : [];
                        
                        // Stock status
                        $stock_status = intval($product['stock']) > 0 ? 'available' : 'out-of-stock';
                        ?>
                <div class="col-md-4">
                    <div class="product-listing card">
                        <div class="card-body">
                            <div class="content-1">
                                        <img src="<?= media($product['uuid']) ?>" alt="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>"
                                    class="product-image">
                                <div class="product-tag">
                                            <?php if (!empty($tags)): ?>
                                                <div class="ui tag label <?= rand(0,1) ? 'red' : 'teal' ?>">
                                                    <?= htmlspecialchars(ucfirst(trim($tags[0])), ENT_QUOTES, 'UTF-8') ?>
                                                </div>
                                            <?php else: ?>
                                                <div class="ui tag label blue">Featured</div>
                                            <?php endif; ?>
                                </div>
                                        <div class="product-price">₱<?= number_format($effective_price, 2) ?></div>
                            </div>
                            <div class="content-2">
                                        <h3 class="product-title"><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                                <div class="meta">
                                    <div class="rating">
                                        Rating:&nbsp;
                                                <div class="ui yellow disabled rating" data-rating="<?= rand(3,5) ?>" data-max-rating="5">
                                        </div>
                                    </div>
                                    <div class="vr-line"></div>
                                    <div class="category">
                                        <i class="tag icon"></i>
                                                Product
                                    </div>
                                </div>
                                <p class="paragraph">
                                            <?= htmlspecialchars(substr($product['description'], 0, 100), ENT_QUOTES, 'UTF-8') ?>...
                                </p>
                                <div class="product-specs">
                                    <div class="product-spec-item">
                                                <i class="box icon"></i> Stock: <?= intval($product['stock']) ?>
                                    </div>
                                            <?php if (!empty($specs)): ?>
                                                <?php foreach (array_slice($specs, 0, 3) as $spec): ?>
                                    <div class="product-spec-item">
                                                        <i class="checkmark icon"></i> <?= htmlspecialchars(trim($spec), ENT_QUOTES, 'UTF-8') ?>
                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                </div>
                                <div class="product-footer">
                                    <div class="learnmore">
                                                <a class="learnmore-btn" href="/src/app/user/product-single-view.php?uuid=<?= $product['uuid'] ?>">Learn More</a>
                                    </div>
                                            <div class="quantity-controls">
                                    <div class="ui mini icon buttons">
                                        <button class="ui button decrease-quantity">
                                            <i class="minus icon"></i>
                                        </button>
                                        <div class="ui disabled button quantity-value">1</div>
                                        <button class="ui button increase-quantity">
                                            <i class="plus icon"></i>
                                        </button>
                                    </div>
                                        </div>
                                            <button class="add-to-cart-btn" <?= $stock_status === 'out-of-stock' ? 'disabled' : '' ?>>
                                                <?= $stock_status === 'out-of-stock' ? 'Out of Stock' : 'Add to Cart' ?>
                                            </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <p class="text-center">No related products available.</p>
                </div>
                <?php endif; ?>

                    </div>
                </div>
    </div>
</section>
<?php
$product = $GLOBALS['product'] ?? null;
if (!$product) {
    return;
}

// Parse tags and specs
$tags = !empty($product['tags']) ? explode(',', $product['tags']) : [];
$specs = !empty($product['specs']) ? explode(',', $product['specs']) : [];

// Calculate stock status
$stock_status = intval($product['stock']) > 0 ? 'in-stock' : 'out-stock';
$stock_text = intval($product['stock']) > 0 ? 'In Stock' : 'Out of Stock';

// Calculate effective price
$effective_price = !empty($product['dc_price']) && floatval($product['dc_price']) > 0
    ? floatval($product['dc_price'])
    : floatval($product['og_price']);

$has_discount = !empty($product['dc_price']) && floatval($product['dc_price']) > 0
    && floatval($product['dc_price']) < floatval($product['og_price']);
?>
<style>
    /*----------- MAIN (Product) -----------*/
    main section.highlights {
        /* padding: 4rem 0; */
        background: var(--color-background-variant);
    }

    main section.highlights .product-title {
        margin-bottom: 1.5rem;
    }

    main section.highlights .product-title h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--color-dark);
    }

    main section.highlights .product-category-tag {
        display: inline-block;
        background: var(--color-primary);
        color: white;
        padding: 0.4rem 1rem;
        border-radius: 4px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    main section.highlights .product-image-main {
        border-radius: 0;
        overflow: hidden;
        margin-bottom: 1rem;
        background-color: #FFF8BD;
        height: 500px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    main section.highlights .product-image-main img {
        max-width: 80%;
        max-height: 80%;
        object-fit: contain;
        object-position: center;
    }

    main section.highlights .product-thumbnails {
        display: flex;
        gap: 0.5rem;
        /* margin-bottom: 2rem; */
        justify-content: center;
    }

    main section.highlights .thumbnail {
        width: 30px;
        height: 6px;
        border-radius: 3px;
        overflow: hidden;
        cursor: pointer;
        background-color: #E5E5E5;
        transition: all 0.3s ease;
    }

    main section.highlights .thumbnail.active {
        background-color: var(--color-primary);
    }

    main section.highlights .product-info-card {
        background-color: transparent;
        border-radius: 0;
        padding: 0;
        box-shadow: none;
        border: none;
    }

    main section.highlights .product-rating {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        color: #FFC107;
    }

    main section.highlights .review-count {
        margin-left: 0.5rem;
        font-weight: 500;
        color: var(--color-dark);
    }

    main section.highlights .product-price {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--color-primary);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }

    main section.highlights .original-price {
        text-decoration: line-through;
        color: var(--color-text-muted);
        font-size: 1.2rem;
        margin-left: 1rem;
    }

    main section.highlights .stock-status {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 4px;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    main section.highlights .stock-status.in-stock {
        background-color: #FFC107;
        color: #fff;
    }

    main section.highlights .stock-status.out-stock {
        background-color: #ffebee;
        color: #c62828;
    }

    main section.highlights .product-description {
        margin-bottom: 1.5rem;
        color: var(--color-text-muted);
        line-height: 1.6;
    }

    main section.highlights .size-selector {
        margin-bottom: 1.5rem;
    }

    main section.highlights .size-label {
        display: flex;
        gap: 1rem;
        margin-bottom: 0.6rem;
    }

    main section.highlights .size-label span {
        font-weight: 600;
    }

    main section.highlights .size-label a {
        color: var(--color-text-muted);
        text-decoration: none;
    }

    main section.highlights .size-options {
        display: flex;
        gap: 0.5rem;
    }

    main section.highlights .size-option {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #ddd !important;
        cursor: pointer;
    }

    main section.highlights .size-option:hover {
        background: #FFC107 !important;
        color: var(--color-white) !important;
    }

    main section.highlights .size-option.active {
        border-color: #FFC107 !important;
        color: #FFC107;
    }

    main section.highlights .product-actions {
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        justify-content: start;
        align-items: center;
        gap: 0.5rem;

        margin-bottom: 1rem;
        padding-bottom: 1.3rem;
        border-bottom: 1px solid #ddd !important;

        width: 100%;
    }

    main section.highlights .product-actions .quantity-selector {
        width: 30%;
        border-right: 1px solid #ddd !important;
        padding-right: 1rem;
        margin-right: 0.5rem;
    }

    main section.highlights .product-actions .ui.button {
        padding: 1rem;
        width: 30%;
        text-wrap: nowrap;
    }

    main section.highlights .wishlist-button {
        display: flex;
        align-items: center;
        justify-content: center;
        background: none;
        border: none;
        color: var(--color-text-muted);
        cursor: pointer;
    }

    main section.highlights .wishlist-button i {
        margin-right: 0.5rem;
    }

    main section.highlights .product-meta {
        position: relative;
    }

    main section.highlights .meta-item {
        display: flex;
        flex-direction: row;
        justify-content: start;
        align-items: start;
        gap: 1rem;

        margin-bottom: 0.5rem;
        color: var(--color-dark);
    }

    main section.highlights .meta-item span {
        text-wrap: nowrap;
    }

    main section.highlights .ui.labels {
        display: flex;
        justify-content: start;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.6rem;
    }

    main section.highlights .ui.labels>.label {
        margin: 0;
    }
</style>
<!-- Product Section -->
<section class="highlights py-5">
    <div class="container-xl">
        <div class="row">
            <!-- Product Images -->
            <div class="col-lg-7">
                <div class="product-image-main">
                    <img src="<?= media($product['uuid']) ?>"
                        alt="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="product-thumbnails">
                    <div class="thumbnail active"></div>
                    <div class="thumbnail"></div>
                    <div class="thumbnail"></div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-5">
                <div class="product-info-card">
                    <div class="stock-status <?= $stock_status ?>"><?= $stock_text ?> (<?= intval($product['stock']) ?>)
                    </div>
                    <div class="product-title">
                        <h1><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></h1>
                    </div>

                    <div class="product-rating">
                        <i class="star icon"></i>
                        <i class="star icon"></i>
                        <i class="star icon"></i>
                        <i class="star outline icon"></i>
                        <span class="review-count">- 2 Customer Reviews</span>
                    </div>

                    <div class="product-price">
                        ₱<?= number_format($effective_price, 2) ?>
                        <?php if ($has_discount): ?>
                            <span class="original-price">₱<?= number_format(floatval($product['og_price']), 2) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="product-description">
                        <?= nl2br(htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8')) ?>
                    </div>

                    <?php if (!empty($specs)): ?>
                        <div class="size-selector">
                            <div class="size-label">
                                <span>Specs : </span>
                            </div>
                            <div class="size-options">
                                <?php foreach (array_slice($specs, 0, 4) as $spec): ?>
                                    <div class="size-option"><?= htmlspecialchars(trim($spec), ENT_QUOTES, 'UTF-8') ?></div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="product-actions">
                        <div class="ui input quantity-selector">
                            <input type="number" value="1" min="1" max="<?= intval($product['stock']) ?>">
                        </div>
                        <button class="ui primary compact mini button" <?= intval($product['stock']) <= 0 ? 'disabled' : '' ?>>
                            ADD TO CART
                        </button>
                        <button class="ui basic compact mini button wishlist-button">
                            <i class="heart outline icon"></i> Add To Wishlist
                        </button>
                    </div>

                    <div class="product-meta">
                        <?php if (!empty($tags)): ?>
                            <div class="meta-item tags">
                                <span><i class="tag icon"></i>Tags :</span>
                                <div class="ui circular labels">
                                    <?php foreach ($tags as $tag): ?>
                                        <a class="ui mini grey basic label">
                                            <?= strtoupper(htmlspecialchars(trim($tag), ENT_QUOTES, 'UTF-8')) ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
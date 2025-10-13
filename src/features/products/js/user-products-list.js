// src/features/products/js/user-products-list.js

const productsList = {
    init() {
        this.productsContainer = $(".products .row.g-4");
        this.allProducts = []; // Store all products
        this.currentSort = "default"; // Default sort
        this.loadProducts();
        this.bindEvents();
        this.startAutoRefresh();
    },

    bindEvents() {
        // Handle quantity increase
        $(document).on("click", ".increase-quantity", (e) => {
            e.preventDefault();
            const quantityElement = $(e.currentTarget).siblings(
                ".quantity-value"
            );
            let currentQuantity = parseInt(quantityElement.text()) || 1;
            currentQuantity++;
            quantityElement.text(currentQuantity);
        });

        // Handle quantity decrease
        $(document).on("click", ".decrease-quantity", (e) => {
            e.preventDefault();
            const quantityElement = $(e.currentTarget).siblings(
                ".quantity-value"
            );
            let currentQuantity = parseInt(quantityElement.text()) || 1;
            if (currentQuantity > 1) {
                currentQuantity--;
                quantityElement.text(currentQuantity);
            }
        });

        // Handle add to cart with current quantity
        $(document).on("click", ".add-to-cart-btn", (e) => {
            e.preventDefault();

            // Check if button is disabled (for unavailable products)
            if ($(e.currentTarget).hasClass("disabled")) {
                return false;
            }

            const productCard = $(e.currentTarget).closest(".product-listing");
            const productUuid = productCard.data("product-uuid");
            const quantity =
                parseInt(productCard.find(".quantity-value").text()) || 1;

            // Get selected size if available (default to 'm' for medium)
            const size = productCard.find(".size-selector").val() || "m";

            // Use the Cart object from cart.js to add to cart
            if (typeof Cart !== "undefined") {
                Cart.addToCart(productUuid, quantity, size);
            } else {
                console.error("Cart object not available");
            }
        });

        // Search functionality
        $(".ui.search").search({
            type: "category",
            minCharacters: 2,
            source: [],
            searchFields: ["title"],
            onSelect: (result) => {
                const product = this.allProducts.find(
                    (p) => p.uuid === result.uuid
                );
                if (product) {
                    this.renderProducts([product]);
                }
                return false;
            },
            onSearchQuery: (query) => {
                if (query.length === 0) {
                    this.applySort();
                } else if (query.length >= 2) {
                    // Filter products based on search query
                    const filtered = this.allProducts.filter(
                        (product) =>
                            product.name
                                .toLowerCase()
                                .includes(query.toLowerCase()) ||
                            product.description
                                .toLowerCase()
                                .includes(query.toLowerCase()) ||
                            (product.category &&
                                product.category.label &&
                                product.category.label
                                    .toLowerCase()
                                    .includes(query.toLowerCase()))
                    );
                    this.renderProducts(filtered);
                }
            },
        });

        // Sort dropdown
        $(".sort-dropdown").dropdown({
            onChange: (value) => {
                console.log("Sort changed to:", value);
                this.currentSort = value || "default";
                this.applySort();
            },
        });
    },

    loadProducts() {
        $.ajax({
            url: apiUrl("products") + "products.php",
            method: "GET",
            data: {
                action: "all",
            },
            success: (response) => {
                if (!response.success) {
                    console.error("API Error:", response.message);
                    return;
                }
                this.allProducts = response.data;
                this.updateSearchSource(response.data);
                this.applySort();
            },
            error: (xhr, status, error) => {
                console.error("Error loading products:", error);
            },
        });
    },

    updateSearchSource(products) {
        const searchData = products.map((product) => ({
            title: product.name,
            description: product.category.label,
            uuid: product.uuid,
        }));

        $(".ui.search").search("setting", "source", {
            products: { name: "Products", results: searchData },
        });
    },

    applySort() {
        let sorted = [...this.allProducts];

        switch (this.currentSort) {
            case "newest":
                sorted.sort(
                    (a, b) => new Date(b.created_at) - new Date(a.created_at)
                );
                break;
            case "price-low":
                sorted.sort(
                    (a, b) => parseFloat(a.og_price) - parseFloat(b.og_price)
                );
                break;
            case "price-high":
                sorted.sort(
                    (a, b) => parseFloat(b.og_price) - parseFloat(a.og_price)
                );
                break;
            case "popular":
                sorted.sort(
                    (a, b) => (b.orders_count || 0) - (a.orders_count || 0)
                );
                break;
            case "rating":
                sorted.sort(
                    (a, b) =>
                        (b.weighted_average || 0) - (a.weighted_average || 0)
                );
                break;
            default:
                sorted.sort((a, b) => a.category_id - b.category_id);
        }

        this.renderProducts(sorted);
    },

    renderProducts(products) {
        let html = "";

        products.forEach((product) => {
            // Format price to include peso sign if not already included
            const price = product.og_price.startsWith("₱")
                ? product.og_price
                : `₱${product.og_price}`;

            // Handle discounted price if exists
            const discountedPrice = product.dc_price
                ? `<span class="original-price">${price}</span> ₱${product.dc_price}`
                : price;

            const statusClass = product.status.label.toLowerCase();
            const statusBadge = `
                <div class="status-badge ${statusClass}">
                    <i class="circle icon"></i>
                    ${product.status.label}
                </div>
            `;

            // Handle tags
            const tagsArray = product.tags || [];
            const getTagColor = (tag) => {
                // Map tags to semantic UI colors
                const colorMap = {
                    organic: "green",
                    grainfree: "teal",
                    puppy: "blue",
                    senior: "purple",
                    hypoallergenic: "olive",
                    bestseller: "red",
                    new: "orange",
                    limited: "brown",
                };
                return colorMap[tag.toLowerCase()] || "grey";
            };

            const tagHtml =
                tagsArray.length > 0
                    ? `
                <div class="product-tag">
                    <span class="ui ${getTagColor(tagsArray[0])} tag label">
                        <i class="tag icon"></i>
                        ${tagsArray[0]} ${
                          tagsArray.length > 1 ? `+${tagsArray.length - 1}` : ""
                      }
                    </span>
                </div>
                `
                    : "";

            // Get category icon
            const categoryIcon = product.category?.icon || "box"; // Default to box icon

            // Handle specs
            const specsArray = product.specs || [];
            const specsHtml = specsArray
                .map(
                    (spec) => `
                <div class="product-spec-item">
                    <i class="check icon"></i>${spec}
                </div>
            `
                )
                .join("");

            // Check if product is available for adding to cart
            const isAvailable = statusClass === "available";

            // Generate quantity controls and add to cart button based on availability
            const cartControlsHtml = isAvailable
                ? `
                <div class="ui mini icon buttons">
                    <button class="ui button decrease-quantity">
                        <i class="minus icon"></i>
                    </button>
                    <div class="ui disabled button quantity-value">1</div>
                    <button class="ui button increase-quantity">
                        <i class="plus icon"></i>
                    </button>
                </div>
                <div class="ui vertical animated button add-to-cart-btn" tabindex="0">
                    <div class="hidden content">Add to Cart</div>
                    <div class="visible content">
                        <i class="shop icon"></i>
                    </div>
                </div>
            `
                : `
                <div class="ui mini icon buttons disabled">
                    <button class="ui button disabled">
                        <i class="minus icon"></i>
                    </button>
                    <div class="ui disabled button quantity-value">1</div>
                    <button class="ui button disabled">
                        <i class="plus icon"></i>
                    </button>
                </div>
                <div class="ui vertical button disabled" tabindex="-1" title="Product is currently unavailable">
                    <div class="visible content">
                        <i class="ban icon"></i> Unavailable
                    </div>
                </div>
            `;

            html += `
                <div class="col-md-4">
                    <div class="product-listing card" data-product-uuid="${
                        product.uuid
                    }">
                        <div class="card-body">
                            <div class="content-1">
                                <img src="${product.image}" alt="${
                product.name
            }" class="product-image">
                                ${tagHtml}
                                <div class="product-price">${discountedPrice}</div>
                            </div>
                            <div class="content-2">
                                <h3 class="product-title">${product.name}</h3>
                                <div class="meta">
                                    <div class="category">
                                        <i class="${categoryIcon} icon"></i>
                                        ${product.category.label}
                                    </div>
                                    <div class="vr-line"></div>
                                    <div class="rating">
                                        <span class="stars">${generateRatingStars(
                                            product.weighted_average || 0
                                        )}</span>
                                        ${
                                            product.weighted_average
                                                ? `<span class="rating-text">${product.weighted_average.toFixed(
                                                      1
                                                  )}</span>`
                                                : ""
                                        }
                                        ${
                                            product.total_reviews
                                                ? `<span class="review-count">(${product.total_reviews})</span>`
                                                : ""
                                        }
                                    </div>
                                    <div class="vr-line"></div>
                                    <div class="status">
                                        ${statusBadge}
                                    </div>
                                </div>
                                <p class="paragraph">
                                    ${product.description}
                                </p>
                                <div class="product-specs">
                                    ${specsHtml}
                                </div>
                                <div class="product-footer">
                                    <div class="learnmore">
                                        <a class="ui teal button learnmore-btn" href="/src/app/user/product-single-view.php?uuid=${
                                            product.uuid
                                        }">
                                            Learn More
                                        </a>
                                    </div>
                                    ${cartControlsHtml}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        this.productsContainer.html(html);
        // Only initialize dropdowns inside product cards, NOT the sort dropdown
        this.productsContainer.find(".ui.dropdown").dropdown();
    },

    startAutoRefresh() {
        setInterval(() => this.loadProducts(), 5000);
    },
};

$(document).ready(() => productsList.init());

// Helper function to generate rating stars
function generateRatingStars(rating) {
    let stars = "";
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 >= 0.5;

    // Add full stars
    for (let i = 0; i < fullStars; i++) {
        stars += "★";
    }

    // Add half star if needed
    if (hasHalfStar) {
        stars += "☆";
    }

    // Calculate remaining empty stars
    const totalShown = fullStars + (hasHalfStar ? 1 : 0);
    const emptyStars = 5 - totalShown;

    // Add empty stars
    for (let i = 0; i < emptyStars; i++) {
        stars += "☆";
    }

    return stars;
}

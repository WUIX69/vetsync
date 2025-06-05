<style>
    /* Admin Products Management Styles */
    main {
        padding: 1rem;
        background-color: #f6f8fb;
    }

    main section {
        margin-top: 1rem;
    }

    main section .products-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    main section .products-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--color-dark);
    }

    main section .add-product-btn {
        background-color: #0d6efd;
        color: white;
        border: none;
        border-radius: 0.4rem;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    main section .search-filter-container {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    main section .search-container {
        flex: 1;
        position: relative;
    }

    main section .search-input {
        width: 100%;
        padding: 0.6rem 1rem 0.6rem 2.5rem;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 0.4rem;
        font-size: 0.875rem;
        background-color: white;
    }

    main section .search-icon {
        position: absolute;
        left: 0.8rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    main section .category-filter {
        width: 300px;
    }

    main section .category-select {
        width: 100%;
        padding: 0.6rem 1rem;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 0.4rem;
        font-size: 0.875rem;
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
    }

    main section .products-table-container {
        background-color: white;
        border-radius: 0.8rem;
        box-shadow: 0 0.15rem 0.5rem rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
    }

    main section .products-table {
        width: 100%;
        border-collapse: collapse;
    }

    main section .products-table th {
        text-align: left;
        padding: 1rem 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--color-dark);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    main section .products-table td {
        padding: 1rem 0.5rem;
        vertical-align: middle;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    main section .products-table tr:last-child td {
        border-bottom: none;
    }

    main section .product-image {
        width: 60px;
        height: 60px;
        border-radius: 0.3rem;
        object-fit: cover;
    }

    main section .product-name {
        font-weight: 500;
        color: var(--color-dark);
    }

    main section .product-category {
        color: var(--color-dark-variant);
    }

    main section .product-price {
        font-weight: 600;
        color: var(--color-dark);
    }

    main section .product-stock {
        color: var(--color-dark-variant);
    }

    main section .product-status {
        display: inline-block;
        padding: 0.25rem 0.6rem;
        border-radius: 0.3rem;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
    }

    main section .status-available {
        background-color: rgba(47, 158, 68, 0.1);
        color: #2f9e44;
    }

    main section .status-unavailable {
        background-color: rgba(240, 62, 62, 0.1);
        color: #f03e3e;
    }

    main section .actions-container {
        display: flex;
        gap: 0.5rem;
    }

    main section .action-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        border-radius: 0.3rem;
        cursor: pointer;
    }

    main section .edit-btn {
        background-color: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }

    main section .toggle-btn {
        background-color: rgba(108, 117, 125, 0.1);
        color: #6c757d;
    }

    main section .delete-btn {
        background-color: rgba(240, 62, 62, 0.1);
        color: #f03e3e;
    }
</style>
<!-- Products Management -->
<section>
    <div class="products-header">
        <h2 class="products-title">Products Management</h2>
        <button class="add-product-btn" id="addProductBtn">
            <i class="plus icon"></i> Add New Product
        </button>
    </div>

    <div class="search-filter-container">
        <div class="search-container">
            <span class="search-icon">
                <i class="search icon"></i>
            </span>
            <input type="text" class="search-input" placeholder="Search products..." id="searchProducts">
        </div>
        <div class="category-filter">
            <select class="category-select" id="categoryFilter">
                <option value="">All Categories</option>
                <option value="dog-food">Dog Food</option>
                <option value="cat-food">Cat Food</option>
                <option value="supplements">Supplements</option>
                <option value="accessories">Accessories</option>
            </select>
        </div>
    </div>

    <div class="products-table-container">
        <table class="products-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <img src="<?= asset('img/contents/products/pdogfood.jpg') ?>" alt="Product"
                            class="product-image">
                    </td>
                    <td class="product-name">Premium Dry Dog Food</td>
                    <td class="product-category">Dog Food</td>
                    <td class="product-price">$22.99</td>
                    <td class="product-stock">45</td>
                    <td><span class="product-status status-available">Available</span></td>
                    <td>
                        <div class="actions-container">
                            <button class="action-btn edit-btn" data-product-id="1">
                                <i class="edit icon"></i>
                            </button>
                            <button class="action-btn toggle-btn" data-product-id="1">
                                <i class="toggle off icon"></i>
                            </button>
                            <button class="action-btn delete-btn" data-product-id="1">
                                <i class="trash icon"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <img src="<?= asset('img/contents/products/vitamins.jpg') ?>" alt="Product"
                            class="product-image">
                    </td>
                    <td class="product-name">Joint Health Supplements</td>
                    <td class="product-category">Supplements</td>
                    <td class="product-price">$18.50</td>
                    <td class="product-stock">32</td>
                    <td><span class="product-status status-available">Available</span></td>
                    <td>
                        <div class="actions-container">
                            <button class="action-btn edit-btn" data-product-id="2">
                                <i class="edit icon"></i>
                            </button>
                            <button class="action-btn toggle-btn" data-product-id="2">
                                <i class="toggle off icon"></i>
                            </button>
                            <button class="action-btn delete-btn" data-product-id="2">
                                <i class="trash icon"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <img src="<?= asset('img/contents/products/petfood.jpg') ?>" alt="Product"
                            class="product-image">
                    </td>
                    <td class="product-name">Puppy Growth Formula</td>
                    <td class="product-category">Dog Food</td>
                    <td class="product-price">$28.50</td>
                    <td class="product-stock">0</td>
                    <td><span class="product-status status-unavailable">Unavailable</span></td>
                    <td>
                        <div class="actions-container">
                            <button class="action-btn edit-btn" data-product-id="3">
                                <i class="edit icon"></i>
                            </button>
                            <button class="action-btn toggle-btn" data-product-id="3">
                                <i class="toggle on icon"></i>
                            </button>
                            <button class="action-btn delete-btn" data-product-id="3">
                                <i class="trash icon"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
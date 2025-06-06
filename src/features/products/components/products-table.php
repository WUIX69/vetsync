<style>
    /* Admin Products Management Styles */
    main section.products-table .product-image {
        width: 60px;
        height: 60px;
        border-radius: 0.3rem;
        object-fit: cover;
    }

    main section.products-table .product-status {
        display: inline-block;
        padding: 0.25rem 0.6rem;
        border-radius: 0.3rem;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
    }

    main section.products-table .product-status.available {
        background-color: rgba(47, 158, 68, 0.1);
        color: #2f9e44;
    }

    main section.products-table .product-status.unavailable {
        background-color: rgba(240, 62, 62, 0.1);
        color: #f03e3e;
    }

    main section.products-table .actions-container {
        display: flex;
        gap: 0.5rem;
    }

    main section.products-table .action-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        border-radius: 0.3rem;
        cursor: pointer;
    }

    main section.products-table .action-btn.edit-btn {
        background-color: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }

    main section.products-table .action-btn.toggle-btn {
        background-color: rgba(108, 117, 125, 0.1);
        color: #6c757d;
    }

    main section.products-table .action-btn.delete-btn {
        background-color: rgba(240, 62, 62, 0.1);
        color: #f03e3e;
    }
</style>
<!-- Products Table -->
<section class="products-table">
    <h2 class="title">Products List</h2>
    <div class="container table-list box">
        <div class="table-filters">
            <div class="base-filters">

                <div class="ui fluid mini category search service-search">
                    <div class="ui icon input">
                        <input class="prompt" type="text" placeholder="Search services..." />
                        <i class="search icon"></i>
                    </div>
                    <div class="results"></div>
                </div>

                <div class="ui mini compact selection floating labeled icon dropdown">
                    <input type="hidden" name="status-filter">
                    <i class="filter icon"></i>
                    <div class="default text">All Statuses</div>
                    <div class="menu">
                        <div class="item" data-value="all">All Statuses</div>
                        <div class="item" data-value="available">Available</div>
                        <div class="item" data-value="unavailable">Unavailable</div>
                    </div>
                </div>

                <div class="ui mini compact selection floating labeled icon dropdown">
                    <input type="hidden" name="category-filter">
                    <i class="filter icon"></i>
                    <div class="default text">All Categories</div>
                    <div class="menu">
                        <div class="item" data-value="all">All Categories</div>
                        <div class="item" data-value="examination">Examination</div>
                        <div class="item" data-value="treatment">Treatment</div>
                        <div class="item" data-value="surgery">Surgery</div>
                        <div class="item" data-value="grooming">Grooming</div>
                    </div>
                </div>

            </div>
            <button class="ui mini primary button" data-open-modal="#productModal">
                <i class="plus icon"></i>
                Add Product
            </button>
        </div>
        <div class="table-container">
            <table class="ui fixed single line small center aligned very basic selectable table">
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
                <tbody id="productsTableBody">
                    <!-- Table Data Populated by JS -->
                </tbody>
            </table>
        </div>
    </div>
</section>
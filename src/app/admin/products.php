<?php include_once __DIR__ . '/../../core/app.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta') ?>
    <title>Admin Products Dashboard</title>
    <?= shared('elements/styles') ?>
    <?= featured('admin/dashboard/styles') ?>
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

        /* Product Modal */
        .product-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .product-modal.active {
            display: flex;
        }

        .modal-content {
            background-color: white;
            border-radius: 0.8rem;
            width: 100%;
            max-width: 550px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            animation: modal-in 0.3s ease;
        }

        .modal-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--color-dark);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.25rem;
            cursor: pointer;
            color: #6c757d;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            color: var(--color-dark);
        }

        .form-control {
            width: 100%;
            padding: 0.6rem 0.75rem;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 0.4rem;
            font-size: 0.875rem;
        }

        .form-control:focus {
            outline: 0;
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .form-select {
            width: 100%;
            padding: 0.6rem 0.75rem;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 0.4rem;
            font-size: 0.875rem;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
        }

        .image-preview {
            margin-top: 0.5rem;
            max-width: 100%;
            height: 120px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 0.4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .image-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.4rem;
            border: none;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: #0d6efd;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5c636a;
        }

        @keyframes modal-in {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window') ?> <!-- Window Spinner -->
    </div>

    <div class="container-body pusher">
        <!-- Sidebar -->
        <?= featured('admin/shared/layouts/sidebar') ?> <!-- Sidebar -->

        <!-- Main Content -->
        <main class="container-main">
            <!-- Header -->
            <?= featured('admin/dashboard/components/header') ?>

            <div class="row">
                <div class="col-lg-12">
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
                                <input type="text" class="search-input" placeholder="Search products..."
                                    id="searchProducts">
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

                    <!-- Product Modal -->
                    <div class="product-modal" id="productModal">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 id="modalTitle">Add New Product</h3>
                                <button class="modal-close" id="closeModal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <form id="productForm">
                                    <input type="hidden" id="productId">
                                    <div class="form-group">
                                        <label for="productName">Product Name</label>
                                        <input type="text" class="form-control" id="productName" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="productCategory">Category</label>
                                        <select class="form-select" id="productCategory" required>
                                            <option value="">Select Category</option>
                                            <option value="dog-food">Dog Food</option>
                                            <option value="cat-food">Cat Food</option>
                                            <option value="supplements">Supplements</option>
                                            <option value="accessories">Accessories</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="productPrice">Price ($)</label>
                                        <input type="number" class="form-control" id="productPrice" step="0.01" min="0"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="productStock">Stock Quantity</label>
                                        <input type="number" class="form-control" id="productStock" min="0" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="productDescription">Description</label>
                                        <textarea class="form-control" id="productDescription" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="productImage">Product Image</label>
                                        <input type="file" class="form-control" id="productImage" accept="image/*">
                                        <div class="image-preview">
                                            <img id="imagePreview" src="<?= asset('img/placeholder.jpg') ?>"
                                                alt="Product Image Preview">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="productStatus">Status</label>
                                        <select class="form-select" id="productStatus" required>
                                            <option value="available">Available</option>
                                            <option value="unavailable">Unavailable</option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" id="cancelBtn">Cancel</button>
                                <button class="btn btn-primary" id="saveProductBtn">Save Product</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts') ?>
    <script src="<?= featured('admin/dashboard/js/main.js', true) ?>"></script>
    <!-- <script>
        // Products Management JavaScript
        $(document).ready(function () {
            // Open Product Modal
            $('#addProductBtn').on('click', function () {
                $('#modalTitle').text('Add New Product');
                $('#productForm')[0].reset();
                $('#imagePreview').attr('src', '<?= asset("img/placeholder.jpg") ?>');
                $('#productId').val('');
                $('#productModal').addClass('active');
            });

            // Close Product Modal
            $('#closeModal, #cancelBtn').on('click', function () {
                $('#productModal').removeClass('active');
            });

            // Image Preview
            $('#productImage').on('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        $('#imagePreview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Edit Product
            $('.btn-edit').on('click', function () {
                const productId = $(this).data('product-id');
                $('#modalTitle').text('Edit Product');
                $('#productId').val(productId);

                // Populate form with product data (would come from API/database in real app)
                if (productId === 1) {
                    $('#productName').val('Premium Dry Dog Food');
                    $('#productCategory').val('dog-food');
                    $('#productPrice').val('22.99');
                    $('#productStock').val('45');
                    $('#productDescription').val('High-quality dry dog food with balanced nutrition for adult dogs. Contains chicken, rice, and essential vitamins.');
                    $('#productStatus').val('available');
                    $('#imagePreview').attr('src', '<?= asset("img/contents/products/pdogfood.jpg") ?>');
                } else if (productId === 2) {
                    $('#productName').val('Joint Health Supplements');
                    $('#productCategory').val('supplements');
                    $('#productPrice').val('18.50');
                    $('#productStock').val('32');
                    $('#productDescription').val('Support your pet\'s joint health with these premium supplements. Ideal for senior pets or those with mobility issues.');
                    $('#productStatus').val('available');
                    $('#imagePreview').attr('src', '<?= asset("img/contents/products/vitamins.jpg") ?>');
                } else if (productId === 3) {
                    $('#productName').val('Puppy Growth Formula');
                    $('#productCategory').val('dog-food');
                    $('#productPrice').val('28.50');
                    $('#productStock').val('0');
                    $('#productDescription').val('Specially formulated for growing puppies with essential nutrients to support healthy development and strong immune systems.');
                    $('#productStatus').val('unavailable');
                    $('#imagePreview').attr('src', '<?= asset("img/contents/products/petfood.jpg") ?>');
                }

                $('#productModal').addClass('active');
            });

            // Toggle Product Status
            $('.btn-toggle').on('click', function () {
                const productId = $(this).data('product-id');
                const currentStatus = $(this).closest('tr').find('.product-status');

                if (currentStatus.hasClass('status-available')) {
                    currentStatus.removeClass('status-available').addClass('status-unavailable').text('Unavailable');
                    $(this).find('i').removeClass('toggle off').addClass('toggle on');
                } else {
                    currentStatus.removeClass('status-unavailable').addClass('status-available').text('Available');
                    $(this).find('i').removeClass('toggle on').addClass('toggle off');
                }
            });

            // Delete Product
            $('.btn-delete').on('click', function () {
                const productId = $(this).data('product-id');
                if (confirm('Are you sure you want to delete this product?')) {
                    $(this).closest('tr').remove();
                }
            });

            // Save Product
            $('#saveProductBtn').on('click', function () {
                // Validate form
                if (!$('#productForm')[0].checkValidity()) {
                    $('#productForm')[0].reportValidity();
                    return;
                }

                // In a real application, you would submit form data to server
                alert('Product saved successfully!');
                $('#productModal').removeClass('active');
            });

            // Search Products
            $('#searchProducts').on('keyup', function () {
                const value = $(this).val().toLowerCase();
                $('table tbody tr').filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Filter by Category
            $('#categoryFilter').on('change', function () {
                const value = $(this).val().toLowerCase();
                if (value === '') {
                    $('table tbody tr').show();
                } else {
                    $('table tbody tr').filter(function () {
                        return $(this).find('td:nth-child(3)').text().toLowerCase().replace(' ', '-') === value;
                    }).show();
                    $('table tbody tr').filter(function () {
                        return $(this).find('td:nth-child(3)').text().toLowerCase().replace(' ', '-') !== value;
                    }).hide();
                }
            });
        });
    </script> -->
</body>

</html>
});
</script> -->
</body>

</html>
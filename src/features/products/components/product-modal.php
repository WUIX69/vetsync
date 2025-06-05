<style>
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
                    <input type="number" class="form-control" id="productPrice" step="0.01" min="0" required>
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
                        <img id="imagePreview" src="<?= asset('img/placeholder.jpg') ?>" alt="Product Image Preview">
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
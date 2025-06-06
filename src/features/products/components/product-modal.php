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

    /* Product Form Modal */
    .product-form-modal .ui.form .field {
        margin-bottom: 1.2rem;
    }

    .product-form-modal .ui.form label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .product-form-modal .ui.form .field.image-preview {
        margin-top: 1rem;
    }

    .product-form-modal .ui.form .field.image-preview img {
        max-width: 100%;
        border-radius: 0.5rem;
        border: 1px solid #e2e8f0;
    }
</style>
<!-- Product Modal -->
<div class="ui tiny modal product-form-modal" id="productModal">
    <i class="close icon"></i>
    <div class="header">
        <i class="plus circle icon"></i> Add New Product
    </div>
    <div class="content">
        <form class="ui form">
            <div class="field">
                <label>Product Name</label>
                <input type="text" name="name" placeholder="Enter product name">
            </div>
            <div class="field">
                <label>Description</label>
                <textarea name="description" rows="3" placeholder="Enter product description"></textarea>
            </div>
            <div class="two fields">
                <div class="field">
                    <label>Price</label>
                    <div class="ui labeled input">
                        <div class="ui label">$</div>
                        <input type="number" name="price" placeholder="0.00">
                    </div>
                </div>
                <div class="field">
                    <label>Stock</label>
                    <input type="number" name="stock" placeholder="0">
                </div>
            </div>
            <div class="field">
                <label>Status</label>
                <div class="ui selection dropdown">
                    <input type="hidden" name="status">
                    <i class="dropdown icon"></i>
                    <div class="default text">Select Status</div>
                    <div class="menu">
                        <div class="item" data-value="available">
                            <i class="check circle green icon"></i>Available
                        </div>
                        <div class="item" data-value="unavailable">
                            <i class="times circle red icon"></i>Unavailable
                        </div>
                    </div>
                </div>
            </div>
            <div class="field">
                <label>Category</label>
                <div class="ui selection dropdown">
                    <input type="hidden" name="category">
                    <i class="dropdown icon"></i>
                    <div class="default text">Select Category</div>
                    <div class="menu">
                        <div class="item" data-value="dogfood">
                            <i class="food icon"></i>Dog Food
                        </div>
                        <div class="item" data-value="supplements">
                            <i class="medkit icon"></i>Supplements
                        </div>
                        <div class="item" data-value="accessories">
                            <i class="paw icon"></i>Accessories
                        </div>
                        <div class="item" data-value="grooming">
                            <i class="shower icon"></i>Grooming
                        </div>
                    </div>
                </div>
            </div>
            <div class="field">
                <label>Tags</label>
                <div class="ui fluid multiple search selection dropdown" id="tagsDropdown">
                    <input type="hidden" name="tags">
                    <i class="dropdown icon"></i>
                    <div class="default text">Select Tags</div>
                    <div class="menu">
                        <div class="item" data-value="organic">Organic</div>
                        <div class="item" data-value="grainfree">Grain-Free</div>
                        <div class="item" data-value="puppy">Puppy</div>
                        <div class="item" data-value="senior">Senior</div>
                        <div class="item" data-value="hypoallergenic">Hypoallergenic</div>
                        <div class="item" data-value="bestseller">Bestseller</div>
                        <div class="item" data-value="new">New Arrival</div>
                        <div class="item" data-value="limited">Limited Edition</div>
                    </div>
                </div>
            </div>
            <div class="field">
                <label>Specs</label>
                <div class="ui fluid multiple search selection dropdown" id="specsDropdown">
                    <input type="hidden" name="specs">
                    <i class="dropdown icon"></i>
                    <div class="default text">Select Specs</div>
                    <div class="menu">
                        <div class="item" data-value="smallbreed">Small Breed</div>
                        <div class="item" data-value="largebreed">Large Breed</div>
                        <div class="item" data-value="chicken">Chicken Flavor</div>
                        <div class="item" data-value="beef">Beef Flavor</div>
                        <div class="item" data-value="1kg">1kg Pack</div>
                        <div class="item" data-value="5kg">5kg Pack</div>
                        <div class="item" data-value="softchews">Soft Chews</div>
                        <div class="item" data-value="liquid">Liquid</div>
                        <div class="item" data-value="tablet">Tablet</div>
                    </div>
                </div>
            </div>
            <div class="field">
                <label>Upload Image</label>
                <input type="file" name="image">
            </div>
            <div class="field image-preview">
                <img id="imagePreview" src="<?= asset('img/products/placeholder.jpg') ?>" alt="Product Image Preview">
            </div>
            <div class="actions">
                <div class="ui black deny button">
                    Cancel
                </div>
                <div class="ui positive right labeled icon button">
                    Save
                    <i class="checkmark icon"></i>
                </div>
            </div>
        </form>
    </div>
</div>
<style>
    /* Service Form Modal */
    .service-form-modal .ui.form .field {
        margin-bottom: 1.2rem;
    }

    .service-form-modal .ui.form label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .service-form-modal .ui.form .field.image-preview {
        margin-top: 1rem;
    }

    .service-form-modal .ui.form .field.image-preview img {
        max-width: 100%;
        border-radius: 0.5rem;
        border: 1px solid #e2e8f0;
    }
</style>
<div class="ui tiny modal service-form-modal">
    <i class="close icon"></i>
    <div class="header">
        <i class="plus circle icon"></i> Add New Service
    </div>
    <div class="content">
        <form class="ui form">
            <div class="field">
                <label>Service Name</label>
                <input type="text" name="name" placeholder="Enter service name">
            </div>
            <div class="field">
                <label>Description</label>
                <textarea name="description" rows="3" placeholder="Enter service description"></textarea>
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
                    <label>Duration (minutes)</label>
                    <input type="number" name="duration" placeholder="30">
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
                        <div class="item" data-value="examination">
                            <i class="stethoscope icon"></i>Examination
                        </div>
                        <div class="item" data-value="treatment">
                            <i class="medkit icon"></i>Treatment
                        </div>
                        <div class="item" data-value="surgery">
                            <i class="cut icon"></i>Surgery
                        </div>
                        <div class="item" data-value="grooming">
                            <i class="shower icon"></i>Grooming
                        </div>
                    </div>
                </div>
            </div>
            <div class="field">
                <label>Upload Image</label>
                <input type="file" name="image">
            </div>
            <div class="field image-preview">
                <img id="imagePreview" src="<?= asset('img/services/placeholder.jpg') ?>" alt="Service Image Preview">
            </div>
        </form>
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
</div>
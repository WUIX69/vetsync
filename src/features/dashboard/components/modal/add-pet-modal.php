<style>
    /* Pet Form Modal */
    .pet-form-modal .ui.form .field {
        margin-bottom: 1.2rem;
    }

    .pet-form-modal .ui.form label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .pet-form-modal .ui.form .field.image-preview {
        margin-top: 1rem;
    }

    .pet-form-modal .ui.form .field.image-preview img {
        max-width: 100%;
        border-radius: 0.5rem;
        border: 1px solid #e2e8f0;
    }
</style>
<!-- Add Pet Modal -->
<div class="ui tiny modal pet-form-modal" id="addPetModal">
    <i class="close icon"></i>
    <div class="header">
        <i class="paw icon"></i> Add New Pet
    </div>
    <div class="content">
        <form class="ui form" id="addPetForm">
            <input type="hidden" name="user_uuid" id="user_uuid" value="">
            <input type="hidden" name="uuid">
            <div class="field">
                <label>Pet Name</label>
                <input type="text" name="name" placeholder="Enter pet name">
            </div>

            <div class="field">
                <label>Date Of Birth</label>
                <input type="date" name="dob">
            </div>

            <div class="two fields">
                <div class="field">
                    <label>Species</label>
                    <input type="text" name="species" placeholder="e.g. Dog/Cat">
                </div>
                <div class="field">
                    <label>Breed</label>
                    <input type="text" name="breed" placeholder="Enter breed">
                </div>
            </div>

            <div class="field">
                <label>Upload Pet Image</label>
                <input type="file" name="file" class="filepond pet-pond">
            </div>

            <div class="actions" style="margin-top: 18px;">
                <button class="ui black deny clear button" type="reset">
                    Cancel
                </button>
                <button class="ui positive right labeled icon submit button" type="submit">
                    Save
                    <i class="checkmark icon"></i>
                </button>
            </div>
        </form>
    </div>
</div>
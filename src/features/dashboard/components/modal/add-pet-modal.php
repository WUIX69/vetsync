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

            <div class="two fields">
                <div class="field">
                    <label>Species</label>
                    <select class="ui dropdown" name="species" id="petSpeciesDropdown">
                        <option value="">Select Species</option>
                        <option value="Dog">🐕 Dog</option>
                        <option value="Cat">🐱 Cat</option>
                        <!-- <option value="Bird">🦜 Bird</option>
                        <option value="Rabbit">🐰 Rabbit</option>
                        <option value="Other">🐾 Other</option> -->
                    </select>
                </div>
                <div class="field">
                    <label>Breed</label>
                    <select class="ui dropdown" name="breed" id="petBreedDropdown">
                        <option value="">Select species first</option>
                    </select>
                </div>
            </div>

            <!-- Custom Breed Input (hidden by default) -->
            <div class="field" id="customBreedField" style="display: none;">
                <label>Custom Breed <span style="color: #666; font-weight: normal;">(Please specify)</span></label>
                <input type="text" id="customBreedInput" placeholder="Enter custom breed name">
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
<!-- Add Pet Modal -->
<div class="ui tiny modal add-pet-modal" id="addPetModal">
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
                <input type="text" name="name" placeholder="Enter pet name" required>
            </div>

            <div class="field">
                <label>Date Of Birth</label>
                <input type="date" name="dob" required>
            </div>

            <div class="field">
                <label>Species</label>
                <input type="text" name="species" placeholder="e.g. Dog/Cat" required>
            </div>
            <div class="field">
                <label>Breed</label>
                <input type="text" name="breed" placeholder="Enter breed" required>
            </div>



            <div class="field">
                <label>Avatar</label>
                <input type="file" name="files[]" id="pet_files" multiple>
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
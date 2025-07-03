// My Pets CRUD (database-backed) using pets.php API

const myPetsSection = $("section.my-pets");
const petsList = myPetsSection.find(".pet-items");

// Modal for add/edit pet (use the correct id: addPetModal)
const petModal = $("#addPetModal");
const petModalForm = petModal.find("form");

// Helper for AJAX to pets API
function petsAjax(options) {
    let url = apiUrl("dashboard") + "pets.php";
    if (options.urlParams) {
        url += "?" + $.param(options.urlParams);
    }
    const defaultOptions = {
        url: url,
        dataType: "json",
        timeout: 5000,
        error: ajaxErrorHandler,
    };
    const finalOptions = $.extend(true, {}, defaultOptions, options);
    return $.ajax(finalOptions);
}

// Fetch and render all pets
function getAllPets() {
    petsList.empty();

    petsAjax({
        method: "GET",
        data: { action: "all" },
        success: function (response) {
            if (!response.success) {
                alert(response.message);
                return false;
            }
            const pets = response.data || [];
            let petsHTML = "";

            pets.forEach((pet) => {
                petsHTML += `
                    <li class="item view-pet" data-pet-uuid="${pet.uuid}">
                        <img class="avatar-img" src="${
                            pet.image || "https://placehold.co/100x100?text=Pet"
                        }" alt="${pet.name}">
                        <div class="avatar-name">${pet.name}</div>
                    </li>
                `;
            });

            // Add the "Add Pet" button at the end, using the correct modal id
            petsHTML += `
                <button class="ui circular icon button add-pet-btn" data-open-modal="#addPetModal">
                    <i class="plus icon"></i> Add Pet
                </button>
            `;

            petsList.append(petsHTML);
        },
    });
}

// Fetch and show a single pet in the modal (for view/edit)
function getSinglePet(petUuid = null) {
    if (!petUuid) return false;

    petsAjax({
        method: "GET",
        data: {
            action: "single",
            uuid: petUuid,
        },
        success: function (response) {
            if (!response.success) {
                alert(response.message);
                return false;
            }
            const pet = response.data || {};
            // Populate the form fields
            $.each(pet, function (key, value) {
                petModalForm.find('[name="' + key + '"]').val(value);
            });
            petModal.modal("show");
        },
    });
}

// Delete a pet
function deletePet(petUuid = null) {
    if (!petUuid) return false;
    if (!confirm("Are you sure you want to delete this pet?")) return false;

    petsAjax({
        urlParams: { uuid: petUuid },
        method: "DELETE",
        success: function (response) {
            alert(response.message);
            if (!response.success) return false;
            getAllPets();
            // Also hide the flyout if open
            $("#petFlyout").flyout("hide");
        },
    });
}

// Show pet details in flyout (read-only view)
function showPetFlyout(petUuid = null) {
    if (!petUuid) return false;
    // You may want to fetch the pet again, or use cached data
    petsAjax({
        method: "GET",
        data: {
            action: "single",
            uuid: petUuid,
        },
        success: function (response) {
            if (!response.success) {
                alert(response.message);
                return false;
            }
            const pet = response.data || {};
            const petFlyout = $("#petFlyout");
            // Fill in the flyout fields
            $.each(pet, function (name, value) {
                if (name === "image") {
                    petFlyout
                        .find(`.pet-profile-img`)
                        .attr(
                            "src",
                            value || "https://placehold.co/100x100?text=Pet"
                        );
                } else {
                    petFlyout.find(`.pet-profile-${name}`).text(value);
                }
            });
            // Store the uuid on the flyout for later use (update/delete)
            petFlyout.data("pet-uuid", pet.uuid);
            petFlyout.flyout("show");
        },
    });
}

$(function () {
    getAllPets();

    // View pet details on click
    $("body").on("click", ".view-pet", function (e) {
        const petUuid = $(this).data("pet-uuid");
        showPetFlyout(petUuid);
    });

    // Open modal for adding a new pet
    $("body").on("click", ".add-pet-btn", function () {
        // Only reset if form exists
        if (
            petModalForm.length &&
            typeof petModalForm[0].reset === "function"
        ) {
            petModalForm[0].reset();
        } else {
            // fallback: clear all input fields manually
            petModalForm.find("input, textarea, select").each(function () {
                if ($(this).is(":checkbox") || $(this).is(":radio")) {
                    $(this).prop("checked", false);
                } else {
                    $(this).val("");
                }
            });
        }
        petModalForm.find('[name="uuid"]').val(""); // Clear uuid for new pet
        petModal.modal("show");
    });

    // Handle delete (if you add a delete button in the UI)
    $("body").on("click", ".delete-pet-btn", function (e) {
        e.stopPropagation();
        const petUuid = $(this).closest(".item").data("pet-uuid");
        deletePet(petUuid);
    });

    // --- Flyout Update and Delete Handlers ---

    // Update button in flyout: open modal with pet data for editing
    $("body").on("click", "#updatePetBtn", function () {
        const petUuid = $("#petFlyout").data("pet-uuid");
        if (!petUuid) return;
        // Fetch pet data and open modal for editing
        petsAjax({
            method: "GET",
            data: {
                action: "single",
                uuid: petUuid,
            },
            success: function (response) {
                if (!response.success) {
                    alert(response.message);
                    return false;
                }
                const pet = response.data || {};
                // Populate the form fields
                $.each(pet, function (key, value) {
                    petModalForm.find('[name="' + key + '"]').val(value);
                });
                petModalForm.find('[name="uuid"]').val(petUuid);
                petModal.modal("show");
                // Hide the flyout
                $("#petFlyout").flyout("hide");
            },
        });
    });

    // Delete button in flyout: delete pet
    $("body").on("click", "#deletePetBtn", function () {
        const petUuid = $("#petFlyout").data("pet-uuid");
        if (!petUuid) return;
        deletePet(petUuid);
    });

    // Validate and submit pet form (add/edit)
    petModalForm.form({
        fields: {
            name: {
                identifier: "name",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter a pet name",
                    },
                ],
            },
            pet_dob: {
                identifier: "dob",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter date of birth",
                    },
                ],
            },
            pet_species: {
                identifier: "species",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter species",
                    },
                ],
            },
            pet_breed: {
                identifier: "breed",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter breed",
                    },
                ],
            },
        },
        inline: true,
        on: "blur",
        onSuccess: function (event, fields) {
            event.preventDefault();
            const $submitBtn = $(this).find("button[type=submit]");
            const isUpdate = !!fields.uuid;

            // Prepare form data for file upload
            const formData = new FormData(petModalForm[0]);
            formData.append("action", isUpdate ? "update" : "store");

            petsAjax({
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $submitBtn.addClass("loading");
                },
                success: function (response) {
                    alert(response.message);
                    if (response.success) {
                        getAllPets();
                        petModal.modal("hide");
                    }
                },
                complete: function () {
                    $submitBtn.removeClass("loading");
                },
            });
        },
    });
});

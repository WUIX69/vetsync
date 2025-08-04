// My Pets CRUD (database-backed) using pets.php API

const myPetsSection = $("section.my-pets");
const petsList = myPetsSection.find(".pet-items");

// Modal for add/edit pet (use the correct id: addPetModal)
const petModal = $("#addPetModal");
const petModalForm = petModal.find("form");

// Filepond Flags (Required)
let isModalHide = false;
let isPondRender = false;

// Pet Image FilePond
const petImagePond = FilePond.create(document.querySelector(".pet-pond"), {
    maxFiles: 2,
    maxFileSize: "2MB",
    allowMultiple: true,
    allowFileTypes: ["image/*"],
    labelIdle: `Drag & Drop your pet image or <span class="filepond--label-action">Browse</span>`,
    imagePreviewHeight: 170,
    imageCropAspectRatio: "1:1",
    imageResizeTargetWidth: 200,
    imageResizeTargetHeight: 200,
    onprocessfile: function (error, file) {
        // console.log("On Process Files:", file);
    },
    onaddfile: function (error, file) {
        // console.log("On Add File:", file);
    },
    onremovefile: function (error, file) {
        // console.log("On Remove File:", file);

        // Only handle "local" files (already on server) and only if not modal hide
        if (file.origin === 3 && !isModalHide) {
            console.log("is local delete");
            $.ajax({
                url: apiUrl("shared") + "filepond.php",
                headers: {
                    "X-Reference-Model": "pets",
                },
                method: "DELETE",
                data: file.serverId,
                processData: false,
                contentType: false,
                error: ajaxErrorHandler,
            });
        }
    },
    onupdatefiles: function (files) {
        // Optional: Handle file updates
    },
    server: {
        url: apiUrl() + "filepond.php",
        headers: {
            "X-Reference-Model": "pets",
        },
        timeout: 7000,
        withCredentials: false,
        process: {
            url: "",
        },
        revert: {
            url: "",
        },
        load: {
            url: "?folder=",
        },
    },
});

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
        data: {
            action: "all",
        },
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
                if (key === "files") {
                    // Prevent FilePond from deleting files when modal is hidden and !empty files
                    if (value.length > 0) isPondRender = true;

                    // Add files to FilePond
                    value.forEach(function (file) {
                        petImagePond
                            .addFile(file.folder, {
                                type: "local",
                                options: {
                                    file: {
                                        name: file.filename,
                                    },
                                    metadata: {
                                        serverId: file.folder,
                                    },
                                },
                            })
                            .then(function (fileItem) {
                                // console.log("Added fileItem:", fileItem);
                            });
                    });
                } else {
                    petModalForm.find('[name="' + key + '"]').val(value);
                }
            });

            petModal.modal("show");
        },
    });
}

// Delete a pet
function deletePet(petUuid = null) {
    if (!petUuid) return false;
    if (
        !confirm(
            "Are you sure you want to delete this pet? This action cannot be undone."
        )
    )
        return false;

    petsAjax({
        urlParams: { uuid: petUuid },
        method: "DELETE",
        success: function (response) {
            if (response.success) {
                alert("✅ " + response.message);
                getAllPets();
                $("#petFlyout").flyout("hide");
            } else {
                // Show more informative error message
                alert(
                    "⚠️ " +
                        response.message +
                        "\n\nTip: Cancel or complete the pet's appointments first, then try deleting again."
                );
            }
        },
        error: function (xhr, status, error) {
            alert("❌ Error deleting pet: " + error);
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

            console.log("Pet data:", pet); // Debug log

            // Fill in the flyout fields properly
            $.each(pet, function (name, value) {
                if (name === "image") {
                    // Use the image field directly - it's already formatted by the media() function
                    petFlyout
                        .find(`.pet-profile-img`)
                        .attr(
                            "src",
                            value || "https://placehold.co/110x110?text=Pet"
                        );
                } else if (name !== "files") {
                    // Set other pet profile fields (skip files array)
                    const fieldElement = petFlyout.find(`.pet-profile-${name}`);
                    if (fieldElement.length) {
                        fieldElement.text(value || "Not specified");
                    }
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
        // Reset form and FilePond
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
        getSinglePet(petUuid);
        // Hide the flyout
        $("#petFlyout").flyout("hide");
    });

    // Delete button in flyout: delete pet
    $("body").on("click", "#deletePetBtn", function () {
        const petUuid = $("#petFlyout").data("pet-uuid");
        if (!petUuid) return;
        deletePet(petUuid);
    });

    // Remove files from FilePond when modal is hidden
    petModal.modal("setting", "onHide", function () {
        isModalHide = true;
        if (!isPondRender) {
            // Delete files from storage and FilePond
            petImagePond.removeFiles({ revert: true });
        } else {
            // Delete files from FilePond UI
            petImagePond.removeFiles();
            // Reset the flag for next time
            isPondRender = false;
        }
    });

    // Validate and submit pet form (add/edit) - EXACT SAME PATTERN AS SERVICES
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
            dob: {
                identifier: "dob",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter date of birth",
                    },
                ],
            },
            species: {
                identifier: "species",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter species",
                    },
                ],
            },
            breed: {
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
        on: "submit",
        onSuccess: function (event, fields) {
            event.preventDefault();
            const $submitBtn = $(this).find("button[type=submit]");
            const formData = new FormData(petModalForm[0]);

            let action = "store";
            if (formData.get("uuid")) action = "update";
            formData.append("action", action);

            // Collect all FilePond serverIds (folder names)
            let files = petImagePond.getFiles().map((f) => f.serverId);
            formData.set("files", files.join(","));
            formData.delete("file");

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
                        getAllPets(); // Refresh the pets data
                        isPondRender = true; // Set the flag BEFORE hiding the modal
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

// My Pets CRUD (database-backed) using pets.php API

const myPetsSection = $("section.my-pets");
const petsList = myPetsSection.find(".pet-items");

// Modal for add/edit pet (use the correct id: addPetModal)
const petModal = $("#addPetModal");
const petModalForm = petModal.find("form");

// Filepond Flags (Required)
let isModalHide = false;
let isPondRender = false;

// Pet Image FilePond - FIXED VERSION
const petImagePond = FilePond.create(document.querySelector(".pet-pond"), {
    maxFiles: 1, // CHANGED: Only allow 1 file
    maxFileSize: "2MB",
    allowMultiple: false, // CHANGED: No multiple files
    allowFileTypes: ["image/*"],
    labelIdle: `Drag & Drop your pet image or <span class="filepond--label-action">Browse</span>`,
    imagePreviewHeight: 170,
    imageCropAspectRatio: "1:1",
    imageResizeTargetWidth: 200,
    imageResizeTargetHeight: 200,
    allowReplace: true, // ADDED: Allow replacing files
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

// Common breeds for different species (Only Dog and Cat for now)
const breedOptions = {
    Dog: [
        { value: "Golden Retriever", text: "Golden Retriever" },
        { value: "Labrador Retriever", text: "Labrador Retriever" },
        { value: "German Shepherd", text: "German Shepherd" },
        { value: "Shih Tzu", text: "Shih Tzu" },
        { value: "Aspin", text: "Aspin (Asong Pinoy)" },
        { value: "Other", text: "Other (Specify below)" },
    ],
    Cat: [
        { value: "Persian", text: "Persian" },
        { value: "Siamese", text: "Siamese" },
        { value: "Maine Coon", text: "Maine Coon" },
        { value: "British Shorthair", text: "British Shorthair" },
        { value: "Puspin", text: "Puspin (Pusang Pinoy)" },
        { value: "Other", text: "Other (Specify below)" },
    ],
    // Bird and Rabbit removed - only Dog and Cat available for now
};

// Handle species dropdown change
$(document).on("change", "#petSpeciesDropdown", function () {
    const species = $(this).val();
    const $breedDropdown = $("#petBreedDropdown");
    const $customBreedField = $("#customBreedField");

    // Reset custom fields
    $customBreedField.hide();
    $("#customBreedInput").val("");

    if (species && breedOptions[species]) {
        // Enable and populate breed dropdown
        $breedDropdown.prop("disabled", false);
        $breedDropdown.empty();
        $breedDropdown.append('<option value="">Select Breed</option>');

        breedOptions[species].forEach(function (breed) {
            $breedDropdown.append(
                `<option value="${breed.value}">${breed.text}</option>`
            );
        });

        $breedDropdown.dropdown("refresh");
        $breedDropdown.dropdown("clear");
    } else {
        // No species selected
        $breedDropdown.dropdown("clear");
        $breedDropdown.empty();
        $breedDropdown.append('<option value="">Select species first</option>');
        $breedDropdown.dropdown("refresh");
        $breedDropdown.prop("disabled", true);
    }
});

// Handle breed dropdown change
$(document).on("change", "#petBreedDropdown", function () {
    const breed = $(this).val();
    const $customBreedField = $("#customBreedField");

    if (breed === "Other") {
        $customBreedField.show();
    } else {
        $customBreedField.hide();
        $("#customBreedInput").val("");
    }
});

// Original Pet click handler - Use the existing showPetFlyout function
$(document)
    .off("click", ".view-pet")
    .on("click", ".view-pet", function (e) {
        e.preventDefault();
        console.log("Pet clicked!");

        const petUuid = $(this).data("pet-uuid");
        console.log("Pet UUID:", petUuid);

        if (!petUuid) {
            console.error("No pet UUID found");
            return;
        }

        // Use the original showPetFlyout function
        showPetFlyout(petUuid);
    });

// Fix the service tab click handler to use the correct data key

// Service tab click handler - load service history
$(document).on("click", "#service-tab", function (e) {
    console.log("Service tab clicked!");

    const petFlyout = $("#petFlyout");
    const petUuid = petFlyout.data("pet-uuid"); // Changed from "current-pet-uuid" to "pet-uuid"

    console.log("Pet UUID for service history:", petUuid);

    if (petUuid) {
        loadPetServiceHistory(petUuid);
    } else {
        console.error("No pet UUID found for service history");
    }
});

// Close flyout handler
$(document).on("click", "#petFlyout .close.icon", function (e) {
    e.preventDefault();
    console.log("Close button clicked");
    $("#petFlyout").flyout("hide");
});

// Initialize pets when DOM is ready
$(function () {
    console.log("DOM ready - Loading pets...");

    // Ensure the pet-items container exists
    if (petsList.length === 0) {
        console.error(
            "Pet items container not found! Make sure .pet-items exists in the HTML."
        );
        return;
    }

    // Load active pets immediately
    getAllPets("active");

    // Set the active tab
    currentTab = "active";
    $(".pet-tab").removeClass("active");
    $(".pet-tab[data-tab='active']").addClass("active");
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

// Global state for current tab
let currentTab = "active";

// Fetch and render all pets
function getAllPets(archiveStatus = "active") {
    petsList.empty();
    currentTab = archiveStatus;

    // Show loading state
    petsList.append(`
        <div class="ui active centered inline loader" style="margin: 2rem 0;"></div>
        <div style="text-align: center; color: #999; margin-top: 1rem;">
            Loading pets...
        </div>
    `);

    petsAjax({
        method: "GET",
        data: {
            action: "all",
            archive_status: archiveStatus,
        },
        success: function (response) {
            petsList.empty(); // Clear loading message

            if (!response.success) {
                petsList.append(`
                    <div class="ui negative message">
                        <i class="times circle icon"></i>
                        ${response.message || "Failed to load pets"}
                    </div>
                `);
                return false;
            }

            const pets = response.data || [];

            // Handle empty states
            if (pets.length === 0) {
                if (archiveStatus === "active") {
                    petsList.append(`
                        <div class="ui placeholder segment" style="text-align: center; padding: 2rem;">
                            <div class="ui icon header">
                                <i class="paw icon"></i>
                                No pets found
                            </div>
                            <p>Add your first pet to get started!</p>
                            <button class="ui primary button add-pet-btn" data-open-modal="#addPetModal">
                                <i class="plus icon"></i> Add Pet
                            </button>
                        </div>
                    `);
                } else if (archiveStatus === "archived") {
                    petsList.append(`
                        <div class="ui placeholder segment" style="text-align: center; padding: 2rem;">
                            <div class="ui icon header">
                                <i class="archive icon"></i>
                                No archived pets
                            </div>
                            <p>Your archived pets will appear here.</p>
                            <small style="color: #999;">Pets are automatically archived after one year of inactivity.</small>
                        </div>
                    `);
                }
                return;
            }

            let petsHTML = "";

            pets.forEach((pet) => {
                const isArchived = pet.archive_status !== "active";
                const archiveBadge = isArchived
                    ? `<div class="archive-badge">${pet.archive_status}</div>`
                    : "";
                const archivedClass = isArchived ? "archived" : "";

                petsHTML += `
                    <li class="item view-pet ${archivedClass}" data-pet-uuid="${
                    pet.uuid
                }">
                        ${archiveBadge}
                        <img class="avatar-img" src="${
                            pet.image || "https://placehold.co/100x100?text=Pet"
                        }" alt="${pet.name}">
                        <div class="avatar-name">${pet.name}</div>
                    </li>
                `;
            });

            // Add the "Add Pet" button for active pets only
            if (archiveStatus === "active") {
                petsHTML += `
                    <li>
                        <button class="ui circular icon button add-pet-btn" data-open-modal="#addPetModal" style="margin-top: 10px;">
                        <i class="plus icon"></i> Add Pet
                    </button>
                    </li>
                `;
            }

            petsList.append(petsHTML);
        },
        error: function (xhr, status, error) {
            petsList.empty();
            petsList.append(`
                <div class="ui negative message">
                    <i class="warning sign icon"></i>
                    Failed to load pets. Please refresh the page.
                </div>
            `);
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

// Archive a pet
function archivePet(petUuid, status = "inactive") {
    if (!petUuid) return false;

    const statusText = status === "deceased" ? "mark as deceased" : "archive";
    if (!confirm(`Are you sure you want to ${statusText} this pet?`))
        return false;

    petsAjax({
        urlParams: { uuid: petUuid, status: status },
        method: "GET",
        data: {
            action: "archive",
        },
        success: function (response) {
            if (response.success) {
                alert("✅ " + response.message);
                getAllPets(currentTab);
                $("#petFlyout").flyout("hide");
            } else {
                alert("⚠️ " + response.message);
            }
        },
        error: function (xhr, status, error) {
            alert("❌ Error archiving pet: " + error);
        },
    });
}

// Unarchive a pet
function unarchivePet(petUuid) {
    if (!petUuid) return false;
    if (!confirm("Are you sure you want to restore this pet?")) return false;

    petsAjax({
        urlParams: { uuid: petUuid },
        method: "GET",
        data: {
            action: "unarchive",
        },
        success: function (response) {
            if (response.success) {
                alert("✅ " + response.message);
                getAllPets(currentTab);
                $("#petFlyout").flyout("hide");
            } else {
                alert("⚠️ " + response.message);
            }
        },
        error: function (xhr, status, error) {
            alert("❌ Error restoring pet: " + error);
        },
    });
}

// Get inactive pets (pets without appointments for 1 year)
function getInactivePets() {
    petsAjax({
        method: "GET",
        data: {
            action: "inactive",
        },
        success: function (response) {
            if (response.success) {
                const inactivePets = response.data || [];
                if (inactivePets.length > 0) {
                    let message = `Found ${inactivePets.length} pets without appointments in the last year:\n\n`;
                    inactivePets.forEach((pet) => {
                        message += `• ${pet.name} (${pet.species})\n`;
                    });
                    message += "\nWould you like to archive these pets?";

                    if (confirm(message)) {
                        // Archive all inactive pets
                        inactivePets.forEach((pet) => {
                            archivePet(pet.uuid, "inactive");
                        });
                    }
                } else {
                    alert("No inactive pets found.");
                }
            }
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

            // Show/hide archive buttons based on pet status
            if (pet.archive_status === "active") {
                // Hide the manual "Archive Pet" button since archiving is automatic
                // Keep "Mark as Deceased" button for manual use
                $("#archivePetBtn").hide();
                $("#deceasedPetBtn").show();
                $("#unarchivePetBtn").hide();
            } else {
                // For archived pets, only show restore button
                $("#archivePetBtn, #deceasedPetBtn").hide();
                $("#unarchivePetBtn").show();
            }

            petFlyout.flyout("show");
        },
    });
}

// Fetch and render all archived pets (both inactive and deceased)
function getAllArchivedPets() {
    petsList.empty();
    currentTab = "archived";

    // Get both inactive and deceased pets
    const promises = [
        petsAjax({
            method: "GET",
            data: {
                action: "all",
                archive_status: "inactive",
            },
        }),
        petsAjax({
            method: "GET",
            data: {
                action: "all",
                archive_status: "deceased",
            },
        }),
    ];

    Promise.all(promises)
        .then((responses) => {
            let allArchivedPets = [];

            responses.forEach((response) => {
                if (response.success && response.data) {
                    allArchivedPets = allArchivedPets.concat(response.data);
                }
            });

            if (allArchivedPets.length === 0) {
                petsList.append(
                    '<li style="text-align: center; padding: 20px; color: #666;">No archived pets found.</li>'
                );
                return;
            }

            let petsHTML = "";
            allArchivedPets.forEach((pet) => {
                const archiveBadge = `<div class="archive-badge">${pet.archive_status}</div>`;

                petsHTML += `
                <li class="item view-pet archived" data-pet-uuid="${pet.uuid}">
                    ${archiveBadge}
                    <img class="avatar-img" src="${
                        pet.image || "https://placehold.co/100x100?text=Pet"
                    }" alt="${pet.name}">
                    <div class="avatar-name">${pet.name}</div>
                </li>
            `;
            });

            petsList.append(petsHTML);
        })
        .catch((error) => {
            console.error("Error fetching archived pets:", error);
            alert("Error loading archived pets");
        });
}

// Update the Service tab click handler with better debugging
function loadPetServiceHistory(petUuid) {
    console.log("Loading service history for pet:", petUuid);
    const container = $(".service-history-container");

    if (container.length === 0) {
        console.error("Service history container not found");
        return;
    }

    // Show clean loading state
    container.html(`
        <div style="text-align: center; padding: 3rem;">
            <div class="ui active inline loader"></div>
            <div style="color: #666; margin-top: 1rem; font-size: 1rem;">Loading service history...</div>
        </div>
    `);

    // Make API call
    $.ajax({
        url: apiUrl("dashboard") + "pet-service-history.php",
        method: "GET",
        data: { pet_uuid: petUuid },
        dataType: "json",
        success: function (response) {
            console.log("Service history response:", response);
            console.log("Services found:", response.data.length);

            if (response.success && response.data.length > 0) {
                // Create clean service history HTML
                let historyHTML = `
                    <div style="padding: 1rem 0;">
                        <div style="margin-bottom: 1.5rem;">
                            <h4 style="color: #2c3e50; margin: 0; font-size: 1.1rem; font-weight: 600;">
                                <i class="heartbeat icon" style="color: #21ba45; margin-right: 0.5rem;"></i>
                                Completed Services
                            </h4>
                        </div>
                `;

                response.data.forEach((service, index) => {
                    const serviceDate = new Date(
                        service.appointment_date
                    ).toLocaleDateString("en-US", {
                        year: "numeric",
                        month: "short",
                        day: "numeric",
                    });

                    historyHTML += `
                        <div style="background: white; border: 1px solid #e1e8ed; border-radius: 8px; margin-bottom: 1rem; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                            <div style="padding: 1.2rem 1.5rem;">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.8rem;">
                                    <div style="flex: 1;">
                                        <h5 style="margin: 0; color: #2c3e50; font-size: 1.1rem; font-weight: 600;">
                                            ${service.service_name}
                                        </h5>
                                        <div style="color: #7f8c8d; font-size: 0.9rem; margin-top: 0.3rem;">
                                            ${service.category_name}
                                        </div>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 0.8rem;">
                                        <div style="font-size: 0.9rem; color: #666;">
                                            ${serviceDate}
                                        </div>
                                        <div style="background: #e8f5e8; color: #27ae60; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: 500;">
                                            ✓ Completed
                                        </div>
                                    </div>
                                </div>
                                ${
                                    service.note
                                        ? `
                                    <div style="background: #f8f9fa; padding: 0.8rem; border-radius: 6px; border-left: 3px solid #21ba45; margin-top: 0.8rem;">
                                        <div style="font-size: 0.9rem; color: #555; line-height: 1.4;">
                                            <strong style="color: #21ba45;">Note:</strong> ${service.note}
                                        </div>
                                    </div>
                                `
                                        : ""
                                }
                            </div>
                        </div>
                    `;
                });

                historyHTML += `</div>`;
                container.html(historyHTML);
            } else {
                // No service history found - clean empty state
                container.html(`
                    <div style="text-align: center; padding: 3rem 2rem;">
                        <div style="color: #bdc3c7; margin-bottom: 1.5rem;">
                            <i class="clipboard outline icon" style="font-size: 3rem;"></i>
                        </div>
                        <div style="color: #7f8c8d; font-size: 1.1rem; font-weight: 500; margin-bottom: 0.5rem;">
                            No Service History
                        </div>
                        <div style="color: #95a5a6; font-size: 0.95rem; line-height: 1.5;">
                            Completed services will appear here
                        </div>
                    </div>
                `);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error loading service history:", error);
            container.html(`
                <div style="text-align: center; padding: 3rem 2rem;">
                    <div style="color: #e74c3c; margin-bottom: 1rem;">
                        <i class="exclamation triangle icon" style="font-size: 2.5rem;"></i>
                    </div>
                    <div style="color: #e74c3c; font-size: 1rem; font-weight: 500;">
                        Error loading service history
                    </div>
                </div>
            `);
        },
    });
}

// Helper function to get appropriate icons for categories
function getCategoryIcon(categoryName) {
    const iconMap = {
        vaccination: "syringe",
        surgery: "cut",
        treatment: "first aid",
        checkup: "stethoscope",
        dental: "tooth",
        grooming: "shower",
        emergency: "ambulance",
        consultation: "user md",
    };

    const category = categoryName.toLowerCase();
    return iconMap[category] || "clipboard";
}

// Validate and submit pet form (add/edit) - EXACT SAME PATTERN AS SERVICES
petModalForm.form({
    fields: {
        name: {
            identifier: "name",
            rules: [{ type: "empty", prompt: "Please enter a pet name" }],
        },
        species: {
            identifier: "species",
            rules: [{ type: "empty", prompt: "Please select a species" }],
        },
        breed: {
            identifier: "breed",
            rules: [{ type: "empty", prompt: "Please select a breed" }],
        },
        // DOB field removed - not needed anymore
    },
    inline: true,
    on: "submit",
    onSuccess: function (event, fields) {
        event.preventDefault();
        const $submitBtn = $(this).find("button[type=submit]");
        const formData = new FormData(petModalForm[0]);

        // Handle custom breed
        if (fields.breed === "Other") {
            const customBreed = $("#customBreedInput").val().trim();
            if (!customBreed) {
                alert("Please enter a custom breed");
                return false;
            }
            formData.set("breed", customBreed);
        }

        let action = "store";
        if (formData.get("uuid")) action = "update";
        formData.append("action", action);

        // Collect all FilePond serverIds (folder names)
        let files = petImagePond.getFiles().map((f) => f.serverId);
        formData.set("files", files.join(","));
        formData.delete("file");

        $.ajax({
            url: apiUrl("dashboard") + "pets.php",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            timeout: 5000,
            beforeSend: function () {
                $submitBtn.addClass("loading");
            },
            success: function (response) {
                alert(response.message);
                getAllPets();
                isPondRender = true;
                petModal.modal("hide");
            },
            complete: function () {
                $submitBtn.removeClass("loading");
            },
            error: ajaxErrorHandler,
        });
    },
});

// Tab switching
$("body").on("click", ".pet-tab", function (e) {
    e.preventDefault();
    const tab = $(this).data("tab");

    // Update active tab
    $(".pet-tab").removeClass("active");
    $(this).addClass("active");

    // Load pets for selected tab
    const archiveStatus = tab === "archived" ? "archived" : "active";
    getAllPets(archiveStatus);
});

// Mark as deceased button in flyout
$("body").on("click", "#deceasedPetBtn", function () {
    const petUuid = $("#petFlyout").data("pet-uuid");
    if (!petUuid) return;
    archivePet(petUuid, "deceased");
});

// Unarchive button in flyout
$("body").on("click", "#unarchivePetBtn", function () {
    const petUuid = $("#petFlyout").data("pet-uuid");
    if (!petUuid) return;
    unarchivePet(petUuid);
});

// Add these button click handlers after the other event handlers (around line 115)

// Delete pet button handler
$(document).on("click", "#deletePetBtn", function (e) {
    e.preventDefault();
    console.log("Delete pet button clicked!");

    const petFlyout = $("#petFlyout");
    const petUuid = petFlyout.data("pet-uuid");

    console.log("Pet UUID for deletion:", petUuid);

    if (petUuid) {
        deletePet(petUuid);
    } else {
        console.error("No pet UUID found for deletion");
        alert(
            "Error: Could not find pet information. Please refresh and try again."
        );
    }
});

// Update pet button handler
$(document).on("click", "#updatePetBtn", function (e) {
    e.preventDefault();
    console.log("Update pet button clicked!");

    const petFlyout = $("#petFlyout");
    const petUuid = petFlyout.data("pet-uuid");

    console.log("Pet UUID for update:", petUuid);

    if (petUuid) {
        // Hide the flyout first
        petFlyout.flyout("hide");

        // Then get the pet data and populate the edit modal
        getSinglePet(petUuid);

        // Show the pet modal for editing
        petModal.modal("show");
    } else {
        console.error("No pet UUID found for update");
        alert(
            "Error: Could not find pet information. Please refresh and try again."
        );
    }
});

// Restore pet button handler (for archived pets)
$(document).on("click", "#unarchivePetBtn", function (e) {
    e.preventDefault();
    console.log("Restore pet button clicked!");

    const petFlyout = $("#petFlyout");
    const petUuid = petFlyout.data("pet-uuid");

    console.log("Pet UUID for restore:", petUuid);

    if (petUuid) {
        unarchivePet(petUuid);
    } else {
        console.error("No pet UUID found for restore");
        alert(
            "Error: Could not find pet information. Please refresh and try again."
        );
    }
});

// Initialize pets when page loads
$(document).ready(function () {
    console.log("Initializing My Pets section...");

    // Load active pets on page load
    getAllPets("active");

    // Set active tab
    $(".pet-tab[data-tab='active']").addClass("active");
    $(".pet-tab[data-tab='archived']").removeClass("active");

    console.log("My Pets initialized successfully");
});

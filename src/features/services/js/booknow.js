// src/features/services/js/booknow.js

// Global variable to store disabled dates
let disabledDates = [];
let dailyAppointmentCounts = {};

// Date validation notification function
function showDateNotification(type, title, message) {
    // Create notification element
    const notification = $(`
        <div class="ui ${type} message date-notification" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <div class="header">
                <i class="calendar times icon"></i>
                ${title}
            </div>
            <p>${message}</p>
            <button class="ui mini button" onclick="$(this).closest('.date-notification').fadeOut()">
                <i class="close icon"></i>
                Dismiss
            </button>
        </div>
    `);

    // Add to page and animate
    $("body").append(notification);
    notification.hide().fadeIn(300);

    // Auto-remove after 5 seconds
    setTimeout(() => {
        notification.fadeOut(300, function () {
            $(this).remove();
        });
    }, 5000);
}

// Load availability data
function loadAppointmentAvailability() {
    return $.ajax({
        url: "/src/features/appointments/api/check-availability.php",
        method: "GET",
        dataType: "json",
        success: function (response) {
            if (response.success) {
                disabledDates = response.disabled_dates || [];
                console.log("Disabled dates loaded:", disabledDates);
            }
        },
        error: function (xhr, status, error) {
            console.error("Failed to load availability:", error);
        },
    });
}

// Check if a date is available
function checkDateAvailability(dateString) {
    const $availabilityDiv = $("#dateAvailability");

    if (disabledDates.includes(dateString)) {
        $availabilityDiv
            .removeClass("available")
            .addClass("fully-booked")
            .html(
                '<i class="times circle icon"></i><span><strong>Fully Booked!</strong> This date has reached the maximum 10 appointments. Please select another date.</span>'
            );
        return false;
    } else {
        $availabilityDiv
            .removeClass("fully-booked")
            .addClass("available")
            .html(
                '<i class="check circle icon"></i><span><strong>Date Available!</strong> Slots are still open for this date.</span>'
            );
        return true;
    }
}

// Real-time date validation
function validateDateInput(inputElement) {
    const selectedDate = $(inputElement).val();
    if (selectedDate) {
        const today = new Date();
        const appointmentDate = new Date(selectedDate);
        today.setHours(0, 0, 0, 0);
        appointmentDate.setHours(0, 0, 0, 0);

        // Check if past date
        if (appointmentDate < today) {
            showDateNotification(
                "error",
                "Past Date Selected",
                "You cannot book an appointment for a past date. Please select today or a future date."
            );
            $(inputElement).val("");
            $("#dateAvailability").hide();
            return false;
        }

        // Check if date is fully booked
        if (!checkDateAvailability(selectedDate)) {
            showDateNotification(
                "warning",
                "Date Fully Booked",
                "This date has reached the maximum of 10 appointments. Please select another available date."
            );
            $(inputElement).val("");
            return false;
        } else {
            showDateNotification(
                "success",
                "Date Available",
                "Great! This date has available slots for appointments."
            );
        }
    }
    return true;
}

document.addEventListener("DOMContentLoaded", function () {
    $(document).on("click", "[data-open-modal]", function () {
        const modalSelector = $(this).data("open-modal");
        if (!modalSelector) return;

        // For Book Now modal, store selected service UUID
        if ($(this).hasClass("book-now-btn")) {
            // Check if button is disabled (for unavailable services)
            if ($(this).hasClass("disabled")) {
                return false;
            }

            const serviceUuid = $(this).data("service-uuid");
            window.selectedServiceUuids = serviceUuid ? [serviceUuid] : [];
        }

        $(modalSelector)
            .modal({
                autofocus: false,
                observeChanges: true,
                onShow: function () {
                    if (modalSelector === "#bookNowModal") {
                        // Load availability data first
                        loadAppointmentAvailability().then(function () {
                            console.log(
                                "Availability loaded, disabled dates:",
                                disabledDates
                            );
                        });

                        // Initialize pet dropdown first
                        $("#bookNowPetDropdown").dropdown();

                        // Initialize multi-select dropdown for services
                        $("#bookNowServiceDropdown").dropdown({
                            placeholder: "Select one or more services",
                            allowAdditions: false,
                            hideAdditions: true,
                            minCharacters: 0,
                        });

                        // Fetch pets
                        $.ajax({
                            url: "/src/features/dashboard/api/pets.php",
                            method: "GET",
                            data: { action: "all" },
                            dataType: "json",
                            success: function (response) {
                                var $dropdown = $("#bookNowPetDropdown");
                                $dropdown.empty();
                                $dropdown.append(
                                    '<option value="">Select your pet</option>'
                                );
                                if (
                                    response.success &&
                                    Array.isArray(response.data)
                                ) {
                                    response.data.forEach(function (pet) {
                                        $dropdown.append(
                                            $("<option>", {
                                                value: pet.uuid,
                                                text:
                                                    pet.name +
                                                    (pet.breed
                                                        ? " (" + pet.breed + ")"
                                                        : ""),
                                            })
                                        );
                                    });
                                }
                                $dropdown.dropdown("refresh");
                            },
                            error: function (xhr, status, error) {
                                console.error("Failed to fetch pets:", error);
                            },
                        });

                        // Fetch all services
                        $.ajax({
                            url: "/src/features/services/api/services.php",
                            method: "GET",
                            data: { action: "all" },
                            dataType: "json",
                            success: function (response) {
                                var $serviceDropdown = $(
                                    "#bookNowServiceDropdown"
                                );
                                $serviceDropdown.empty();
                                if (
                                    response.success &&
                                    Array.isArray(response.data)
                                ) {
                                    response.data.forEach(function (service) {
                                        $serviceDropdown.append(
                                            $("<option>", {
                                                value: service.uuid,
                                                text: service.name,
                                            })
                                        );
                                    });
                                    // Add "Others" option at the end
                                    $serviceDropdown.append(
                                        $("<option>", {
                                            value: "others",
                                            text: "Others (Custom Service Request)",
                                        })
                                    );
                                }

                                // Refresh dropdown FIRST
                                $serviceDropdown.dropdown("refresh");

                                // THEN set pre-selected values (after refresh)
                                if (
                                    window.selectedServiceUuids &&
                                    window.selectedServiceUuids.length > 0
                                ) {
                                    // Use setTimeout to ensure dropdown is fully ready
                                    setTimeout(function () {
                                        $serviceDropdown.dropdown(
                                            "set selected",
                                            window.selectedServiceUuids
                                        );
                                    }, 100);
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error(
                                    "Failed to fetch services:",
                                    error
                                );
                            },
                        });

                        // Add real-time date validation
                        $("#appointmentDateInput").on("change", function () {
                            validateDateInput(this);
                        });

                        // Add change event handler for service dropdown
                        $("#bookNowServiceDropdown")
                            .off("change")
                            .on("change", function () {
                                const selectedValues =
                                    $(this).dropdown("get value");

                                // Handle both array and string returns
                                let valuesArray = [];
                                if (Array.isArray(selectedValues)) {
                                    valuesArray = selectedValues;
                                } else if (typeof selectedValues === "string") {
                                    valuesArray = selectedValues
                                        ? selectedValues.split(",")
                                        : [];
                                }

                                const $customServiceField = $(
                                    "#customServiceField"
                                );
                                const $customTextarea =
                                    $customServiceField.find(
                                        'textarea[name="custom_service_request"]'
                                    );

                                if (valuesArray.includes("others")) {
                                    $customServiceField.show();
                                    $customTextarea.prop("required", true);
                                } else {
                                    $customServiceField.hide();
                                    $customTextarea.prop("required", false);
                                    $customTextarea.val(""); // Clear the field when hidden
                                }
                            });
                    }
                },
            })
            .modal("show");
    });
});

$(function () {
    $("#bookNowForm").form({
        fields: {
            date: {
                identifier: "date",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please select an appointment date",
                    },
                    {
                        type: "regExp[/^\\d{4}-\\d{2}-\\d{2}$/]",
                        prompt: "Please enter a valid date format",
                    },
                ],
            },
            pet_uuid: {
                identifier: "pet_uuid",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please select a pet",
                    },
                ],
            },
            custom_service_request: {
                identifier: "custom_service_request",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please describe the custom service you need",
                    },
                ],
                optional: true,
            },
        },
        onSuccess: function (event, fields) {
            event.preventDefault();
            console.log("Form validation passed, submitting...");

            // Additional validation: Check if selected date is fully booked
            if (disabledDates.includes(fields.date)) {
                showDateNotification(
                    "error",
                    "Date Fully Booked",
                    "This date is no longer available. Please select another date."
                );
                $("#appointmentDateInput").val("");
                $("#dateAvailability").hide();
                return false;
            }

            // Get selected services
            const selectedServices = $("#bookNowServiceDropdown").dropdown(
                "get value"
            );

            // Handle both array and string returns
            let serviceUuids = [];
            if (Array.isArray(selectedServices)) {
                serviceUuids = selectedServices;
            } else if (typeof selectedServices === "string") {
                serviceUuids = selectedServices
                    ? selectedServices.split(",")
                    : [];
            }

            // Manual validation for services (since array fields don't work well with Semantic UI)
            if (serviceUuids.length === 0) {
                $("#bookNowForm").form(
                    "add prompt",
                    "service_uuids[]",
                    "Please select at least one service"
                );
                // Show error on the dropdown
                $("#bookNowServiceDropdown").parent().addClass("error");
                return false;
            } else {
                $("#bookNowServiceDropdown").parent().removeClass("error");
            }

            // Custom validation for "Others" service
            const customServiceRequest = fields.custom_service_request;

            if (
                serviceUuids.includes("others") &&
                (!customServiceRequest || customServiceRequest.trim() === "")
            ) {
                // Show error for custom service field
                $("#bookNowForm").form(
                    "add prompt",
                    "custom_service_request",
                    "Please describe the custom service you need"
                );
                return false;
            }

            const formData = new FormData();
            formData.append("action", "add_multiple");
            formData.append("service_uuids", JSON.stringify(serviceUuids));
            formData.append("pet_uuid", fields.pet_uuid);
            formData.append("date", fields.date);
            formData.append("note", fields.special_request || "");

            // Add custom service request if "Others" is selected
            if (serviceUuids.includes("others")) {
                formData.append(
                    "custom_service_request",
                    fields.custom_service_request
                );
            }

            // Show loading state
            const submitBtn = $("#bookNowForm .ui.button");
            submitBtn.addClass("loading disabled");

            $.ajax({
                url: "/src/features/appointments/api/user-appointments.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json", // Add this to ensure proper JSON parsing
                success: function (response) {
                    console.log("Booking response:", response);
                    submitBtn.removeClass("loading disabled");

                    if (response.success) {
                        // Close modal first
                        $("#bookNowModal").modal("hide");

                        // Show simple alert message (like login)
                        alert(
                            "✅ Appointment booked successfully! We will contact you to confirm."
                        );

                        // Reset form
                        $("#bookNowForm")[0].reset();
                        $("#bookNowServiceDropdown").dropdown("clear");
                        $("#dateAvailability").hide();

                        // Reload page
                        setTimeout(function () {
                            window.location.reload();
                        }, 500);
                    } else {
                        alert(
                            "❌ " +
                                (response.message ||
                                    "Failed to book appointment. Please try again.")
                        );
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Booking error:", error);
                    console.log("XHR:", xhr);
                    submitBtn.removeClass("loading disabled");

                    // Try to parse error response
                    let errorMessage =
                        "An error occurred while booking your appointments. Please try again.";
                    try {
                        const errorResponse = JSON.parse(xhr.responseText);
                        if (errorResponse.message) {
                            errorMessage = errorResponse.message;
                        }
                    } catch (e) {
                        // Keep default message
                    }

                    showDateNotification(
                        "error",
                        "Booking Failed",
                        errorMessage
                    );
                },
            });

            return false; // Prevent default form submission
        },
        onFailure: function () {
            // Add debug logging for validation failures
            console.log("Form validation failed");
            const errors = $(this).find(".error.message .list li");
            console.log(
                "Validation errors:",
                errors
                    .map(function () {
                        return $(this).text();
                    })
                    .get()
            );
            return false;
        },
    });
});

// src/features/services/js/booknow.js

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

// Real-time date validation
function validateDateInput(inputElement) {
    const selectedDate = $(inputElement).val();
    if (selectedDate) {
        const today = new Date();
        const appointmentDate = new Date(selectedDate);
        today.setHours(0, 0, 0, 0);
        appointmentDate.setHours(0, 0, 0, 0);

        if (appointmentDate < today) {
            showDateNotification(
                "error",
                "Past Date Selected",
                "You cannot book an appointment for a past date. Please select today or a future date."
            );
            $(inputElement).val(""); // Clear the invalid date
            return false;
        } else {
            // Show success notification for valid future date
            showDateNotification(
                "success",
                "Date Selected",
                "Great! You have selected a valid appointment date."
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
                        $(
                            "#bookNowPetDropdown, #bookNowServiceDropdown"
                        ).dropdown();

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
                                    if (
                                        window.selectedServiceUuids &&
                                        window.selectedServiceUuids.length
                                    ) {
                                        $serviceDropdown.val(
                                            window.selectedServiceUuids[0]
                                        );
                                    }
                                }
                                $serviceDropdown.dropdown("refresh");
                            },
                            error: function (xhr, status, error) {
                                console.error(
                                    "Failed to fetch services:",
                                    error
                                );
                            },
                        });

                        // Add real-time date validation
                        $('input[name="date"]').on("change", function () {
                            validateDateInput(this);
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
            full_name: {
                identifier: "full_name",
                rules: [
                    {
                        type: "empty",
                        prompt: "Full name is required",
                    },
                ],
            },
            email: {
                identifier: "email",
                rules: [
                    {
                        type: "empty",
                        prompt: "Email is required",
                    },
                    {
                        type: "email",
                        prompt: "Please enter a valid email",
                    },
                ],
            },
            phone: {
                identifier: "phone",
                rules: [
                    {
                        type: "empty",
                        prompt: "Phone number is required",
                    },
                ],
            },
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
            service_uuid: {
                identifier: "service_uuid",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please select a service",
                    },
                ],
            },
        },
        onSuccess: function () {
            const $form = $(this);
            const $submitBtn = $form.find("button[type=submit]");

            // Validate date before submission
            const selectedDate = $form.find('input[name="date"]').val();
            if (selectedDate) {
                const today = new Date();
                const appointmentDate = new Date(selectedDate);
                today.setHours(0, 0, 0, 0); // Reset time to start of day
                appointmentDate.setHours(0, 0, 0, 0); // Reset time to start of day

                if (appointmentDate < today) {
                    // Show notification for past date selection
                    showDateNotification(
                        "error",
                        "Past Date Selected",
                        "You cannot book an appointment for a past date. Please select today or a future date."
                    );
                    return false;
                }
            }

            $submitBtn.addClass("loading");

            const formData = $form.serialize();

            $.ajax({
                url: "/src/features/appointments/api/appointments.php",
                method: "POST",
                data: formData,
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        alert(response.message);
                        $("#bookNowModal").modal("hide");
                        $form[0].reset();
                        $form.find(".ui.dropdown").dropdown("clear");
                    } else {
                        alert(
                            "Failed to book appointment: " + response.message
                        );
                    }
                },
                complete: function () {
                    $submitBtn.removeClass("loading");
                },
                error: function (xhr, status, error) {
                    alert("Failed to book appointment. Please try again.");
                },
            });

            return false; // Prevent default form submission
        },
    });
});

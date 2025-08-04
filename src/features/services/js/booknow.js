// src/features/services/js/booknow.js

document.addEventListener("DOMContentLoaded", function () {
    $(document).on("click", "[data-open-modal]", function () {
        const modalSelector = $(this).data("open-modal");
        if (!modalSelector) return;

        // For Book Now modal, store selected service UUID
        if ($(this).hasClass("book-now-btn")) {
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

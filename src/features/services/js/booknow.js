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
    $("#bookNowForm").on("submit", function (e) {
        e.preventDefault();
        const $form = $(this);
        const $submitBtn = $form.find("button[type=submit]");
        $submitBtn.addClass("loading");

        // Collect form data
        const formData = $form.serialize();

        $.ajax({
            url: "/src/features/appointments/api/appointments.php",
            method: "POST",
            data: formData,
            dataType: "json",
            success: function (response) {
                alert(response.message);
                if (response.success) {
                    $("#bookNowModal").modal("hide");
                    $form[0].reset();
                }
            },
            complete: function () {
                $submitBtn.removeClass("loading");
            },
            error: function (xhr, status, error) {
                alert("Failed to book appointment: " + error);
            },
        });
    });
});

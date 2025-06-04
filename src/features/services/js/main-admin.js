$(function () {
    // Initialize dropdowns
    $(".ui.dropdown").dropdown();

    // Add Service Button - Open modal
    $("#addServiceBtn").on("click", function () {
        $(".ui.modal.service-form-modal").modal("show");
    });

    // Image preview
    $('input[name="image"]').on("change", function (e) {
        if (e.target.files && e.target.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#imagePreview").attr("src", e.target.result);
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    // Status toggle buttons
    $(".ui.red.basic.button").on("click", function () {
        // In real implementation, this would call an API to update the service status
        $(this)
            .closest(".service-card")
            .find(".status")
            .removeClass("available")
            .addClass("unavailable")
            .html('<i class="times circle icon"></i> Unavailable');

        $(this).replaceWith(`
            <button class="ui green basic button">
                <i class="check circle icon"></i>
                Set Available
            </button>
        `);
    });

    $(".ui.green.basic.button").on("click", function () {
        // In real implementation, this would call an API to update the service status
        $(this)
            .closest(".service-card")
            .find(".status")
            .removeClass("unavailable")
            .addClass("available")
            .html('<i class="check circle icon"></i> Available');

        $(this).replaceWith(`
            <button class="ui red basic button">
                <i class="times circle icon"></i>
                Set Unavailable
            </button>
        `);
    });
});

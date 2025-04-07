function validateHandler(options = {}) {
    const defaults = {
        fields: {},
        form: null,
    };

    const config = { ...defaults, ...options };
    let $form = $(config.form); // Ensure $form is a jQuery object
    if (!$form || !$form.length) return false;

    let $submitBtn = $form.find("button[type=submit]");
    $form.attr("data-is-configured", true); // Set flag to true for form configuration submission use

    $form.form({
        fields: config.fields,
        inline: true,
        on: "blur",
        onSuccess: function (event) {
            event.preventDefault();

            // Double check button state before proceeding
            if ($submitBtn.hasClass("loading")) return false;
            $submitBtn.addClass("loading");

            const formData = new FormData(this);
            console.log(formData);
            return false;
        },
    });

    // $form.api({
    //     url: "",
    //     method: "POST",
    //     data: {},
    //     dataType: "json",
    //     serializeForm: true,
    //     beforeSend: function (settings) {
    //         console.log(settings);
    //         return false;
    //     },
    //     onSuccess: function (response) {
    //         console.log(response);
    //         return false;
    //     },
    // });
}

$(function () {
    // Prevent submission of unconfigured forms
    $("body").on("click", ".ui.form button[type=submit]", function () {
        let isConfigured = $(this).closest(".ui.form").data("is-configured");
        if (!isConfigured) {
            console.log("Form is not validated yet, TODO: use validateHandler");
            return false;
        }
    });
});

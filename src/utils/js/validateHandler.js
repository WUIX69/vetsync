function validateHandler(options = {}) {
    const defaults = {
        fields: {},
        form: null,
        api_url: null,
    };

    const config = { ...defaults, ...options };
    let $form = $(config.form); // Ensure $form is a jQuery object
    if (!$form || !$form.length) return false;

    let $submitBtn = $form.find("button[type=submit]");
    $form.attr("data-was-validated", true); // Set flag to true for form configuration submission use

    $form.form({
        fields: config.fields,
        inline: true,
        on: "blur",
        onSuccess: function (event, fields) {
            event.preventDefault();
            const formData = new FormData(this);

            // console.log(formData);
            // console.log(config.api_url);
            // console.log(this);
            // console.log($(this));
            // console.log(fields);
            // return false;

            $submitBtn.api({
                url: config.api_url,
                method: "POST",
                data: formData,
                dataType: "json",
                serializeForm: true,
                timeout: 5000,
                loading: true,
                beforeSend: function (settings) {
                    console.log(settings);
                    return false;
                },
                onSuccess: function (response) {
                    console.log(response);
                    alert(response.message);
                    return false;
                },
                onComplete: function () {
                    // Any Cleanup here...
                },
                onFailure: function (formErrors, fields) {
                    console.log("Failure");
                    // $("body").toast({
                    //     message: formErrors.join("<br/>\n"),
                    //     showIcon: "exclamation circle",
                    //     displayTime: 0,
                    //     className: {
                    //         toast: "ui error message",
                    //     },
                    //     compact: false,
                    // });
                    // return false;
                },
                onError: function () {
                    console.log("Error");
                },
            });
        },
    });
}

$(function () {
    // Prevent submission of non-validated forms
    $("body").on("click", ".ui.form button[type=submit]", function () {
        let wasValidated = $(this).closest(".ui.form").data("was-validated");
        if (!wasValidated) {
            console.log("Form is not validated yet, TODO: use validateHandler");
            return false;
        }
    });
});

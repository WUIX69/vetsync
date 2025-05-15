$(function () {
    // console.log("login scripts");
    const $loginForm = $("#loginForm");
    $loginForm.form({
        fields: {
            email: {
                identifier: "email",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter an email",
                    },
                ],
            },
            password: {
                identifier: "password",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter a password",
                    },
                ],
            },
            remember: {
                identifier: "remember",
                optional: true,
                // rules: [],
            },
        },
        inline: true,
        on: "blur",
        onFailure: function (formErrors, fields) {
            console.log("Form validation failed:", formErrors);
        },
        onSuccess: function (event, fields) {
            event.preventDefault();
            const $submitBtn = $(this).find("button[type=submit]");
            const formData = new FormData(this);

            $submitBtn.api({
                url: apiUrl("auth/login") + "users.php",
                method: "POST",
                data: formData,
                dataType: "json",
                serializeForm: true,
                timeout: 5000,
                loading: true,
                onSuccess: function (response) {
                    console.log(response);
                    alert(response.message);

                    if (!response.success) return false;
                    window.location.replace(response.route);
                },
                onComplete: function () {
                    // Any Cleanup here...
                },
                onError: onErrorHandler,
            });
        },
    });

    // $url = apiUrl("auth/login") + "main.php";
    // console.log($url);
});

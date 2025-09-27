$(function () {
    $("#registerForm").form({
        fields: {
            firstname: {
                identifier: "firstname",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter your first name",
                    },
                ],
            },
            lastname: {
                identifier: "lastname",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter your last name",
                    },
                ],
            },
            email: {
                identifier: "email",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter your email",
                    },
                    {
                        type: "email",
                        prompt: "Please enter a valid email address",
                    },
                ],
            },
            telephone: {
                identifier: "telephone",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter your phone number",
                    },
                    // Fix the regex - remove problematic regex
                    {
                        type: "minLength[10]",
                        prompt: "Please enter at least 10 digits",
                    },
                ],
            },
            password: {
                identifier: "password",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter your password",
                    },
                    {
                        type: "minLength[6]",
                        prompt: "Password must be at least 6 characters",
                    },
                ],
            },
            confirm_password: {
                identifier: "confirm_password",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please confirm your password",
                    },
                    {
                        type: "match[password]",
                        prompt: "Passwords do not match",
                    },
                ],
            },
            terms: {
                identifier: "terms",
                rules: [
                    {
                        type: "checked",
                        prompt: "Please accept the terms and conditions",
                    },
                ],
            },
        },
        inline: true,
        on: "blur",
        onSuccess: function (event, fields) {
            event.preventDefault();
            console.log("Form validation passed!");
            console.log("Fields:", fields);

            const $submitBtn = $(this).find("button[type=submit]");
            const apiURL = apiUrl("auth") + "register.php";
            console.log("API URL:", apiURL);

            $.ajax({
                url: apiURL,
                method: "POST",
                data: fields,
                dataType: "json",
                timeout: 10000,
                beforeSend: function () {
                    console.log("Sending registration request...");
                    $submitBtn.addClass("loading");
                },
                success: function (response) {
                    console.log("API Response:", response);
                    alert(response.message);

                    if (response.success) {
                        alert(
                            "Registration successful! Redirecting to login..."
                        );
                        window.location.replace("index.php");
                    }
                },
                complete: function () {
                    $submitBtn.removeClass("loading");
                },
                error: function (xhr, status, error) {
                    console.log("AJAX Error:", xhr.status, status, error);
                    console.log("Response Text:", xhr.responseText);
                    alert("Registration failed: " + error);
                },
            });
        },
        onFailure: function (formErrors, fields) {
            console.log("Form validation failed!");
            console.log("Errors:", formErrors);
            console.log("Fields:", fields);
        },
    });
});

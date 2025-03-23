$(function () {
    $(".ui.dropdown").dropdown();

    // Show/hide admin fields based on user type selection
    $('input[name="user_type"]').change(function () {
        if ($(this).val() === "admin") {
            $(".admin-fields").show();
            $(".admin-fields input").prop("required", true);
        } else {
            $(".admin-fields").hide();
            $(".admin-fields input").prop("required", false);
        }
    });

    $(".ui.form").form({
        fields: {
            name: {
                identifier: "name",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter your full name",
                    },
                ],
            },
            email: {
                identifier: "email",
                rules: [
                    {
                        type: "email",
                        prompt: "Please enter a valid email address",
                    },
                ],
            },
            phone: {
                identifier: "phone",
                rules: [
                    {
                        type: "regExp[/^[0-9]{10}$/]",
                        prompt: "Please enter a valid 10-digit phone number",
                    },
                ],
            },
            user_type: {
                identifier: "user_type",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please select an account type",
                    },
                ],
            },
            password: {
                identifier: "password",
                rules: [
                    {
                        type: "minLength[8]",
                        prompt: "Password must be at least 8 characters",
                    },
                ],
            },
            confirmPassword: {
                identifier: "confirmPassword",
                rules: [
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
                        prompt: "You must agree to the Terms and Conditions",
                    },
                ],
            },
        },
    });
});

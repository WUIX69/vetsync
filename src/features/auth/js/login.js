$(function () {
    // Validate login form
    $("#loginForm").form({
        fields: {
            email: {
                identifier: "email",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter an email",
                    },
                    {
                        type: "email",
                        prompt: "Please enter a valid email address",
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
            },
        },
        inline: true,
        on: "blur",
        onSuccess: function (event, fields) {
            event.preventDefault();
            const formData = new FormData(this);

            $.ajax({
                url: apiUrl("auth") + "login.php",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                timeout: 5000,
                success: function (response) {
                    if (response.success) {
                        window.location.href = response.data.route;
                    } else {
                        alert(response.message);
                    }
                },
                error: function (xhr) {
                    alert("Login failed. Please try again.");
                },
            });
        },
    });

    // Forgot Password Modal
    $("#forgotPasswordLink").click(function (e) {
        e.preventDefault();
        $("#forgotPasswordModal").modal("show");
    });

    // Validate forgot password form
    $("#forgotPasswordForm").form({
        fields: {
            email: {
                identifier: "email",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter your email",
                    },
                    {
                        type: "email",
                        prompt: "Please enter a valid email",
                    },
                ],
            },
        },
        inline: true,
        on: "blur",
    });

    // Send reset link (Development: Generate temp password)
    $("#sendResetLink").click(function () {
        if (!$("#forgotPasswordForm").form("is valid")) {
            return;
        }

        const $btn = $(this);
        const formData = new FormData($("#forgotPasswordForm")[0]);

        $.ajax({
            url: apiUrl("auth") + "forgot-password.php",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            beforeSend: function () {
                $btn.addClass("loading");
                $("#tempPasswordDisplay").hide();
            },
            success: function (response) {
                if (response.success) {
                    // Show temporary password
                    if (response.temp_password) {
                        $("#tempPasswordValue").text(response.temp_password);
                        $("#tempPasswordDisplay").show();
                        $btn.text("Close")
                            .removeClass("positive")
                            .addClass("black");

                        // Change button to close on next click
                        $btn.off("click").on("click", function () {
                            $("#forgotPasswordModal").modal("hide");
                            $("#forgotPasswordForm")[0].reset();
                            $("#tempPasswordDisplay").hide();
                            location.reload(); // Reload to reset button
                        });
                    } else {
                        // Email version (when deployed)
                        alert(response.message);
                        $("#forgotPasswordModal").modal("hide");
                        $("#forgotPasswordForm")[0].reset();
                    }
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                alert("Failed to reset password. Please try again.");
            },
            complete: function () {
                $btn.removeClass("loading");
            },
        });
    });
});

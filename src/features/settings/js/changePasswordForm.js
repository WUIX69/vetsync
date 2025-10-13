$(function () {
    $("#changePasswordForm").form({
        fields: {
            current_password: {
                identifier: "current_password",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter your current password",
                    },
                ],
            },
            new_password: {
                identifier: "new_password",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter your new password",
                    },
                    {
                        type: "minLength[6]",
                        prompt: "Your new password must be at least 6 characters",
                    },
                ],
            },
            confirm_new_password: {
                identifier: "confirm_new_password",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please confirm your new password",
                    },
                    {
                        type: "match[new_password]",
                        prompt: "Passwords do not match",
                    },
                ],
            },
        },
        inline: true,
        on: "submit",
        onSuccess: function (event, fields) {
            event.preventDefault();
            const $form = $(this);
            const $submitBtn = $form.find("button[type=submit]");

            $.ajax({
                url: apiUrl("settings") + "changePassword.php",
                method: "POST",
                data: fields,
                dataType: "json",
                timeout: 10000,
                beforeSend: function () {
                    $submitBtn.addClass("loading");
                },
                success: function (response) {
                    console.log("Password change response:", response);

                    if (response.success) {
                        // Disable browser password manager popup
                        setTimeout(function () {
                            $("input[type=password]")
                                .attr("type", "text")
                                .val("");
                            setTimeout(function () {
                                $("input[type=text]").attr("type", "password");
                            }, 50);
                        }, 100);

                        // Show success notification
                        $("body").toast({
                            title: "Success!",
                            message:
                                response.message ||
                                "Password updated successfully!",
                            class: "success",
                            displayTime: 4000,
                            position: "top right",
                        });

                        // Reset form
                        $form[0].reset();
                        $form.form("remove prompt");

                        // Optional: Auto-logout after password change
                        setTimeout(function () {
                            if (
                                confirm(
                                    "Password changed successfully! Would you like to log out and log back in with your new password?"
                                )
                            ) {
                                window.location.href = "/src/app/auth/";
                            }
                        }, 2000);
                    } else {
                        // Show error notification
                        $("body").toast({
                            title: "Error!",
                            message:
                                response.message || "Failed to update password",
                            class: "error",
                            displayTime: 5000,
                            position: "top right",
                        });
                    }
                },
                complete: function () {
                    $submitBtn.removeClass("loading");
                },
                error: function (xhr, status, error) {
                    console.error("Password change error:", {
                        xhr,
                        status,
                        error,
                    });

                    // Show error notification
                    $("body").toast({
                        title: "Network Error!",
                        message:
                            "Failed to connect to server. Please try again.",
                        class: "error",
                        displayTime: 5000,
                        position: "top right",
                    });
                },
            });
        },
    });
});

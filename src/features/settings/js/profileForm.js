$(function () {
    $("#profileForm").form({
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
            telephone: {
                identifier: "telephone",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter your phone number",
                    },
                    {
                        type: "regExp[/^[0-9-+s()]{7,15}$/]",
                        prompt: "Please enter a valid phone number",
                    },
                ],
            },
            location: {
                identifier: "location",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter your address",
                    },
                ],
            },
        },
        inline: true,
        on: "blur",
        onSuccess: function (event, fields) {
            event.preventDefault();
            const $submitBtn = $(this).find("button[type=submit]");

            // Get form data
            let formSerialized = $(this).serializeArray();
            formSerialized.push({ name: "action", value: "profile-update" });

            // Debug: Log what we're sending
            console.log("Form data being sent:", formSerialized);

            $.ajax({
                url: apiUrl("settings") + "profilePost.php",
                method: "POST",
                data: formSerialized,
                dataType: "json",
                timeout: 10000,
                beforeSend: function () {
                    $submitBtn.addClass("loading");
                    console.log("Sending profile update request...");
                },
                success: function (response) {
                    console.log("Profile update API Response:", response);

                    if (response && response.success === true) {
                        // Show success message
                        $("body").toast({
                            title: "Success!",
                            message:
                                response.message ||
                                "Profile updated successfully!",
                            class: "success",
                            displayTime: 3000,
                            position: "top right",
                        });

                        // Store current form values for comparison
                        const currentValues = {};
                        formSerialized.forEach(function (item) {
                            if (item.name !== "action") {
                                currentValues[item.name] = item.value;
                            }
                        });
                        console.log("Stored current values:", currentValues);

                        // Refresh the profile data after a longer delay to ensure DB is updated
                        setTimeout(function () {
                            console.log("Refreshing profile data...");
                            getProfile();

                            // Verify the data was actually updated after another delay
                            setTimeout(function () {
                                const updatedValues = {};
                                $("#profileForm")
                                    .find("input[name]")
                                    .each(function () {
                                        const name = $(this).attr("name");
                                        const value = $(this).val();
                                        updatedValues[name] = value;
                                    });
                                console.log(
                                    "Updated values after refresh:",
                                    updatedValues
                                );

                                // Compare values
                                let allMatched = true;
                                Object.keys(currentValues).forEach(function (
                                    key
                                ) {
                                    if (
                                        updatedValues[key] !==
                                        currentValues[key]
                                    ) {
                                        console.warn(
                                            `Value mismatch for ${key}: expected "${currentValues[key]}", got "${updatedValues[key]}"`
                                        );
                                        allMatched = false;
                                    }
                                });

                                if (allMatched) {
                                    console.log(
                                        "✅ All values match - update was successful!"
                                    );
                                } else {
                                    console.error(
                                        "❌ Some values don't match - there may be an issue with the update"
                                    );
                                }
                            }, 1000);
                        }, 1000);
                    } else {
                        console.error("Profile update failed:", response);
                        $("body").toast({
                            title: "Error!",
                            message:
                                response.message || "Failed to update profile",
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
                    console.error("AJAX Error:", {
                        status: status,
                        error: error,
                        responseText: xhr.responseText,
                        statusCode: xhr.status,
                    });

                    let errorMessage =
                        "An error occurred while updating your profile.";

                    // Try to parse error response
                    try {
                        const errorResponse = JSON.parse(xhr.responseText);
                        if (errorResponse.message) {
                            errorMessage = errorResponse.message;
                        }
                    } catch (e) {
                        // Use default message
                    }

                    $("body").toast({
                        title: "Error!",
                        message: errorMessage,
                        class: "error",
                        displayTime: 5000,
                        position: "top right",
                    });
                    $submitBtn.removeClass("loading");
                },
            });
        },
    });
});

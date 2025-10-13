// Cache DOM elements
const $userModal = $("#userModal");
const $userModalForm = $userModal.find("#userForm");

$(function () {
    // Validate login form
    $userModalForm.form({
        fields: {
            firstname: {
                identifier: "firstname",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter a first name",
                    },
                ],
            },
            lastname: {
                identifier: "lastname",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter a last name",
                    },
                ],
            },
            email: {
                identifier: "email",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter an email",
                    },
                    {
                        type: "email",
                        prompt: "Please enter a valid email",
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
                    {
                        type: "minLength[6]",
                        prompt: "Password must be at least 6 characters",
                    },
                ],
            },
            telephone: {
                identifier: "telephone",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter a telephone number",
                    },
                ],
            },
            role: {
                identifier: "role",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please select a role",
                    },
                ],
            },
        },
        inline: true,
        on: "blur",
        onSuccess: function (event, fields) {
            event.preventDefault();
            const $submitBtn = $(this).find("button[type=submit]");

            console.log("💾 Saving user data:", fields);

            $.ajax({
                url: "../../features/users/api/users.php",
                method: "POST",
                data: {
                    action: fields.uuid ? "update" : "store",
                    ...fields,
                },
                dataType: "json",
                timeout: 5000,
                beforeSend: function () {
                    $submitBtn.addClass("loading");
                    console.log("⏳ Sending user data to server...");
                },
                success: function (response) {
                    console.log("✅ User save response:", response);

                    if (response && response.success) {
                        // ✅ SHOW SUCCESS MESSAGE
                        alert(
                            "✅ " +
                                (response.message || "User saved successfully!")
                        );

                        // ✅ SIMPLE SOLUTION: ALWAYS REFRESH PAGE
                        console.log("�� Refreshing page to show new user...");
                        location.reload();
                    } else {
                        alert(
                            "❌ " + (response.message || "Failed to save user")
                        );
                    }
                },
                complete: function () {
                    $submitBtn.removeClass("loading");
                    console.log("⏹️ User save request completed");
                },
                error: function (xhr, status, error) {
                    console.error("❌ User save error:", {
                        xhr,
                        status,
                        error,
                        responseText: xhr.responseText,
                    });
                    alert("❌ Error saving user: " + error);
                },
            });
        },
    });
});

// ✅ EXPOSE DATATABLE TO WINDOW FOR ACCESS
$(document).ready(function () {
    // Wait for DataTable to be initialized, then expose it
    setTimeout(function () {
        if (typeof $usersDataTable !== "undefined") {
            window.$usersDataTable = $usersDataTable;
            console.log("✅ DataTable exposed to window");
        }
    }, 1000);
});

// Export userModal and userModalForm to window for debugging
window.$userModal = $userModal;
window.$userModalForm = $userModalForm;

$(function () {
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
                            prompt: "Please enter your telephone",
                        },
                        {
                            type: "number",
                            prompt: "Please enter a valid telephone number",
                        },
                    ],
                },
                dob: {
                    identifier: "dob",
                    rules: [
                        {
                            type: "empty",
                            prompt: "Please enter your date of birth",
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
                location: {
                    identifier: "location",
                    rules: [
                        {
                            type: "empty",
                            prompt: "Please enter your location",
                        },
                    ],
                },
                bio: {
                    identifier: "bio",
                    optional: true,
                    rules: [],
                },
                url: {
                    identifier: "url[]",
                    optional: true,
                    rules: [],
                },
            },
            inline: true,
            on: "blur", // EG: submit, blur
            onSuccess: function (event, fields) {
                event.preventDefault();
                const $submitBtn = $(this).find("button[type=submit]");
                // const formData = new FormData(this); // Only use when a file is included

                // console.log(formData);
                // console.log(fields);
                // return false;

                $.ajax({
                    url: apiUrl("settings") + "profile.php",
                    method: "POST",
                    data: {
                        action: "profile-update",
                        ...fields,
                    },
                    // processData: false, // Only use when FormData is used
                    // contentType: false, // Only use when FormData is used
                    dataType: "json",
                    timeout: 5000,
                    beforeSend: function () {
                        $submitBtn.addClass("loading");
                    },
                    success: function (response) {
                        console.log("API Response:", response);
                        alert(response.message);
                    },
                    complete: function () {
                        $submitBtn.removeClass("loading");
                    },
                    error: ajaxErrorHandler,
                });
            },
        });
    });
});

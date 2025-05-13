// Cache DOM elements
const $userModal = $("#userModal");
const $userForm = $userModal.find("#userForm");

function validateUserForm($form = null) {
    let userFormConf = {
        form: $form,
        fields: {
            name: {
                identifier: "name",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter a name",
                    },
                    {
                        type: "minLength[2]",
                        prompt: "Name must be at least 2 characters",
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
    };
    validateHandler(userFormConf);
}

$(function () {
    validateUserForm($userForm);
});

const $reminderModal = $("#reminderModal");
const $reminderForm = $reminderModal.find(".ui.form");

// Date validation notification function for reminders
function showReminderDateNotification(type, title, message) {
    // Create notification element
    const notification = $(`
        <div class="ui ${type} message reminder-date-notification" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <div class="header">
                <i class="calendar times icon"></i>
                ${title}
            </div>
            <p>${message}</p>
            <button class="ui mini button" onclick="$(this).closest('.reminder-date-notification').fadeOut()">
                <i class="close icon"></i>
                Dismiss
            </button>
        </div>
    `);

    // Add to page and animate
    $("body").append(notification);
    notification.hide().fadeIn(300);

    // Auto-remove after 5 seconds
    setTimeout(() => {
        notification.fadeOut(300, function () {
            $(this).remove();
        });
    }, 5000);
}

// Real-time date validation for reminders
function validateReminderDate(inputElement) {
    const selectedDate = $(inputElement).val();
    if (selectedDate) {
        const today = new Date();
        const reminderDate = new Date(selectedDate);
        today.setHours(0, 0, 0, 0);
        reminderDate.setHours(0, 0, 0, 0);

        if (reminderDate < today) {
            showReminderDateNotification(
                "error",
                "Past Date Selected",
                "You cannot set a reminder for a past date. Please select today or a future date."
            );
            $(inputElement).val(""); // Clear the invalid date
            return false;
        } else {
            // Show success notification for valid future date
            showReminderDateNotification(
                "success",
                "Reminder Date Selected",
                "Great! You have selected a valid reminder date."
            );
        }
    }
    return true;
}

$(function () {
    initModal({
        modal_id: $reminderModal,
        transition: "swing down",
        duration: 800,
        onShow: function () {
            // Add real-time date validation for reminders
            $('input[name="date"]')
                .off("change")
                .on("change", function () {
                    validateReminderDate(this);
                });
        },
    });

    // Validate login form
    $reminderForm.form({
        fields: {
            employee: {
                identifier: "employee",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter a name",
                    },
                ],
            },
            employee_search: {
                identifier: "employee_search",
                optional: true,
                rules: [],
            },
            date: {
                identifier: "date",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter a date",
                    },
                ],
            },
            time_in: {
                identifier: "time_in",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please select a time in",
                    },
                ],
            },
            time_out: {
                identifier: "time_out",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please select a time in",
                    },
                ],
            },
            memo: {
                identifier: "memo",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter a memo",
                    },
                    {
                        type: "minLength[2]",
                        prompt: "Memo must be at least 2 characters",
                    },
                ],
            },
        },
        inline: true,
        on: "blur", // EG: submit, blur
        onSuccess: function (event, fields) {
            event.preventDefault();
            const $submitBtn = $(this).find("button[type=submit]");

            console.log(fields);
            return false;
        },
    });
});

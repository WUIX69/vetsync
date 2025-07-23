function getAllAppointments() {
    $.ajax({
        url: "/src/features/appointments/api/appointments.php",
        method: "GET",
        dataType: "json",
        success: function (response) {
            // console.log(response.data); // Add this line
            if (!response.success) {
                alert(response.message);
                return;
            }
            // Clear all containers
            $(
                "#appointmentsCardsAll, #appointmentsCardsPending, #appointmentsCardsConfirmed, #appointmentsCardsCancelled"
            ).empty();

            response.data.forEach(function (app) {
                // Always append to All Appointments
                let statusClass = "";
                let statusLabel = "";

                // Map DB status to UI
                if (app.status === "pending") {
                    statusClass = "status-pending";
                    statusLabel = "Pending";
                    $("#appointmentsCardsPending").append(
                        cardHtml(app, statusClass, statusLabel)
                    );
                } else if (app.status === "accepted") {
                    statusClass = "status-confirmed";
                    statusLabel = "Confirmed";
                    $("#appointmentsCardsConfirmed").append(
                        cardHtml(app, statusClass, statusLabel)
                    );
                } else if (app.status === "cancelled") {
                    statusClass = "status-cancelled";
                    statusLabel = "Cancelled";
                    $("#appointmentsCardsCancelled").append(
                        cardHtml(app, statusClass, statusLabel)
                    );
                } else if (app.status === "completed") {
                    statusClass = "status-completed";
                    statusLabel = "Completed";
                    // Add to a completed tab if you have one
                }

                // Always append to All Appointments
                $("#appointmentsCardsAll").append(
                    cardHtml(app, statusClass, statusLabel)
                );
            });
        },
    });
}
$(function () {
    getAllAppointments();
});

// 1. Event delegation for status buttons
$(document).on("click", ".appointment-actions .action-btn", function () {
    const $card = $(this).closest(".appointment-card");
    const uuid = $card.data("uuid");
    let newStatus = null;

    if ($(this).hasClass("green")) {
        newStatus = "accepted"; // Confirm
    } else if ($(this).hasClass("blue")) {
        newStatus = "pending"; // Reschedule
    } else if ($(this).hasClass("red")) {
        newStatus = "cancelled"; // Cancel
    }

    if (!uuid || !newStatus) {
        alert("Missing appointment ID or status!");
        return;
    }

    $.ajax({
        url: "/src/features/appointments/api/appointments.php",
        method: "POST",
        data: {
            action: "update_status",
            uuid: uuid,
            status: newStatus,
        },
        dataType: "json",
        success: function (response) {
            alert(response.message); // Show message (success or error)
            if (response.success) {
                getAllAppointments(); // Refresh the list after message
            }
        },
        error: function (xhr, status, error) {
            alert("AJAX error: " + error);
        },
    });
});

function cardHtml(app, statusClass, statusLabel) {
    return `
    <div class="appointment-card" data-uuid="${app.uuid}">
        <div class="appointment-header">
            <div class="appointment-time">
                <i class="material-icons-sharp">schedule</i>
                ${app.time || ""}
            </div>
            <span class="appointment-status ${statusClass}">${statusLabel}</span>
        </div>
        <div class="appointment-patient">
            <div class="patient-avatar">
                <img src="${
                    app.pet_avatar || "/img/placeholders/image.png"
                }" alt="Pet">
            </div>
            <div class="patient-info">
                <h4>${app.pet_name || app.pet_uuid}</h4>
                <p>Owner: ${app.user_name || app.user_uuid}</p>
            </div>
        </div>
        <div class="appointment-service">
            <strong>Service:</strong> ${app.service_name || ""}
        </div>
        <div class="appointment-actions">
            <button class="ui action-btn green button">Confirm</button>
            <button class="ui action-btn blue button">Reschedule</button>
            <button class="ui action-btn red button">Cancel</button>
        </div>
    </div>
    `;
}

// Helper function to extract original user instructions from note (for admin side)
function getOriginalInstructions(note) {
    if (!note) {
        return "No special instructions provided.";
    }

    // Remove admin messages and get original instructions
    let originalNote = note;

    // Remove admin cancellation messages
    if (originalNote.includes("[CANCELLED BY ADMIN]")) {
        originalNote = originalNote.split("[CANCELLED BY ADMIN]")[0];
    }

    // Remove admin reschedule messages
    if (originalNote.includes("[RESCHEDULED BY ADMIN]")) {
        originalNote = originalNote.split("[RESCHEDULED BY ADMIN]")[0];
    }

    // Clean up whitespace and newlines
    originalNote = originalNote.trim().replace(/\n\n$/, "");

    // Return original instructions or default message
    return originalNote || "No special instructions provided.";
}

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
                "#appointmentsCardsAll, #appointmentsCardsPending, #appointmentsCardsConfirmed, #appointmentsCardsCompleted, #appointmentsCardsCancelled"
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
                } else if (app.status === "completed") {
                    statusClass = "status-completed";
                    statusLabel = "Completed";
                    $("#appointmentsCardsCompleted").append(
                        cardHtml(app, statusClass, statusLabel, true) // true = completed
                    );
                } else if (app.status === "cancelled") {
                    statusClass = "status-cancelled";
                    statusLabel = "Cancelled";
                    $("#appointmentsCardsCancelled").append(
                        cardHtml(app, statusClass, statusLabel)
                    );
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

// 1. Event delegation for status buttons (but ignore buttons with specific handlers)
$(document).on("click", ".appointment-actions .action-btn", function (e) {
    // Only ignore buttons that actually have their own specific handlers
    if ($(this).hasClass("btn-delete") || $(this).hasClass("btn-cancel")) {
        return; // Let specific handlers deal with these
    }

    const $card = $(this).closest(".appointment-card");
    const uuid = $card.data("uuid");
    let newStatus = null;

    if (
        $(this).hasClass("btn-confirm") ||
        ($(this).hasClass("green") && !$(this).hasClass("btn-complete"))
    ) {
        newStatus = "accepted"; // Confirm
    } else if (
        $(this).hasClass("btn-complete") ||
        $(this).hasClass("complete-btn")
    ) {
        newStatus = "completed"; // Mark as Complete
    } else if (
        $(this).hasClass("btn-reschedule") ||
        $(this).hasClass("reschedule-btn")
    ) {
        // Handle reschedule with date picker
        openRescheduleModal($card);
        return;
    } else if ($(this).hasClass("red") && !$(this).hasClass("btn-delete")) {
        newStatus = "cancelled"; // Cancel
    } else if ($(this).hasClass("view-details")) {
        // Handle view details for completed appointments
        alert("View appointment details (to be implemented)");
        return;
    } else if ($(this).hasClass("download-report")) {
        // Handle download report for completed appointments
        alert("Download appointment report (to be implemented)");
        return;
    }

    if (!uuid || !newStatus) {
        return;
    }

    // Confirmation for completion
    if (newStatus === "completed") {
        if (
            !confirm(
                "Mark this appointment as completed? This will move it to the archive."
            )
        ) {
            return;
        }
    }

    // Confirmation for cancellation (but not for buttons with specific handlers)
    if (newStatus === "cancelled") {
        if (!confirm("Are you sure you want to cancel this appointment?")) {
            return;
        }
    }

    updateAppointmentStatus(uuid, newStatus);
});

// Global variable to store appointment UUID
let appointmentToCancel = null;

// Handle delete appointment (separate from cancel)
$(document).on("click", ".btn-delete", function (e) {
    e.preventDefault();
    e.stopPropagation();

    const uuid = $(this).data("uuid");
    const appointmentTitle = $(this)
        .closest(".appointment-card")
        .find("h4")
        .text();

    if (
        confirm(
            `Are you sure you want to permanently delete "${appointmentTitle}"? This action cannot be undone.`
        )
    ) {
        $.ajax({
            url: "/src/features/appointments/api/appointments.php",
            method: "POST",
            data: {
                action: "delete",
                uuid: uuid,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    getAllAppointments(); // Reload appointments
                } else {
                    alert("Failed to delete appointment: " + response.message);
                }
            },
            error: function () {
                alert("Failed to delete appointment");
            },
        });
    }
});

// Handle cancel appointment (keep existing logic but add preventDefault)
$(document).on("click", ".btn-cancel", function (e) {
    e.preventDefault();
    e.stopPropagation();

    appointmentToCancel = $(this).data("uuid");
    const appointmentTitle = $(this)
        .closest(".appointment-card")
        .find("h4")
        .text();

    // Clear previous reason
    $("#cancellationReason").val("");

    // Update modal title
    $("#cancellationModal .header").html(`
        <i class="times circle icon"></i>
        Cancel "${appointmentTitle}"
    `);

    // Show modal
    $("#cancellationModal").modal("show");
});

// Handle modal confirmation
$(document).on("click", "#confirmCancellation", function () {
    const reason = $("#cancellationReason").val().trim();

    if (!reason) {
        // Show validation message in modal, not alert
        $(".ui.form").addClass("error");
        return;
    }

    if (appointmentToCancel) {
        $.ajax({
            url: "/src/features/appointments/api/appointments.php",
            method: "POST",
            data: {
                action: "update_status",
                uuid: appointmentToCancel,
                status: "cancelled",
                cancellation_reason: reason,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#cancellationModal").modal("hide");
                    getAllAppointments(); // Reload appointments
                } else {
                    alert("Failed to cancel appointment: " + response.message);
                }
            },
            error: function () {
                alert("Failed to cancel appointment");
            },
        });
    }
});

// Function to open reschedule modal
function openRescheduleModal($card) {
    const uuid = $card.data("uuid");
    const petName = $card.find(".patient-info h4").text();
    const serviceName = $card
        .find(".appointment-service")
        .text()
        .replace("Service:", "")
        .trim();
    const currentDate = $card.find(".appointment-time").text().trim();

    // Populate modal with current appointment details
    $("#reschedule-uuid").val(uuid);
    $("#reschedule-pet-name").text(petName);
    $("#reschedule-service-name").text(serviceName);
    $("#reschedule-current-date").text(currentDate);

    // Set minimum date to today
    const today = new Date().toISOString().split("T")[0];
    $("#reschedule-new-date").attr("min", today);

    // Show modal
    $("#rescheduleModal").modal("show");
}

// Handle reschedule form submission
$(document).on("submit", "#rescheduleForm", function (e) {
    e.preventDefault();

    const uuid = $("#reschedule-uuid").val();
    const newDate = $("#reschedule-new-date").val();
    const reason = $("[name='reschedule_reason']").val();

    if (!newDate) {
        alert("Please select a new date.");
        return;
    }

    $.ajax({
        url: "/src/features/appointments/api/appointments.php",
        method: "POST",
        data: {
            action: "reschedule",
            uuid: uuid,
            new_date: newDate,
            reason: reason,
        },
        dataType: "json",
        success: function (response) {
            alert(response.message);
            if (response.success) {
                $("#rescheduleModal").modal("hide");
                getAllAppointments(); // Refresh the list
            }
        },
        error: function (xhr, status, error) {
            alert("Error rescheduling appointment: " + error);
        },
    });
});

// Separate function for status updates
function updateAppointmentStatus(uuid, status) {
    $.ajax({
        url: "/src/features/appointments/api/appointments.php",
        method: "POST",
        data: {
            action: "update_status",
            uuid: uuid,
            status: status,
        },
        dataType: "json",
        success: function (response) {
            alert(response.message);
            if (response.success) {
                getAllAppointments(); // Refresh the list
            }
        },
        error: function (xhr, status, error) {
            alert("AJAX error: " + error);
        },
    });
}

// Update cardHtml function with better button layout
function cardHtml(app, statusClass, statusLabel, isCompleted = false) {
    let actionButtons = "";
    let reasonInfo = "";

    // ADMIN SIDE: Only show when PATIENT cancelled
    if (
        app.status === "cancelled" &&
        app.note &&
        (app.note.includes("Cancelled by client") ||
            app.note.includes("Cancelled by patient")) // Check for both client and patient
    ) {
        reasonInfo = `
            <div class="reason-info patient-cancelled">
                <div class="alert">
                    ⚠️ <strong>Client Cancelled</strong>
                </div>
            </div>`;
    }

    // Remove all other reason display logic for admin side
    // (No admin cancellation reasons, no reschedule reasons shown to admin)

    if (isCompleted) {
        // Completed appointments show different actions
        actionButtons = `
            <button class="ui action-btn blue button view-details">
                <i class="eye icon"></i> View Details
            </button>
            <button class="ui action-btn teal button download-report">
                <i class="download icon"></i> Report
            </button>
        `;
    } else if (statusLabel === "Pending") {
        // Pending appointments
        actionButtons = `
            <button class="ui action-btn green button btn-confirm" data-uuid="${app.uuid}">
                <i class="check icon"></i> Confirm
            </button>
            <button class="ui action-btn blue button btn-reschedule" data-uuid="${app.uuid}">
                <i class="calendar icon"></i> Reschedule
            </button>
            <button class="ui action-btn red button btn-cancel" data-uuid="${app.uuid}">
                <i class="times icon"></i> Cancel
            </button>
        `;
    } else if (statusLabel === "Confirmed") {
        // Confirmed appointments can be completed, rescheduled, or cancelled
        actionButtons = `
            <button class="ui action-btn green button btn-complete" data-uuid="${app.uuid}">
                <i class="check icon"></i> Complete
            </button>
            <button class="ui action-btn blue button btn-reschedule" data-uuid="${app.uuid}">
                <i class="calendar icon"></i> Reschedule
            </button>
            <button class="ui action-btn red button btn-cancel" data-uuid="${app.uuid}">
                <i class="times icon"></i> Cancel
            </button>
        `;
    } else if (app.status === "cancelled") {
        // Only show delete button for cancelled appointments
        actionButtons = `
            <button class="ui action-btn red button btn-delete" data-uuid="${app.uuid}" title="Permanently delete this appointment">
                <i class="trash icon"></i> Delete
            </button>
        `;
    } else {
        // Default actions for other statuses
        actionButtons = `
            <button class="ui action-btn blue reschedule-btn button btn-reschedule" data-uuid="${app.uuid}">
                <i class="calendar icon"></i> Reschedule
            </button>
        `;
    }

    return `
    <div class="appointment-card" data-uuid="${app.uuid}">
        <div class="appointment-header">
            <div class="appointment-time">
                <i class="material-icons-sharp">schedule</i>
                ${app.date || ""}
            </div>
            <span class="appointment-status ${statusClass}">${statusLabel}</span>
        </div>
        <div class="appointment-patient">
            <div class="patient-avatar">
                <img src="${
                    app.pet_image || "/img/placeholders/image.png"
                }" alt="${
        app.pet_name || "Pet"
    }" onerror="this.src='/img/placeholders/image.png'">
            </div>
            <div class="patient-info">
                <h4>${app.pet_name || app.pet_uuid}</h4>
                <p>Owner: ${app.user_name || app.user_uuid}</p>
            </div>
        </div>
        <div class="appointment-service">
            <strong>Service:</strong> ${app.service_name || ""}
        </div>
        <div class="appointment-description">
            <strong>Instructions:</strong> ${getOriginalInstructions(app.note)}
        </div>
        ${reasonInfo}
        <div class="appointment-actions">
            ${actionButtons}
        </div>
    </div>
    `;
}

// Initialize modals when page loads
$(document).ready(function () {
    $("#cancellationModal").modal({
        closable: false,
        onApprove: function () {
            return false; // Prevent default approval
        },
    });
});

// Global variable to store appointment UUID for reschedule
let appointmentToReschedule = null;

// Handle reschedule appointment
$(document).on("click", ".btn-reschedule", function (e) {
    e.preventDefault();
    e.stopPropagation();

    appointmentToReschedule = $(this).data("uuid");
    const appointmentTitle = $(this)
        .closest(".appointment-card")
        .find("h4")
        .text();

    // Clear previous values
    $("#rescheduleDate").val("");
    $("#rescheduleReason").val("");

    // Update modal title
    $("#rescheduleModal .header").html(`
        <i class="calendar icon"></i>
        Reschedule "${appointmentTitle}"
    `);

    // Show modal
    $("#rescheduleModal").modal("show");
});

// Handle reschedule confirmation
$(document).on("click", "#confirmReschedule", function () {
    const newDate = $("#rescheduleDate").val().trim();
    const reason = $("#rescheduleReason").val().trim();

    if (!newDate) {
        alert("Please select a new date");
        return;
    }

    if (!reason) {
        alert("Please provide a reason for rescheduling");
        return;
    }

    if (appointmentToReschedule) {
        $.ajax({
            url: "/src/features/appointments/api/appointments.php",
            method: "POST",
            data: {
                action: "reschedule",
                uuid: appointmentToReschedule,
                new_date: newDate,
                reason: reason,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#rescheduleModal").modal("hide");
                    alert("Appointment rescheduled successfully");
                    getAllAppointments(); // Reload appointments
                } else {
                    alert(
                        "Failed to reschedule appointment: " + response.message
                    );
                }
            },
            error: function () {
                alert("Failed to reschedule appointment");
            },
        });
    }
});

// Initialize reschedule modal
$(document).ready(function () {
    $("#rescheduleModal").modal({
        closable: false,
        onApprove: function () {
            return false; // Prevent default approval
        },
    });
});

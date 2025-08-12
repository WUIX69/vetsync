// User Appointments Management
$(document).ready(function () {
    let userAppointments = [];

    // Load user appointments on page load
    loadUserAppointments();

    // Tab switching functionality
    $(".appointments-nav .nav-link").on("click", function (e) {
        e.preventDefault();

        // Update active state
        $(".appointments-nav .nav-link").removeClass("active");
        $(this).addClass("active");

        // Get filter type
        const filter = $(this).data("filter");
        renderAppointments(filter);
    });

    function loadUserAppointments() {
        $.ajax({
            url: "/src/features/appointments/api/user-appointments.php",
            method: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    userAppointments = response.data;
                    renderAppointments("all"); // Show all appointments by default
                } else {
                    showEmptyState();
                }
            },
            error: function () {
                showEmptyState();
                console.error("Failed to load appointments");
            },
        });
    }

    function renderAppointments(filter = "all") {
        const container = $(".appointments-list");
        container.empty();

        let filteredAppointments = userAppointments;

        // Filter appointments based on status
        if (filter !== "all") {
            filteredAppointments = userAppointments.filter((app) => {
                if (filter === "upcoming") {
                    return (
                        app.status === "pending" || app.status === "accepted"
                    );
                }
                return app.status === filter;
            });
        }

        if (filteredAppointments.length === 0) {
            showEmptyState();
            return;
        }

        filteredAppointments.forEach((appointment) => {
            const appointmentCard = createAppointmentCard(appointment);
            container.append(appointmentCard);
        });
    }

    function createAppointmentCard(appointment) {
        const statusInfo = getStatusInfo(appointment.status);
        const canCancel =
            appointment.status === "pending" ||
            appointment.status === "accepted";
        const canDelete = appointment.status === "cancelled";

        // USER SIDE: Show ONLY reschedule reasons when admin reschedules (but NOT for cancelled appointments)
        let reasonInfo = "";

        // Check for reschedule messages, but exclude cancelled appointments
        if (
            appointment.note &&
            appointment.note.includes("[RESCHEDULED BY ADMIN]") &&
            appointment.status !== "cancelled" // Add this condition
        ) {
            // Split by the marker and get the reason part
            const parts = appointment.note.split("[RESCHEDULED BY ADMIN]");
            if (parts.length > 1) {
                const reason = parts[1].trim() || "No reason provided";
                reasonInfo = `
                    <div class="reason-info reschedule-reason">
                        <div class="alert">
                            ⚠️ <strong>Reschedule Reason:</strong> ${reason}
                        </div>
                    </div>`;
            }
        }

        return `
            <div class="appointment-listing" data-uuid="${appointment.uuid}">
                <div class="appointment-header">
                    <h3>${appointment.service_name || "Service"}</h3>
                    <span class="appointment-status ${
                        statusInfo.class
                    }">${statusInfo.label}</span>
                </div>
                <div class="appointment-description">
                    ${getOriginalInstructions(appointment.note)}
                </div>
                ${reasonInfo}
                <div class="appointment-details">
                    <div class="detail-item">
                        <span class="emoji">📅</span>
                        <strong>Date:</strong> ${appointment.formatted_date}
                    </div>
                    <div class="detail-item">
                        <span class="emoji">🏥</span>
                        <strong>Service:</strong> ${appointment.service_name}
                    </div>
                    <div class="detail-item">
                        <span class="emoji">🐾</span>
                        <strong>Pet:</strong> ${
                            appointment.pet_name
                        }${appointment.pet_breed ? ` (${appointment.pet_breed})` : ""}
                    </div>
                </div>
                <div class="appointment-actions">
                    ${
                        canCancel
                            ? `<button class="btn btn-outline-danger btn-cancel" data-uuid="${appointment.uuid}">
                        <span class="emoji">❌</span> Cancel
                    </button>`
                            : ""
                    }
                    ${
                        canDelete
                            ? `<button class="btn btn-outline-secondary btn-delete" data-uuid="${appointment.uuid}" title="Remove from your appointments">
                        <span class="emoji">🗑️</span> Remove
                    </button>`
                            : ""
                    }
                </div>
            </div>
        `;
    }

    function getStatusInfo(status) {
        const statusMap = {
            pending: { class: "status-upcoming", label: "Pending" },
            accepted: { class: "status-upcoming", label: "Confirmed" },
            completed: { class: "status-completed", label: "Completed" },
            cancelled: { class: "status-cancelled", label: "Cancelled" },
        };
        return (
            statusMap[status] || { class: "status-upcoming", label: "Unknown" }
        );
    }

    function showEmptyState() {
        $(".appointments-list").html(`
            <div class="empty-state text-center py-5">
                <span class="emoji" style="font-size: 4rem;">📅</span>
                <h3>No Appointments Found</h3>
                <p class="text-muted">You don't have any appointments yet. Book your first appointment!</p>
                <a href="/src/app/user/services.php" class="btn btn-primary">
                    <span class="emoji">📅</span> Book Appointment
                </a>
            </div>
        `);
    }

    // Handle cancel appointment (user side)
    $(document).on("click", ".btn-cancel", function () {
        const uuid = $(this).data("uuid");
        const appointmentTitle = $(this)
            .closest(".appointment-listing")
            .find("h3")
            .text();

        if (confirm(`Are you sure you want to cancel "${appointmentTitle}"?`)) {
            $.ajax({
                url: "/src/features/appointments/api/user-appointments.php",
                method: "POST",
                data: {
                    action: "cancel",
                    uuid: uuid,
                    cancellation_reason:
                        "Cancelled by patient - no reason provided",
                },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        alert("Appointment cancelled successfully");
                        loadUserAppointments(); // Reload appointments
                    } else {
                        alert(
                            "Failed to cancel appointment: " + response.message
                        );
                    }
                },
                error: function () {
                    alert("Failed to cancel appointment");
                },
            });
        }
    });

    // Add delete handler for users
    $(document).on("click", ".btn-delete", function () {
        const uuid = $(this).data("uuid");
        const appointmentTitle = $(this)
            .closest(".appointment-listing")
            .find("h3")
            .text();

        if (
            confirm(
                `Are you sure you want to remove "${appointmentTitle}" from your appointments? This action cannot be undone.`
            )
        ) {
            $.ajax({
                url: "/src/features/appointments/api/user-appointments.php",
                method: "POST",
                data: {
                    action: "delete",
                    uuid: uuid,
                },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        loadUserAppointments(); // Reload appointments
                    } else {
                        alert(
                            "Failed to remove appointment: " + response.message
                        );
                    }
                },
                error: function () {
                    alert("Failed to remove appointment");
                },
            });
        }
    });

    // Helper function to extract original user instructions from note
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
});

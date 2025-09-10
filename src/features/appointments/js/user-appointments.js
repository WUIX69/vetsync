// User Appointments Management
$(document).ready(function () {
    let userAppointments = [];
    let hiddenAppointments = JSON.parse(
        localStorage.getItem("hiddenAppointments") || "[]"
    );

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

        let filteredAppointments = userAppointments.filter(
            (app) =>
                // Don't show appointments that user has hidden
                !hiddenAppointments.includes(app.uuid)
        );

        // Sort appointments by date (newest first)
        filteredAppointments.sort((a, b) => {
            // Try to parse the date in multiple formats
            const dateA = new Date(
                a.date.replace(/(\d{2})\/(\d{2})\/(\d{4})/, "$3-$2-$1")
            ).getTime();
            const dateB = new Date(
                b.date.replace(/(\d{2})\/(\d{2})\/(\d{4})/, "$3-$2-$1")
            ).getTime();

            if (dateA === dateB) {
                // If dates are the same, sort by created_at (newest first)
                const createdAtA = new Date(a.created_at).getTime();
                const createdAtB = new Date(b.created_at).getTime();
                return createdAtB - createdAtA;
            }

            return dateB - dateA; // Sort by appointment date (newest first)
        });

        // Filter appointments based on status
        if (filter !== "all") {
            filteredAppointments = filteredAppointments.filter((app) => {
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

        // USER SIDE: Show both admin cancellation and reschedule reasons
        let reasonInfo = "";

        // Check for reschedule messages
        if (
            appointment.note &&
            appointment.note.includes("[RESCHEDULED BY ADMIN]") &&
            appointment.status !== "cancelled"
        ) {
            const rescheduleReason = extractLatestRescheduleReason(
                appointment.note
            );
            if (rescheduleReason) {
                reasonInfo = `
                    <div class="reason-info reschedule-reason">
                        <div class="alert">
                            ⚠️ <strong>Reschedule Reason:</strong> ${rescheduleReason}
                        </div>
                    </div>`;
            }
        }

        // Update the cancellation message check
        if (!reasonInfo && appointment.note) {
            if (appointment.note.includes("[CANCELLED BY ADMIN]")) {
                const parts = appointment.note.split("[CANCELLED BY ADMIN]");
                if (parts.length > 1) {
                    const reason =
                        parts[1].split("[")[0].trim() || "No reason provided";
                    if (!reason.toLowerCase().includes("cancelled by client")) {
                        reasonInfo = `
                            <div class="reason-info cancellation-reason">
                                <div class="alert">
                                    ❌ <strong>Cancelled by Admin:</strong> ${reason}
                                </div>
                            </div>`;
                    }
                }
            } else if (appointment.status === "cancelled") {
                // This is a client cancellation
                reasonInfo = `
                    <div class="reason-info cancellation-reason">
                        <div class="alert">
                            ❌ <strong>Cancelled by Client</strong>
                        </div>
                    </div>`;
            }
        }

        // Don't show user's own cancellation reason
        // But DO show admin cancellation reasons

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
                        "Cancelled by client - no reason provided",
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
                `Are you sure you want to remove "${appointmentTitle}" from your appointments? You won't see this appointment anymore.`
            )
        ) {
            // Add to hidden appointments in localStorage
            hiddenAppointments.push(uuid);
            localStorage.setItem(
                "hiddenAppointments",
                JSON.stringify(hiddenAppointments)
            );

            // Refresh the display
            renderAppointments(
                $(".appointments-nav .nav-link.active").data("filter")
            );
        }
    });

    // Helper function to extract original user instructions from note
    function getOriginalInstructions(note) {
        if (!note) {
            return "No special instructions provided.";
        }

        // Get the original note (everything before any admin messages)
        let originalNote = note;

        // Remove all admin messages
        const adminMessages = [
            "[CANCELLED BY ADMIN]",
            "[RESCHEDULED BY ADMIN]",
        ];

        for (const message of adminMessages) {
            if (originalNote.includes(message)) {
                originalNote = originalNote.split(message)[0];
            }
        }

        // Clean up whitespace and newlines
        originalNote = originalNote.trim();

        return originalNote || "No special instructions provided.";
    }

    // Helper function to extract the latest reschedule reason from note
    function extractLatestRescheduleReason(note) {
        if (!note || !note.includes("[RESCHEDULED BY ADMIN]")) {
            return null;
        }

        // Split by [RESCHEDULED BY ADMIN] and get the last occurrence
        const parts = note.split("[RESCHEDULED BY ADMIN]");
        if (parts.length > 1) {
            // Get the last reschedule reason (most recent)
            const lastReschedule = parts[parts.length - 1].trim();

            // Extract just the reason part (before the timestamp)
            // Format: "reason - 2024-01-15 14:30:00"
            const reasonMatch = lastReschedule.match(
                /^(.+?)\s*-\s*\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}:\d{2}$/
            );
            if (reasonMatch) {
                return reasonMatch[1].trim();
            }

            // Fallback: return the whole text if no timestamp format found
            return lastReschedule;
        }
        return null;
    }
});

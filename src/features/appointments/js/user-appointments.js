// User Appointments Management
// Global variables
let userAppointments = [];
let hiddenAppointments = JSON.parse(
    localStorage.getItem("hiddenAppointments") || "[]"
);

// Global functions
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
            !hiddenAppointments.includes(app.uuid) &&
            (filter === "all" ||
                (filter === "pending" && app.status === "pending") ||
                (filter === "confirmed" && app.status === "accepted") ||
                (filter === "completed" && app.status === "completed") ||
                (filter === "cancelled" && app.status === "cancelled"))
    );

    if (filteredAppointments.length === 0) {
        showEmptyState();
        return;
    }

    // Group appointments by booking_group_id
    const grouped = {};
    filteredAppointments.forEach((appointment) => {
        const groupId = appointment.booking_group_id || appointment.uuid;
        if (!grouped[groupId]) {
            grouped[groupId] = [];
        }
        grouped[groupId].push(appointment);
    });

    // Render each group
    Object.values(grouped).forEach((appointmentGroup) => {
        const appointmentCard = createAppointmentGroupCard(appointmentGroup);
        container.append(appointmentCard);
    });
}

function createAppointmentCard(appointment) {
    const statusInfo = getStatusInfo(appointment.status);
    const canCancel =
        appointment.status === "pending" || appointment.status === "accepted";
    const canDelete = appointment.status === "cancelled";
    const canRate = appointment.status === "completed";

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

    // NEW: Check if user has already reviewed this appointment
    const hasReviewed = appointment.review_id ? true : false;

    return `
        <div class="appointment-listing" data-uuid="${appointment.uuid}">
            <div class="appointment-header">
                <h3>${appointment.service_name || "Service"}</h3>
                <span class="appointment-status ${statusInfo.class}">${
        statusInfo.label
    }</span>
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
                    <span class="emoji">⏰</span>
                    <strong>Time:</strong> ${
                        appointment.formatted_time || "No time set"
                    }
                </div>
                <div class="detail-item">
                    <span class="emoji">🏥</span>
                    <strong>Service:</strong> ${appointment.service_name}
                </div>
                <div class="detail-item">
                    <span class="emoji">🐾</span>
                    <strong>Pet:</strong> ${appointment.pet_name}${
        appointment.pet_breed ? ` (${appointment.pet_breed})` : ""
    }
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
                ${
                    canRate && !hasReviewed
                        ? `<button class="btn btn-primary btn-rate" data-uuid="${
                              appointment.uuid
                          }" data-service-uuid="${
                              appointment.service_uuid || ""
                          }" data-service-name="${
                              appointment.service_name || "Custom Service"
                          }">
                    <span class="emoji">⭐</span> Rate Us
                </button>`
                        : ""
                }
                ${
                    canRate && hasReviewed
                        ? `<button class="btn btn-success" disabled title="Already reviewed">
                    <span class="emoji">✅</span> Reviewed
                </button>`
                        : ""
                }
            </div>
        </div>
    `;
}

function createAppointmentGroupCard(appointmentGroup) {
    // If it's a single appointment, use the regular card
    if (appointmentGroup.length === 1) {
        return createAppointmentCard(appointmentGroup[0]);
    }

    // Multiple appointments booked together
    const firstAppointment = appointmentGroup[0];
    const statusInfo = getStatusInfo(firstAppointment.status);
    const canCancel =
        firstAppointment.status === "pending" ||
        firstAppointment.status === "accepted";
    const canDelete = firstAppointment.status === "cancelled";

    // Collect all service names
    const serviceNames = appointmentGroup
        .map((app) => app.service_name || "Custom Service")
        .join(", ");
    const serviceCount = appointmentGroup.length;

    return `
        <div class="appointment-listing appointment-group" data-group-id="${
            firstAppointment.booking_group_id
        }">
            <div class="appointment-header">
                <h3>
                    <span class="ui label teal">Group Booking</span>
                    ${serviceCount} Services
                </h3>
                <span class="appointment-status ${statusInfo.class}">${
        statusInfo.label
    }</span>
            </div>
            <div class="appointment-description">
                ${getOriginalInstructions(firstAppointment.note)}
            </div>
            <div class="appointment-details">
                <div class="detail-item">
                    <span class="emoji">📅</span>
                    <strong>Date:</strong> ${firstAppointment.formatted_date}
                </div>
                <div class="detail-item">
                    <span class="emoji">⏰</span>
                    <strong>Time:</strong> ${
                        firstAppointment.formatted_time || "No time set"
                    }
                </div>
                <div class="detail-item">
                    <span class="emoji">🏥</span>
                    <strong>Services:</strong> ${serviceNames}
                </div>
                <div class="detail-item">
                    <span class="emoji">🐾</span>
                    <strong>Pet:</strong> ${firstAppointment.pet_name}${
        firstAppointment.pet_breed ? ` (${firstAppointment.pet_breed})` : ""
    }
                </div>
            </div>
            <div class="appointment-services-list" style="margin: 1rem 0; padding: 1rem; background: #f9f9f9; border-radius: 8px;">
                <strong style="display: block; margin-bottom: 0.5rem;">📋 Services in this booking:</strong>
                ${appointmentGroup
                    .map(
                        (app, index) => `
                    <div style="padding: 0.5rem 0; border-bottom: ${
                        index < appointmentGroup.length - 1
                            ? "1px solid #e0e0e0"
                            : "none"
                    };">
                        ${index + 1}. ${app.service_name || "Custom Service"}
                    </div>
                `
                    )
                    .join("")}
            </div>
            <div class="appointment-actions">
                ${
                    canCancel
                        ? appointmentGroup
                              .map(
                                  (app) => `
                    <button class="btn btn-outline-danger btn-cancel" data-uuid="${app.uuid}" style="margin: 0.25rem;">
                        <span class="emoji">❌</span> Cancel ${app.service_name}
                    </button>
                `
                              )
                              .join("")
                        : ""
                }
                ${
                    canDelete
                        ? `
                    <button class="btn btn-outline-secondary btn-delete-group" data-group-id="${firstAppointment.booking_group_id}">
                        <span class="emoji">🗑️</span> Remove All
                    </button>
                `
                        : ""
                }
            </div>
        </div>
    `;
}

function getStatusInfo(status) {
    const statusMap = {
        pending: { label: "Pending", class: "pending" },
        accepted: { label: "Accepted", class: "accepted" },
        completed: { label: "Completed", class: "completed" },
        cancelled: { label: "Cancelled", class: "cancelled" },
    };
    return statusMap[status] || { label: status, class: "unknown" };
}

function getOriginalInstructions(note) {
    if (!note) return "No special instructions provided.";

    // Extract only the original instructions (before any admin modifications)
    const parts = note.split("[");
    return parts[0].trim() || "No special instructions provided.";
}

function extractLatestRescheduleReason(note) {
    if (note && note.includes("[RESCHEDULED BY ADMIN]")) {
        // Find all reschedule sections
        const rescheduleMatches = note.match(
            /\[RESCHEDULED BY ADMIN\][^[]*(?:\[[^\]]*\])?/g
        );

        if (rescheduleMatches && rescheduleMatches.length > 0) {
            // Get the last reschedule entry
            const lastReschedule = rescheduleMatches[
                rescheduleMatches.length - 1
            ]
                .replace("[RESCHEDULED BY ADMIN]", "")
                .replace(/\[[^\]]*\]/g, "") // Remove any timestamp brackets
                .trim();

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
    return null;
}

function showEmptyState() {
    const container = $(".appointments-list");
    container.html(`
        <div class="empty-state">
            <div class="empty-icon">📅</div>
            <h3>No Appointments Found</h3>
            <p>You don't have any appointments matching the current filter.</p>
        </div>
    `);
}

// Document ready function with event handlers
$(document).ready(function () {
    // Load user appointments on page load
    loadUserAppointments();

    // Tab switching functionality
    $(".appointments-nav .nav-link").on("click", function (e) {
        e.preventDefault();

        // Update active state
        $(".appointments-nav .nav-link").removeClass("active");
        $(this).addClass("active");

        // Get filter value and render appointments
        const filter = $(this).data("filter");
        renderAppointments(filter);
    });

    // NEW: Handle rate button clicks
    $(document).on("click", ".btn-rate", function () {
        const appointmentUuid = $(this).data("uuid");
        const serviceUuid = $(this).data("service-uuid");
        const serviceName = $(this).data("service-name");

        // Redirect to service page with review focus, or show rating modal
        if (serviceUuid) {
            // Redirect to service single view with review section focus
            window.location.href = `/src/app/user/service-single-view.php?uuid=${serviceUuid}&review=true#reviews-section`;
        } else {
            // For custom services, show a simple rating modal
            showCustomServiceRatingModal(appointmentUuid, serviceName);
        }
    });

    // Handle cancel button clicks
    $(document).on("click", ".btn-cancel", function () {
        const uuid = $(this).data("uuid");
        if (confirm("Are you sure you want to cancel this appointment?")) {
            cancelAppointment(uuid);
        }
    });

    // Handle delete button clicks
    $(document).on("click", ".btn-delete", function () {
        const uuid = $(this).data("uuid");
        if (
            confirm(
                "Are you sure you want to remove this appointment from your list?"
            )
        ) {
            hideAppointment(uuid);
        }
    });
});

// Additional helper functions
function showCustomServiceRatingModal(appointmentUuid, serviceName) {
    // Implementation for custom service rating modal
    alert(`Rating feature for custom service "${serviceName}" coming soon!`);
}

function cancelAppointment(uuid) {
    $.ajax({
        url: "/src/features/appointments/api/user-appointments.php",
        method: "POST",
        data: {
            action: "cancel",
            uuid: uuid,
        },
        success: function (response) {
            if (response.success) {
                alert("Appointment cancelled successfully.");
                // Reload appointments
                loadUserAppointments();
            } else {
                // Show detailed error message
                alert(response.message || "Failed to cancel appointment");
            }
        },
        error: function () {
            alert("Error cancelling appointment. Please try again.");
        },
    });
}

function hideAppointment(uuid) {
    // Add to hidden appointments
    hiddenAppointments.push(uuid);
    localStorage.setItem(
        "hiddenAppointments",
        JSON.stringify(hiddenAppointments)
    );

    // Re-render appointments
    renderAppointments($(".appointments-nav .nav-link.active").data("filter"));
}

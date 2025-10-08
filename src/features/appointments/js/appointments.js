// Date validation notification function for appointments
function showAppointmentDateNotification(type, title, message) {
    // Create notification element
    const notification = $(`
        <div class="ui ${type} message appointment-date-notification" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <div class="header">
                <i class="calendar times icon"></i>
                ${title}
            </div>
            <p>${message}</p>
            <button class="ui mini button" onclick="$(this).closest('.appointment-date-notification').fadeOut()">
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

// Real-time date validation for reschedule
function validateRescheduleDate(inputElement) {
    const selectedDate = $(inputElement).val();
    if (selectedDate) {
        const today = new Date();
        const appointmentDate = new Date(selectedDate);
        today.setHours(0, 0, 0, 0);
        appointmentDate.setHours(0, 0, 0, 0);

        if (appointmentDate < today) {
            showAppointmentDateNotification(
                "error",
                "Past Date Selected",
                "You cannot reschedule an appointment to a past date. Please select today or a future date."
            );
            $(inputElement).val(""); // Clear the invalid date
            return false;
        } else {
            // Show success notification for valid future date
            showAppointmentDateNotification(
                "success",
                "Reschedule Date Selected",
                "Great! You have selected a valid reschedule date."
            );
        }
    }
    return true;
}

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

            // Clear all table bodies instead of card containers
            $(
                "#appointmentsTableAll tbody, #appointmentsTablePending tbody, #appointmentsTableConfirmed tbody, #appointmentsTableCompleted tbody, #appointmentsTableCancelled tbody"
            ).empty();

            // Separate arrays for each status
            const allAppointments = [];
            const pendingAppointments = [];
            const confirmedAppointments = [];
            const completedAppointments = [];
            const cancelledAppointments = [];

            response.data.forEach(function (app) {
                let statusClass = "";
                let statusLabel = "";

                // Map DB status to UI
                if (app.status === "pending") {
                    statusClass = "pending";
                    statusLabel = "Pending";
                    pendingAppointments.push({ app, statusClass, statusLabel });
                } else if (app.status === "accepted") {
                    statusClass = "confirmed";
                    statusLabel = "Confirmed";
                    confirmedAppointments.push({
                        app,
                        statusClass,
                        statusLabel,
                    });
                } else if (app.status === "completed") {
                    statusClass = "completed";
                    statusLabel = "Completed";
                    completedAppointments.push({
                        app,
                        statusClass,
                        statusLabel,
                        isCompleted: true,
                    });
                } else if (app.status === "cancelled") {
                    statusClass = "cancelled";
                    statusLabel = "Cancelled";
                    cancelledAppointments.push({
                        app,
                        statusClass,
                        statusLabel,
                    });
                }

                // Add to all appointments array
                allAppointments.push({
                    app,
                    statusClass,
                    statusLabel,
                    isCompleted: app.status === "completed",
                });
            });

            // Render appointments in their respective tables
            renderAppointments(
                allAppointments.map((item) => item.app),
                "all"
            );
            renderAppointments(
                pendingAppointments.map((item) => item.app),
                "pending"
            );
            renderAppointments(
                confirmedAppointments.map((item) => item.app),
                "confirmed"
            );
            renderAppointments(
                completedAppointments.map((item) => item.app),
                "completed"
            );
            renderAppointments(
                cancelledAppointments.map((item) => item.app),
                "cancelled"
            );
        },
        error: function (xhr, status, error) {
            console.error("Failed to load appointments:", error);
            alert("Failed to load appointments. Please try again.");
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
    if (newStatus === "accepted") {
        if (
            !confirm(
                "Mark this appointment as confirmed? This will move it to the confirmed list."
            )
        ) {
            return;
        }
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

    // Add real-time date validation for reschedule
    $("#reschedule-new-date")
        .off("change")
        .on("change", function () {
            validateRescheduleDate(this);
        });

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
        showAppointmentDateNotification(
            "error",
            "Date Required",
            "Please select a new date for rescheduling."
        );
        return;
    }

    // Validate date before submission
    const today = new Date();
    const appointmentDate = new Date(newDate);
    today.setHours(0, 0, 0, 0);
    appointmentDate.setHours(0, 0, 0, 0);

    if (appointmentDate < today) {
        showAppointmentDateNotification(
            "error",
            "Past Date Selected",
            "You cannot reschedule an appointment to a past date. Please select today or a future date."
        );
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

// Update tableRowHtml function to handle different tab layouts
function tableRowHtml(
    app,
    statusClass,
    statusLabel,
    isCompleted = false,
    tabType = "all",
    groupInfo = { isGrouped: false }
) {
    let actionButtons = "";

    if (isCompleted) {
        actionButtons = `
            <div class="action-buttons">
                <button class="btn btn-xs btn-success download-report" data-uuid="${app.uuid}">
                    Report
            </button>
            </div>
        `;
    } else if (statusLabel === "Pending") {
        actionButtons = `
            <div class="ui compact menu">
                <div class="ui simple dropdown item">
                    Actions
                    <i class="dropdown icon"></i>
                    <div class="menu">
                        <div class="item confirm-appointment" data-uuid="${app.uuid}">
                            <i class="check green icon"></i>
                            Confirm
                        </div>
                        <div class="item reschedule-appointment" data-uuid="${app.uuid}">
                            <i class="calendar orange icon"></i>
                            Reschedule
                        </div>
                        <div class="divider"></div>
                        <div class="item cancel-appointment" data-uuid="${app.uuid}">
                            <i class="times red icon"></i>
                            Cancel
                        </div>
                    </div>
                </div>
            </div>
        `;
    } else if (statusLabel === "Confirmed") {
        actionButtons = `
            <div class="ui compact menu">
                <div class="ui simple dropdown item">
                    Actions
                    <i class="dropdown icon"></i>
                    <div class="menu">
                        <div class="item complete-appointment" data-uuid="${app.uuid}">
                            <i class="check green icon"></i>
                            Complete
                        </div>
                        <div class="item reschedule-appointment" data-uuid="${app.uuid}">
                            <i class="calendar orange icon"></i>
                            Reschedule
                        </div>
                        <div class="divider"></div>
                        <div class="item cancel-appointment" data-uuid="${app.uuid}">
                            <i class="times red icon"></i>
                            Cancel
                        </div>
                    </div>
                </div>
            </div>
        `;
    } else if (statusLabel === "Cancelled") {
        actionButtons = `
            <div class="action-buttons">
                <button class="btn btn-xs btn-primary view-details" data-uuid="${app.uuid}">
                    View
            </button>
                <button class="btn btn-xs btn-secondary delete-appointment" data-uuid="${app.uuid}">
                    Delete
                </button>
            </div>
        `;
    } else {
        actionButtons = `
            <div class="action-buttons">
                <button class="btn btn-xs btn-primary view-details" data-uuid="${app.uuid}">
                    View
            </button>
            </div>
        `;
    }

    // Handle custom service display
    const serviceName =
        app.service_name ||
        (app.note && app.note.includes("CUSTOM SERVICE REQUEST:")
            ? "Custom Service"
            : "N/A");

    // Get instructions/notes with proper handling for custom services
    const instructions = getOriginalInstructions(app.note);
    const shortInstructions =
        instructions.length > 80 // Increased from 50 to 80
            ? instructions.substring(0, 80) + "..."
            : instructions;

    // Status badge (only show in "all" tab, other tabs are filtered by status)
    const statusBadge =
        tabType === "all"
            ? `<td><span class="badge bg-${getStatusColor(
                  statusClass
              )}">${statusLabel}</span></td>`
            : "";

    // Reason column (only for cancelled tab)
    const reasonColumn =
        tabType === "cancelled"
            ? `<td>
            <small class="text-muted">
                ${
                    app.note && app.note.includes("Cancelled")
                        ? app.note.replace(/^.*Cancelled[^:]*:\\s*/, "")
                        : "No reason provided"
                }
            </small>
        </td>`
            : "";

    // Group styling classes and badge
    let groupClasses = "";
    let groupBadge = "";

    if (groupInfo.isGrouped && groupInfo.isCollapsed) {
        const additionalCount = groupInfo.groupSize - 1;
        groupClasses = "group-collapsed cursor-pointer";
        groupBadge = `
                <span class="text-primary ms-2" style="font-size: 0.85rem; cursor: pointer;">
                    <i class="bx bx-chevron-right group-expand-icon"></i>
                    + ${additionalCount} more service${
            additionalCount > 1 ? "s" : ""
        }
                </span>
            `;

        // Note: data-group-id will be added directly to the <tr> tag below
    }

    const groupDataAttr =
        groupInfo.isGrouped && groupInfo.isCollapsed
            ? `data-group-id="${groupInfo.groupId}"`
            : "";

    return `
    <tr data-uuid="${app.uuid}" class="${groupClasses}" ${groupDataAttr}>
            <td>
                <div class="fw-bold">${
                    app.formatted_date || app.date || ""
                }</div>
                <small class="text-muted">ID: ${app.uuid.substring(
                    0,
                    8
                )}...</small>
            </td>
            <td>
                <div class="fw-bold">${
                    app.formatted_time || "No time set"
                }</div>
            </td>
            <td>
                <div class="d-flex align-items-center">
                <img src="${
                    app.pet_image || "/public/img/placeholders/image.png"
                }" 
                         alt="Pet" 
                         class="rounded-circle me-2" 
                         style="width: 32px; height: 32px; object-fit: cover;"
                         onerror="this.src='/public/img/placeholders/image.png'">
                    <div>
                        <div class="fw-bold">${
                            app.pet_name || "Unknown Pet"
                        }</div>
                        <small class="text-muted">ID: ${
                            app.pet_uuid ? app.pet_uuid.substring(0, 8) : "N/A"
                        }</small>
            </div>
            </div>
            </td>
            <td>
                <div>${app.user_name || app.user_uuid}</div>
                <small class="text-muted">${app.user_email || ""}</small>
            </td>
            <td>
                <div class="fw-bold">${serviceName}${groupBadge}</div>
                ${
                    shortInstructions
                        ? `<small class="text-muted" title="${instructions}">${shortInstructions}</small>`
                        : ""
                }
            </td>
            ${reasonColumn}
            ${statusBadge}
            <td>${actionButtons}</td>
        </tr>
    `;
}

// Helper function to get status color for badges
function getStatusColor(statusClass) {
    switch (statusClass) {
        case "pending":
            return "warning";
        case "confirmed":
            return "info";
        case "completed":
            return "success";
        case "cancelled":
            return "danger";
        default:
            return "secondary";
    }
}

// Update the renderAppointments function
function renderAppointments(data, status = "all") {
    let tableSelector = "";
    let colspan = "7";

    switch (status) {
        case "all":
            tableSelector = "#appointmentsTableAll tbody";
            colspan = "7";
            break;
        case "pending":
            tableSelector = "#appointmentsTablePending tbody";
            colspan = "6";
            break;
        case "confirmed":
            tableSelector = "#appointmentsTableConfirmed tbody";
            colspan = "6";
            break;
        case "completed":
            tableSelector = "#appointmentsTableCompleted tbody";
            colspan = "6";
            break;
        case "cancelled":
            tableSelector = "#appointmentsTableCancelled tbody";
            colspan = "7";
            break;
    }

    const container = $(tableSelector);
    container.empty();

    if (!data || data.length === 0) {
        container.append(`
            <tr>
                <td colspan="${colspan}" class="text-center py-4">
                    <div class="text-muted">
                        <i class="bx bx-calendar-x" style="font-size: 3rem;"></i>
                        <p class="mt-2">No ${
                            status === "all" ? "" : status
                        } appointments found</p>
                    </div>
                </td>
            </tr>
        `);
        return;
    }

    // Group appointments by booking_group_id
    const groupedAppointments = {};
    const standaloneAppointments = [];
    const processedGroups = new Set();

    data.forEach((appointment) => {
        if (appointment.booking_group_id) {
            if (!groupedAppointments[appointment.booking_group_id]) {
                groupedAppointments[appointment.booking_group_id] = [];
            }
            groupedAppointments[appointment.booking_group_id].push(appointment);
        } else {
            standaloneAppointments.push(appointment);
        }
    });

    // Render all appointments in order, but collapse groups
    data.forEach((appointment) => {
        const statusInfo = getStatusInfo(appointment.status);

        // Check if this is part of a group
        if (
            appointment.booking_group_id &&
            groupedAppointments[appointment.booking_group_id]?.length > 1
        ) {
            const groupId = appointment.booking_group_id;

            // Only render the first appointment of the group
            if (processedGroups.has(groupId)) {
                return; // Skip, already rendered
            }

            processedGroups.add(groupId);
            const group = groupedAppointments[groupId];

            // Render collapsed group row
            const groupRow = tableRowHtml(
                appointment,
                statusInfo.class,
                statusInfo.label,
                appointment.status === "completed",
                status,
                {
                    isGrouped: true,
                    isCollapsed: true,
                    groupSize: group.length,
                    groupId: groupId,
                    groupData: group,
                }
            );
            container.append(groupRow);
        } else {
            // Render standalone appointment normally
            const row = tableRowHtml(
                appointment,
                statusInfo.class,
                statusInfo.label,
                appointment.status === "completed",
                status,
                { isGrouped: false }
            );
            container.append(row);
        }
    });
}

// Helper function to get status info
function getStatusInfo(status) {
    switch (status) {
        case "pending":
            return { class: "pending", label: "Pending" };
        case "accepted":
            return { class: "confirmed", label: "Confirmed" };
        case "completed":
            return { class: "completed", label: "Completed" };
        case "cancelled":
            return { class: "cancelled", label: "Cancelled" };
        default:
            return { class: "secondary", label: "Unknown" };
    }
}

// Add search functionality
$(document).ready(function () {
    // Simple table search
    $("#searchTable").on("keyup", function () {
        const value = $(this).val().toLowerCase();
        $("#appointmentsTableAll tbody tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});

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

// Add this to ensure Bootstrap pills work properly
$(document).ready(function () {
    // Initialize Bootstrap nav pills if needed
    if (typeof bootstrap !== "undefined") {
        const triggerTabList = [].slice.call(
            document.querySelectorAll('[data-bs-toggle="pill"]')
        );
        triggerTabList.forEach(function (triggerEl) {
            new bootstrap.Tab(triggerEl);
        });
    }

    // Replace the Bootstrap pill handler with this simple version
    $(".nav-btn").on("click", function (e) {
        e.preventDefault();

        // Remove active class from all buttons
        $(".nav-btn").removeClass("active");

        // Add active class to clicked button
        $(this).addClass("active");

        // Hide all tab panes
        $(".tab-pane").removeClass("show active");

        // Show target tab pane
        const target = $(this).attr("data-target");
        $(target).addClass("show active");
    });
});

// Handle report modal
$(document).on("click", ".download-report", function (e) {
    e.preventDefault();
    const uuid = $(this).data("uuid");

    if (!uuid) {
        alert("Error: Unable to identify appointment");
        return;
    }

    // Show modal and loading state
    $("#reportModal").modal("show");
    $("#reportLoading").show();
    $("#reportContent").hide();
    $("#reportError").hide();

    // Make AJAX request to the main appointments API
    $.ajax({
        url: "/src/features/appointments/api/appointments.php",
        method: "POST",
        data: {
            action: "get_report_data",
            uuid: uuid,
        },
        dataType: "json",
        success: function (response) {
            if (response.success && response.data) {
                populateReportModal(response.data);
                $("#reportLoading").hide();
                $("#reportContent").show();
            } else {
                showReportError(response.message || "Unknown error occurred");
            }
        },
        error: function (xhr, status, error) {
            console.error("Report error:", error);
            console.error("Response text:", xhr.responseText);
            showReportError("Error loading report: " + error);
        },
    });
});

// Function to populate the report modal with data
function populateReportModal(data) {
    // Appointment details
    $("#reportAppointmentId").text("ID: " + data.uuid);
    $("#reportDate").text(formatDate(data.appointment_date));
    $("#reportStatus").html(
        `<span class="ui ${getStatusClass(data.status)} label">${
            data.status.charAt(0).toUpperCase() + data.status.slice(1)
        }</span>`
    );

    // Pet information
    $("#reportPetName").text(data.pet_name || "N/A");
    $("#reportPetSpecies").text(data.pet_species || "N/A");
    $("#reportPetBreed").text(data.pet_breed || "N/A");
    $("#reportPetAge").text(data.pet_age || "N/A");

    // Owner information
    $("#reportOwnerName").text(data.owner_name || "N/A");
    $("#reportOwnerEmail").text(data.owner_email || "N/A");
    $("#reportOwnerPhone").text(data.owner_phone || "N/A");

    // Service information
    $("#reportServiceName").text(data.service_name || "N/A");
    if (data.service_description) {
        $("#reportServiceDescription").text(data.service_description);
        $("#reportServiceDescriptionItem").show();
    } else {
        $("#reportServiceDescriptionItem").hide();
    }

    // Notes and instructions
    if (data.note && data.note.trim() !== "") {
        let noteText = data.note;

        // Handle custom service requests
        if (noteText.includes("CUSTOM SERVICE REQUEST:")) {
            noteText = noteText.replace(
                "CUSTOM SERVICE REQUEST:",
                "Custom Service Request:"
            );
        }

        $("#reportNotes").text(noteText);
        $("#reportNotesSection").show();
    } else {
        $("#reportNotesSection").hide();
    }

    // Generated date
    $("#reportGeneratedDate").text(new Date().toLocaleString());
}

// Function to show report error
function showReportError(message) {
    $("#reportLoading").hide();
    $("#reportContent").hide();
    $("#reportErrorMessage").text(message);
    $("#reportError").show();
}

// Function to get status class for styling
function getStatusClass(status) {
    switch (status.toLowerCase()) {
        case "pending":
            return "yellow";
        case "confirmed":
            return "blue";
        case "completed":
            return "green";
        case "cancelled":
            return "red";
        default:
            return "grey";
    }
}

// Function to format date
function formatDate(dateString) {
    const options = {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    };
    return new Date(dateString).toLocaleDateString(undefined, options);
}

// Handle print report
$(document).on("click", "#printReport", function () {
    const printContent = $("#reportContent").html();
    const printWindow = window.open("", "_blank");
    printWindow.document.write(`
        <html>
            <head>
                <title>VetSync - Appointment Report</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .ui.header { margin-bottom: 10px; font-weight: bold; }
                    .ui.list .item { margin-bottom: 5px; }
                    .ui.segment { border: 1px solid #ddd; padding: 15px; margin: 10px 0; }
                    .ui.divider { border-bottom: 1px solid #ddd; margin: 15px 0; }
                    .ui.grid { display: flex; flex-wrap: wrap; }
                    .eight.wide.column { width: 50%; padding: 10px; }
                    .ui.label { padding: 3px 8px; border-radius: 3px; }
                    .green.label { background-color: #21ba45; color: white; }
                    .blue.label { background-color: #2185d0; color: white; }
                    .yellow.label { background-color: #fbbd08; color: #333; }
                    .red.label { background-color: #db2828; color: white; }
                    .grey.label { background-color: #767676; color: white; }
                    @media print {
                        body { margin: 0; }
                        .ui.segment { border: none; box-shadow: none; }
                    }
                </style>
            </head>
            <body>
                <h1>VetSync - Appointment Report</h1>
                ${printContent}
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
});

// Handle table-based action buttons (NEW HANDLERS)
$(document).on("click", ".confirm-appointment", function (e) {
    e.preventDefault();
    const uuid = $(this).data("uuid");

    if (
        confirm(
            "Mark this appointment as confirmed? This will move it to the confirmed list."
        )
    ) {
        updateAppointmentStatus(uuid, "accepted");
    }
});

$(document).on("click", ".complete-appointment", function (e) {
    e.preventDefault();
    const uuid = $(this).data("uuid");

    if (
        confirm(
            "Mark this appointment as completed? This will move it to the completed list."
        )
    ) {
        updateAppointmentStatus(uuid, "completed");
    }
});

$(document).on("click", ".reschedule-appointment", function (e) {
    e.preventDefault();
    const uuid = $(this).data("uuid");

    // Get appointment info from the table row
    const $row = $(this).closest("tr");
    const petName = $row.find("td:nth-child(2)").text().trim();
    const serviceName = $row.find("td:nth-child(4) .fw-bold").text().trim();
    const appointmentDate = $row.find("td:nth-child(1)").text().trim();

    // Open reschedule modal
    openRescheduleModalForTable(uuid, petName, serviceName, appointmentDate);
});

$(document).on("click", ".cancel-appointment", function (e) {
    e.preventDefault();
    const uuid = $(this).data("uuid");

    // Get appointment info from the table row
    const $row = $(this).closest("tr");
    const petName = $row.find("td:nth-child(2)").text().trim();
    const serviceName = $row.find("td:nth-child(4) .fw-bold").text().trim();

    // Open cancellation modal
    openCancellationModalForTable(uuid, petName, serviceName);
});

$(document).on("click", ".view-details", function (e) {
    e.preventDefault();
    const uuid = $(this).data("uuid");

    // For now, just show an alert. You can implement a details modal later
    alert(`View details for appointment: ${uuid}`);
});

$(document).on("click", ".delete-appointment", function (e) {
    e.preventDefault();
    const uuid = $(this).data("uuid");

    const $row = $(this).closest("tr");
    const petName = $row.find("td:nth-child(2)").text().trim();

    if (
        confirm(
            `Are you sure you want to permanently delete the appointment for "${petName}"? This action cannot be undone.`
        )
    ) {
        deleteAppointment(uuid);
    }
});

// Function to open reschedule modal for table rows
function openRescheduleModalForTable(uuid, petName, serviceName, currentDate) {
    // Populate modal with current appointment details
    $("#rescheduleUuid").val(uuid);

    // Update modal content - you may need to adjust these selectors based on your modal structure
    $("#rescheduleModal .header").text(`Reschedule Appointment - ${petName}`);

    // Set minimum date to today
    const today = new Date().toISOString().split("T")[0];
    $("#rescheduleDate").attr("min", today).val("");
    $("#rescheduleReason").val("");

    // Show modal
    $("#rescheduleModal").modal("show");
}

// Function to open cancellation modal for table rows
function openCancellationModalForTable(uuid, petName, serviceName) {
    // Set the UUID for cancellation
    appointmentToCancel = uuid;

    // Clear previous reason
    $("#cancelReason").val("");

    // Update modal title
    $("#cancellationModal .header").html(`
        <i class="times circle icon"></i>
        Cancel Appointment - ${petName}
    `);

    // Show modal
    $("#cancellationModal").modal("show");
}

// Function to handle appointment deletion
function deleteAppointment(uuid) {
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
                alert("Appointment deleted successfully");
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

// Handle reschedule modal confirmation (update existing handler)
$(document).on("click", "#confirmReschedule", function () {
    const uuid = $("#rescheduleUuid").val();
    const newDate = $("#rescheduleDate").val();
    const reason = $("#rescheduleReason").val();

    if (!newDate) {
        alert("Please select a new date");
        return;
    }

    if (!reason) {
        alert("Please provide a reason for rescheduling");
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
            if (response.success) {
                $("#rescheduleModal").modal("hide");
                alert("Appointment rescheduled successfully");
                getAllAppointments(); // Reload appointments
            } else {
                alert("Failed to reschedule appointment: " + response.message);
            }
        },
        error: function () {
            alert("Failed to reschedule appointment");
        },
    });
});

// Handle cancellation modal confirmation (update existing handler)
$(document).on("click", "#confirmCancel", function () {
    const reason = $("#cancelReason").val().trim();

    if (!reason) {
        alert("Please provide a reason for cancellation");
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
                    alert("Appointment cancelled successfully");
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

// Handle group expansion/collapse - Updated to handle both row and badge clicks
$(document).on(
    "click",
    ".group-collapsed, .group-expand-icon, .group-collapsed .text-primary",
    function (e) {
        e.preventDefault();
        e.stopPropagation();

        // Find the parent row
        const $row = $(this).hasClass("group-collapsed")
            ? $(this)
            : $(this).closest(".group-collapsed");

        if (!$row.length) return;

        const groupId = $row.attr("data-group-id");
        const $icon = $row.find(".group-expand-icon");
        const isExpanded = $row.hasClass("group-expanded");

        console.log("Group clicked:", groupId, "isExpanded:", isExpanded);

        if (isExpanded) {
            // Collapse: remove expanded rows
            $(`.group-child-row[data-parent-group="${groupId}"]`).remove();
            $icon.removeClass("bx-chevron-down").addClass("bx-chevron-right");
            $row.removeClass("group-expanded");
        } else {
            // Expand: fetch and render child rows
            $icon.removeClass("bx-chevron-right").addClass("bx-chevron-down");
            $row.addClass("group-expanded");

            // Fetch current appointments to get group data
            $.ajax({
                url: "/src/features/appointments/api/appointments.php",
                method: "GET",
                dataType: "json",
                success: function (response) {
                    if (response.success && response.data) {
                        const groupAppointments = response.data.filter(
                            (apt) => apt.booking_group_id === groupId
                        );

                        console.log(
                            "Found group appointments:",
                            groupAppointments.length
                        );

                        if (groupAppointments.length > 1) {
                            for (let i = 1; i < groupAppointments.length; i++) {
                                const childApp = groupAppointments[i];
                                const statusInfo = getStatusInfo(
                                    childApp.status
                                );

                                const childRow = `
                                <tr class="group-child-row" data-parent-group="${groupId}" data-uuid="${
                                    childApp.uuid
                                }">
                                    <td colspan="2" style="padding-left: 3rem; background-color: #f8f9fa;">
                                        <i class="bx bx-subdirectory-right text-muted"></i>
                                        <strong>${
                                            childApp.service_name ||
                                            "Custom Service"
                                        }</strong>
                                    </td>
                                    <td style="background-color: #f8f9fa;">
                                        <div class="fw-bold">${
                                            childApp.pet_name || "Unknown"
                                        }</div>
                                    </td>
                                    <td style="background-color: #f8f9fa;">
                                        <div>${
                                            childApp.user_name ||
                                            childApp.user_email
                                        }</div>
                                    </td>
                                    <td colspan="2" style="background-color: #f8f9fa;">
                                        <div class="fw-bold">${
                                            childApp.service_name ||
                                            "Custom Service"
                                        }</div>
                                    </td>
                                    <td style="background-color: #f8f9fa;">
                                        ${getActionButtonsForChild(
                                            childApp,
                                            statusInfo.label
                                        )}
                                    </td>
                                </tr>
                            `;

                                $row.after(childRow);
                            }
                        }
                    }
                },
            });
        }
    }
);

// Helper function to get action buttons for child rows
function getActionButtonsForChild(app, statusLabel) {
    // Child rows should not have action buttons - actions apply to entire group
    return `<small class="text-muted">Actions apply to entire group</small>`;
}

$(document).ready(function () {
    loadAdminStats();
    loadRecentAppointments();
    loadRecentUsers();
    loadRecentReservations();
});

function loadAdminStats() {
    $.ajax({
        url: "/src/features/dashboard/api/admin-stats.php",
        method: "GET",
        dataType: "json",
        success: function (response) {
            console.log("Admin stats response:", response); // Debug log

            if (response.success) {
                const data = response.data;

                // Update stats with new pending data
                $("#overall-pending").text(data.overall_pending);
                $("#pending-appointments-month").text(
                    data.pending_appointments_month
                );
                $("#pending-reservations-month").text(
                    data.pending_reservations_month
                );
            } else {
                console.error("Failed to load admin stats:", response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error loading admin stats:", error);
        },
    });
}

function loadRecentAppointments() {
    $.ajax({
        url: "/src/features/dashboard/api/recent-appointments-admin.php",
        method: "GET",
        dataType: "json",
        success: function (response) {
            if (response.success && response.data) {
                renderRecentAppointments(response.data);
            } else {
                $("#recent-appointments-list").html(
                    '<p class="text-muted text-center">No pending appointments</p>'
                );
            }
        },
        error: function () {
            $("#recent-appointments-list").html(
                '<p class="text-danger text-center">Failed to load appointments</p>'
            );
        },
    });
}

function loadRecentUsers() {
    $.ajax({
        url: "/src/features/dashboard/api/recent-users-admin.php",
        method: "GET",
        dataType: "json",
        success: function (response) {
            if (response.success && response.data) {
                renderRecentUsers(response.data);
            } else {
                $("#new-users-grid").html(
                    '<p class="text-muted text-center">No recent users found</p>'
                );
            }
        },
        error: function () {
            $("#new-users-grid").html(
                '<p class="text-danger text-center">Failed to load users</p>'
            );
        },
    });
}

function loadRecentReservations() {
    $.ajax({
        url: "/src/features/dashboard/api/recent-reservations-admin.php",
        method: "GET",
        dataType: "json",
        success: function (response) {
            if (response.success && response.data) {
                renderRecentReservations(response.data);
            } else {
                $("#recent-reservations-list").html(
                    '<p class="text-muted text-center">No pending reservations</p>'
                );
            }
        },
        error: function () {
            $("#recent-reservations-list").html(
                '<p class="text-danger text-center">Failed to load reservations</p>'
            );
        },
    });
}

function renderRecentAppointments(appointments) {
    let html = "";

    if (appointments.length === 0) {
        html = '<p class="text-muted text-center">No pending appointments</p>';
    } else {
        appointments.forEach((appointment) => {
            html += `
                <div class="appointment-item">
                    <div class="appointment-info">
                        <h6>${appointment.pet_name}</h6>
                        <small>${appointment.service_name} • ${formatDate(
                appointment.date
            )}</small>
                    </div>
                    <span class="appointment-status status-${
                        appointment.status
                    }">
                        ${capitalizeFirst(appointment.status)}
                    </span>
                </div>
            `;
        });
    }

    $("#recent-appointments-list").html(html);
}

function renderRecentUsers(users) {
    let html = "";

    if (users.length === 0) {
        html =
            '<p class="text-muted text-center" style="grid-column: 1 / -1;">No recent users</p>';
    } else {
        users.forEach((user) => {
            const initial = user.full_name.charAt(0).toUpperCase();
            const avatarHtml =
                user.avatar_url && !user.avatar_url.includes("placeholders")
                    ? `<img src="${user.avatar_url}" alt="${user.full_name}">`
                    : initial;

            html += `
                <div class="user-card">
                    <div class="user-avatar">${avatarHtml}</div>
                    <div class="user-name" title="${user.full_name}">${user.full_name}</div>
                    <div class="user-time">${user.time_ago}</div>
                </div>
            `;
        });
    }

    $("#new-users-grid").html(html);
}

function renderRecentReservations(reservations) {
    let html = "";

    if (reservations.length === 0) {
        html = '<p class="text-muted text-center">No pending reservations</p>';
    } else {
        reservations.forEach((reservation) => {
            const statusClass = getReservationStatusClass(reservation.status);
            html += `
                <div class="reservation-item">
                    <div class="reservation-info">
                        <h6>${reservation.user_name}</h6>
                        <small>${reservation.product_name} • ${
                reservation.time_ago
            }</small>
                    </div>
                    <div>
                        <div class="reservation-amount">₱${parseFloat(
                            reservation.total_amount
                        ).toFixed(2)}</div>
                        <small class="text-muted">${capitalizeFirst(
                            reservation.status
                        )}</small>
                    </div>
                </div>
            `;
        });
    }

    $("#recent-reservations-list").html(html);
}

function getReservationStatusClass(status) {
    const statusClasses = {
        pending: "warning",
        accepted: "info",
        ready_for_pickup: "primary",
        picked_up: "success",
        rejected: "danger",
    };
    return statusClasses[status] || "secondary";
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString("en-US", {
        month: "short",
        day: "numeric",
        year: "numeric",
    });
}

function capitalizeFirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

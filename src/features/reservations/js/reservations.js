$(document).ready(function () {
    loadReservations();
    setupTabNavigation();
    setupSearchFunctionality();
    setupActionHandlers();
});

let allReservations = [];

function loadReservations() {
    $.get("/src/features/reservations/api/reservations.php")
        .done(function (response) {
            if (response.success) {
                allReservations = response.data;
                renderReservations("all", allReservations);
                updateTabCounts();
            } else {
                showError("Failed to load reservations: " + response.message);
            }
        })
        .fail(function () {
            showError("Failed to load reservations. Please try again.");
        });
}

function setupTabNavigation() {
    $(".nav-pills .nav-link").click(function (e) {
        e.preventDefault();

        // Update active tab
        $(".nav-pills .nav-link").removeClass("active");
        $(this).addClass("active");

        // Show corresponding tab content
        const target = $(this).data("target");
        $(".tab-content").removeClass("active");
        $(`#${target}-tab`).addClass("active");

        // Filter and render reservations
        const filteredReservations = filterReservationsByStatus(target);
        renderReservations(target, filteredReservations);
    });
}

function filterReservationsByStatus(status) {
    if (status === "all") {
        return allReservations;
    }

    // Map completed to picked_up for filtering
    const filterStatus = status === "completed" ? "picked_up" : status;
    return allReservations.filter(
        (reservation) => reservation.status === filterStatus
    );
}

function renderReservations(tab, reservations) {
    const tableBody = $(`#${tab}-reservations-table`);
    tableBody.empty();

    if (reservations.length === 0) {
        tableBody.append(`
            <tr>
                <td colspan="6" class="empty-state">
                    <i class="material-icons-sharp">inbox</i>
                    <h3>No ${tab === "all" ? "" : tab} reservations found</h3>
                    <p>There are no reservations to display at the moment.</p>
                </td>
            </tr>
        `);
        return;
    }

    reservations.forEach((reservation) => {
        const row = createReservationRow(reservation);
        tableBody.append(row);
    });
}

function createReservationRow(reservation) {
    const products = reservation.products_array || [];
    const statusBadge = getStatusBadge(reservation.status);
    const actionButtons = getActionButtons(reservation);

    // Get customer initial for avatar
    const customerName = `${reservation.firstname || ""} ${
        reservation.lastname || ""
    }`.trim();
    const customerInitial = customerName.charAt(0).toUpperCase() || "U";

    // Create products list
    let productsList = "";
    products.forEach((product) => {
        productsList += `
            <div class="product-item">
                <div class="product-name">${product.name}</div>
                <div class="product-details">Qty: ${
                    product.quantity
                } | ₱${parseFloat(product.price).toFixed(2)}</div>
            </div>
        `;
    });

    return `
        <tr data-reservation-id="${reservation.id}">
            <td>
                <div>${reservation.formatted_date}</div>
                <div style="color: #6c757d; font-size: 0.85rem;">${
                    reservation.formatted_time
                }</div>
            </td>
            <td>
                <div class="customer-info">
                    <div class="customer-avatar">${customerInitial}</div>
                    <div class="customer-details">
                        <div class="customer-name">${customerName}</div>
                        <div class="customer-email">${
                            reservation.email || ""
                        }</div>
                    </div>
                </div>
            </td>
            <td>
                <div class="product-list">
                    ${productsList}
                </div>
            </td>
            <td>
                <div class="amount">₱${parseFloat(
                    reservation.total_amount
                ).toFixed(2)}</div>
            </td>
            <td>${statusBadge}</td>
            <td>
                <div class="action-buttons">
                    ${actionButtons}
                </div>
            </td>
        </tr>
    `;
}

function getStatusBadge(status) {
    const statusMap = {
        pending: { class: "pending", text: "Pending" },
        accepted: { class: "accepted", text: "Ready for Pickup" },
        rejected: { class: "rejected", text: "Rejected" },
        picked_up: { class: "completed", text: "Picked Up" },
    };

    const statusInfo = statusMap[status] || { class: "pending", text: status };
    return `<span class="status-badge ${statusInfo.class}">${statusInfo.text}</span>`;
}

function getActionButtons(reservation) {
    let buttons = "";

    switch (reservation.status) {
        case "pending":
            buttons = `
                <button class="btn btn-accept" onclick="updateReservationStatus(${reservation.id}, 'accepted')">
                    Accept
                </button>
                <button class="btn btn-reject" onclick="showRejectionModal(${reservation.id})">
                    Reject
                </button>
            `;
            break;
        case "accepted":
            buttons = `
                <button class="btn btn-complete" onclick="markAsPickedUp(${reservation.id})">
                    Mark as Picked Up
                </button>
            `;
            break;
        case "picked_up":
            buttons = `
                <span class="ready-status">
                    <i class="material-icons-sharp">done_all</i>
                    Completed
                </span>
            `;
            break;
        case "rejected":
            buttons = `
                <span class="status-info">
                    <i class="material-icons-sharp">info</i>
                    Rejected
                </span>
            `;
            break;
    }

    return buttons;
}

function setupActionHandlers() {
    // Rejection modal handlers
    $("#confirmRejection").click(function () {
        const reservationId = $("#rejectionReservationId").val();
        const reason = $("#rejectionReason").val().trim();

        if (!reason) {
            showError("Please provide a reason for rejection.");
            return;
        }

        updateReservationStatus(reservationId, "rejected", reason);
        $("#rejectionModal").modal("hide");
    });
}

function updateReservationStatus(reservationId, status, reason = null) {
    const data = {
        action: "update_status",
        id: reservationId,
        status: status,
    };

    if (reason) {
        data.rejection_reason = reason;
    }

    $.post("/src/features/reservations/api/reservations.php", data)
        .done(function (response) {
            if (response.success) {
                showSuccess(response.message);
                loadReservations(); // Reload data
            } else {
                showError(response.message);
            }
        })
        .fail(function () {
            showError("Failed to update reservation status. Please try again.");
        });
}

function markAsPickedUp(reservationId) {
    // Show confirmation dialog
    if (
        confirm(
            "Mark this reservation as picked up? This action cannot be undone."
        )
    ) {
        const data = {
            action: "update_status",
            id: reservationId,
            status: "picked_up",
            pickup_notes: "Product picked up by customer",
        };

        $.post("/src/features/reservations/api/reservations.php", data)
            .done(function (response) {
                if (response.success) {
                    showSuccess(
                        "Reservation marked as picked up successfully!"
                    );
                    loadReservations(); // Reload data
                } else {
                    showError(response.message);
                }
            })
            .fail(function () {
                showError("Failed to mark as picked up. Please try again.");
            });
    }
}

function showRejectionModal(reservationId) {
    $("#rejectionReservationId").val(reservationId);
    $("#rejectionReason").val("");
    $("#rejectionModal").modal("show");
}

function setupSearchFunctionality() {
    // Setup search for each tab
    ["all", "pending", "accepted", "rejected", "completed"].forEach((tab) => {
        $(`#search-${tab}`).on("input", function () {
            const searchTerm = $(this).val().toLowerCase();
            const filteredReservations = filterReservationsByStatus(tab);

            if (searchTerm) {
                const searchResults = filteredReservations.filter(
                    (reservation) => {
                        const customerName =
                            `${reservation.firstname} ${reservation.lastname}`.toLowerCase();
                        const email = (reservation.email || "").toLowerCase();
                        const products = JSON.stringify(
                            reservation.products_array || []
                        ).toLowerCase();

                        return (
                            customerName.includes(searchTerm) ||
                            email.includes(searchTerm) ||
                            products.includes(searchTerm)
                        );
                    }
                );
                renderReservations(tab, searchResults);
            } else {
                renderReservations(tab, filteredReservations);
            }
        });
    });
}

function updateTabCounts() {
    const counts = {
        all: allReservations.length,
        pending: allReservations.filter((r) => r.status === "pending").length,
        accepted: allReservations.filter((r) => r.status === "accepted").length,
        rejected: allReservations.filter((r) => r.status === "rejected").length,
        completed: allReservations.filter((r) => r.status === "picked_up")
            .length,
    };

    // Update tab labels with counts (optional)
    Object.keys(counts).forEach((status) => {
        const tab = $(`.nav-link[data-target="${status}"]`);
        const text = tab.text().replace(/\(\d+\)/, "");
        tab.html(
            `${tab.find("i")[0].outerHTML} ${text.trim()} (${counts[status]})`
        );
    });
}

function showSuccess(message) {
    // You can implement your notification system here
    alert(message); // Temporary
}

function showError(message) {
    // You can implement your notification system here
    alert(message); // Temporary
}

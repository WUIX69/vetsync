// Reservations Management System - Table Layout
const ReservationsManager = {
    init() {
        // console.log("ReservationsManager initialized");
        this.loadReservations();
        this.bindEvents();
    },

    bindEvents() {
        // console.log("Binding events");

        // Tab navigation
        $(document).on("click", ".nav-pills .nav-link", function (e) {
            e.preventDefault();
            // console.log("Tab clicked:", $(this).data("target"));
            const target = $(this).data("target");

            // Update active nav
            $(".nav-pills .nav-link").removeClass("active");
            $(this).addClass("active");

            // Show target content
            $(".tab-content").removeClass("active");
            $(`#${target}-tab`).addClass("active");
        });

        // Accept reservation
        $(document).on("click", ".btn-accept", function (e) {
            e.preventDefault();
            // console.log("Accept button clicked");
            const reservationId = $(this).data("reservation-id");
            // console.log("Reservation ID:", reservationId);

            if (confirm("Accept this reservation?")) {
                ReservationsManager.updateStatus(reservationId, "accepted");
            }
        });

        // Reject reservation
        $(document).on("click", ".btn-reject", function (e) {
            e.preventDefault();
            // console.log("Reject button clicked");
            const reservationId = $(this).data("reservation-id");
            // console.log("Reservation ID:", reservationId);

            $("#rejectionReservationId").val(reservationId);
            $("#rejectionModal").modal("show");
        });

        // Confirm rejection
        $("#confirmRejection").on("click", function () {
            // console.log("Confirm rejection clicked");
            const reservationId = $("#rejectionReservationId").val();
            const reason = $("#rejectionReason").val().trim();

            if (!reason) {
                alert("Please provide a reason for rejection");
                return;
            }

            ReservationsManager.updateStatus(reservationId, "rejected", reason);
            $("#rejectionModal").modal("hide");
            $("#rejectionReason").val("");
        });

        // Mark as Ready (Complete) reservation
        $(document).on("click", ".btn-complete", function (e) {
            e.preventDefault();
            // console.log("Complete button clicked");
            const reservationId = $(this).data("reservation-id");
            // console.log("Reservation ID:", reservationId);

            if (confirm("Mark this reservation as ready for pickup?")) {
                ReservationsManager.updateStatus(reservationId, "completed");
            }
        });

        // Search functionality
        $("input[id^='search-']").on("keyup", function () {
            const searchTerm = $(this).val().toLowerCase();
            const tableId =
                $(this).attr("id").replace("search-", "") +
                "-reservations-table";

            $(`#${tableId} tr`).each(function () {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.indexOf(searchTerm) > -1);
            });
        });
    },

    loadReservations() {
        // console.log("Loading reservations...");
        $.ajax({
            url: "/src/features/reservations/api/reservations.php",
            method: "GET",
            dataType: "json",
            success: function (response) {
                // console.log("Reservations loaded:", response);
                if (!response.success) {
                    console.error(
                        "Failed to load reservations:",
                        response.message
                    );
                    return;
                }

                ReservationsManager.renderReservations(response.data);
            },
            error: function (xhr, status, error) {
                console.error("Error loading reservations:", error);
                // console.error("Response:", xhr.responseText);
            },
        });
    },

    renderReservations(reservations) {
        // console.log("Rendering reservations:", reservations);
        // Clear all table bodies
        $(
            "#all-reservations-table, #pending-reservations-table, #accepted-reservations-table, #rejected-reservations-table, #completed-reservations-table"
        ).empty();

        if (reservations.length === 0) {
            this.showEmptyState(
                "all-reservations-table",
                "No reservations found"
            );
            return;
        }

        // Separate reservations by status
        const allReservations = reservations;
        const pendingReservations = reservations.filter(
            (r) => r.status === "pending"
        );
        const acceptedReservations = reservations.filter(
            (r) => r.status === "accepted"
        );
        const rejectedReservations = reservations.filter(
            (r) => r.status === "rejected"
        );
        const completedReservations = reservations.filter(
            (r) => r.status === "completed"
        );

        // console.log("Status breakdown:", {
        //     all: allReservations.length,
        //     pending: pendingReservations.length,
        //     accepted: acceptedReservations.length,
        //     rejected: rejectedReservations.length,
        //     completed: completedReservations.length,
        // });

        // Render each category
        this.populateTable("all-reservations-table", allReservations);
        this.populateTable("pending-reservations-table", pendingReservations);
        this.populateTable("accepted-reservations-table", acceptedReservations);
        this.populateTable("rejected-reservations-table", rejectedReservations);
        this.populateTable(
            "completed-reservations-table",
            completedReservations
        );

        // Show empty states for empty tables
        this.showEmptyStatesIfNeeded();
    },

    populateTable(tableId, reservations) {
        // console.log(
        //     `Populating table ${tableId} with ${reservations.length} reservations`
        // );
        const $tableBody = $(`#${tableId}`);

        reservations.forEach((reservation) => {
            const row = this.createTableRow(reservation);
            $tableBody.append(row);
        });
    },

    createTableRow(reservation) {
        // console.log("Creating row for reservation:", reservation);
        const statusInfo = this.getStatusInfo(reservation.status);
        const products = JSON.parse(reservation.products || "[]");

        // Calculate total amount
        const totalAmount = products.reduce((sum, product) => {
            const price =
                parseFloat(product.total_price) ||
                (parseFloat(product.price) || 0) *
                    (parseInt(product.qty) || parseInt(product.quantity) || 0);
            return sum + price;
        }, 0);

        // Get customer initials for avatar
        const initials = `${reservation.firstname?.charAt(0) || ""}${
            reservation.lastname?.charAt(0) || ""
        }`.toUpperCase();

        const actionButtons = this.getActionButtons(reservation);
        // console.log(
        //     "Action buttons for reservation",
        //     reservation.id,
        //     ":",
        //     actionButtons
        // );

        return `
            <tr data-id="${reservation.id}">
                <td>
                    <div style="font-weight: 600; color: #2c3e50;">${
                        reservation.formatted_date
                    }</div>
                    <div style="color: #6c757d; font-size: 0.85rem;">${
                        reservation.formatted_time
                    }</div>
                </td>
                <td>
                    <div class="customer-info">
                        <div class="customer-avatar">${initials}</div>
                        <div class="customer-details">
                            <div class="customer-name">${
                                reservation.firstname
                            } ${reservation.lastname}</div>
                            <div class="customer-email">${
                                reservation.email || "No email"
                            }</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="product-list">
                        ${products
                            .map((product) => {
                                const qty =
                                    parseInt(product.qty) ||
                                    parseInt(product.quantity) ||
                                    0;
                                const price = parseFloat(product.price) || 0;
                                const subtotal =
                                    parseFloat(product.total_price) ||
                                    price * qty;

                                return `
                                <div class="product-item">
                                    <div class="product-name">${
                                        product.name
                                    }</div>
                                    <div class="product-details">
                                        Qty: ${qty} × ₱${price.toFixed(
                                    2
                                )} = ₱${subtotal.toFixed(2)}
                                    </div>
                                </div>
                            `;
                            })
                            .join("")}
                    </div>
                </td>
                <td>
                    <div class="amount">₱${totalAmount.toFixed(2)}</div>
                </td>
                <td>
                    <span class="status-badge ${reservation.status}">${
            statusInfo.label
        }</span>
                </td>
                <td>
                    <div class="action-buttons">
                        ${actionButtons}
                    </div>
                </td>
            </tr>
        `;
    },

    getStatusInfo(status) {
        const statusMap = {
            pending: { label: "Pending", class: "pending" },
            accepted: { label: "Accepted", class: "accepted" },
            rejected: { label: "Rejected", class: "rejected" },
            completed: { label: "Ready", class: "completed" },
        };
        return statusMap[status] || { label: "Unknown", class: "unknown" };
    },

    getActionButtons(reservation) {
        const status = reservation.status;
        let buttons = "";

        // console.log("Getting action buttons for status:", status);

        switch (status) {
            case "pending":
                buttons = `
                    <button class="btn btn-accept" data-reservation-id="${reservation.id}">Accept</button>
                    <button class="btn btn-reject" data-reservation-id="${reservation.id}">Reject</button>
                `;
                break;
            case "accepted":
                buttons = `
                    <button class="btn btn-complete" data-reservation-id="${reservation.id}">Mark Ready</button>
                `;
                break;
            case "rejected":
                buttons = `
                    <span class="status-info">
                        <i class="material-icons-sharp">info</i> Rejected
                    </span>
                `;
                break;
            case "completed":
                buttons = `
                    <span class="ready-status">
                        <i class="material-icons-sharp">check_circle</i> Ready for Pickup
                    </span>
                `;
                break;
            default:
                buttons = `
                    <span class="status-info">
                        <i class="material-icons-sharp">help</i> Unknown
                    </span>
                `;
        }

        // console.log("Generated buttons:", buttons);
        return buttons;
    },

    updateStatus(reservationId, status, reason = null) {
        // console.log("Updating status:", { reservationId, status, reason });
        const data = {
            action: "update_status",
            id: reservationId,
            status: status,
        };

        if (reason) {
            data.rejection_reason = reason;
        }

        // console.log("Sending data:", data);

        $.ajax({
            url: "/src/features/reservations/api/reservations.php",
            method: "POST",
            data: data,
            dataType: "json",
            success: function (response) {
                // console.log("Update response:", response);
                if (response.success) {
                    ReservationsManager.showNotification(
                        response.message,
                        "success"
                    );
                    ReservationsManager.loadReservations(); // Reload data
                } else {
                    ReservationsManager.showNotification(
                        response.message,
                        "error"
                    );
                }
            },
            error: function (xhr, status, error) {
                console.error("Error updating status:", error);
                // console.error("Response:", xhr.responseText);
                ReservationsManager.showNotification(
                    "Failed to update reservation status",
                    "error"
                );
            },
        });
    },

    showEmptyStatesIfNeeded() {
        const tables = [
            {
                id: "pending-reservations-table",
                message: "No pending reservations",
            },
            {
                id: "accepted-reservations-table",
                message: "No accepted reservations",
            },
            {
                id: "rejected-reservations-table",
                message: "No rejected reservations",
            },
            {
                id: "completed-reservations-table",
                message: "No completed reservations",
            },
        ];

        tables.forEach((table) => {
            if ($(`#${table.id} tr`).length === 0) {
                this.showEmptyState(table.id, table.message);
            }
        });
    },

    showEmptyState(tableId, message) {
        $(`#${tableId}`).html(`
            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <i class="material-icons-sharp">inbox</i>
                        <h3>${message}</h3>
                        <p>Reservations will appear here when available</p>
                    </div>
                </td>
            </tr>
        `);
    },

    showNotification(message, type) {
        if (type === "success") {
            alert("✓ " + message);
        } else {
            alert("✗ " + message);
        }
    },
};

// Initialize when document is ready
$(document).ready(function () {
    // console.log("Document ready, initializing ReservationsManager");
    ReservationsManager.init();
});

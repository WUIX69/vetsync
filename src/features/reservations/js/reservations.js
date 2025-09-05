// Reservations Management System
const ReservationsManager = {
    init() {
        this.loadReservations();
        this.bindEvents();
    },

    bindEvents() {
        // Tab navigation
        $(document).on("click", ".navigation .nav-link", function (e) {
            e.preventDefault();
            const target = $(this).data("target");

            // Update active nav
            $(".navigation .nav-link").removeClass("active");
            $(this).addClass("active");

            // Show target content
            $(".tab-content").removeClass("active");
            $(target).addClass("active");
        });

        // Accept reservation
        $(document).on("click", ".btn-accept", function (e) {
            e.preventDefault();
            const reservationId = $(this).data("reservation-id");

            if (confirm("Accept this reservation?")) {
                ReservationsManager.updateStatus(reservationId, "accepted");
            }
        });

        // Reject reservation
        $(document).on("click", ".btn-reject", function (e) {
            e.preventDefault();
            const reservationId = $(this).data("reservation-id");

            $("#rejectionReservationId").val(reservationId);
            $("#rejectionModal").modal("show");
        });

        // Confirm rejection
        $("#confirmRejection").on("click", function () {
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
    },

    loadReservations() {
        $.ajax({
            url: "/src/features/reservations/api/reservations.php",
            method: "GET",
            dataType: "json",
            success: function (response) {
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
            },
        });
    },

    renderReservations(reservations) {
        // Clear all containers
        $(
            "#allReservationsContainer, #pendingReservationsContainer, #acceptedReservationsContainer, #rejectedReservationsContainer"
        ).empty();

        if (reservations.length === 0) {
            const emptyState = this.getEmptyState("No reservations found");
            $("#allReservationsContainer").html(emptyState);
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

        // Render each category separately (no cloning)
        allReservations.forEach((reservation) => {
            const card = this.createReservationCard(reservation);
            $("#allReservationsContainer").append(card);
        });

        pendingReservations.forEach((reservation) => {
            const card = this.createReservationCard(reservation);
            $("#pendingReservationsContainer").append(card);
        });

        acceptedReservations.forEach((reservation) => {
            const card = this.createReservationCard(reservation);
            $("#acceptedReservationsContainer").append(card);
        });

        rejectedReservations.forEach((reservation) => {
            const card = this.createReservationCard(reservation);
            $("#rejectedReservationsContainer").append(card);
        });

        // Show empty state for empty status containers
        this.showEmptyStatesIfNeeded();
    },

    createReservationCard(reservation) {
        const products = reservation.products_array || [];
        const productsHtml = products
            .map(
                (product) => `
            <div class="product-item">
                <div class="product-info">
                    <div class="product-name">${product.name || "Product"}</div>
                    <div class="product-details">
                        <span class="product-size">Size: ${
                            product.size || "M"
                        }</span>
                        <span class="product-qty">Qty: ${
                            product.quantity || 1
                        }</span>
                    </div>
                </div>
                <div class="product-price">₱${product.price || 0}</div>
            </div>
        `
            )
            .join("");

        const actionsHtml =
            reservation.status === "pending"
                ? `
            <div class="reservation-actions">
                <button class="btn btn-accept" data-reservation-id="${reservation.id}">
                    <i class="material-icons-sharp">check_circle</i>
                    <span>Accept</span>
                </button>
                <button class="btn btn-reject" data-reservation-id="${reservation.id}">
                    <i class="material-icons-sharp">cancel</i>
                    <span>Reject</span>
                </button>
            </div>
        `
                : `
            <div class="status-info">
                <i class="material-icons-sharp">${
                    reservation.status === "accepted"
                        ? "check_circle"
                        : "cancel"
                }</i>
                <span>This reservation has been ${reservation.status}</span>
            </div>
        `;

        const rejectionReason = reservation.rejection_reason
            ? `
            <div class="rejection-reason">
                <i class="material-icons-sharp">error_outline</i>
                <div>
                    <strong>Rejection Reason:</strong>
                    <p>${reservation.rejection_reason}</p>
                </div>
            </div>
        `
            : "";

        return $(`
            <div class="reservation-card modern ${
                reservation.status
            }" data-reservation-id="${reservation.id}">
                <div class="reservation-header">
                    <div class="customer-info">
                        <div class="avatar">${reservation.firstname.charAt(
                            0
                        )}${reservation.lastname.charAt(0)}</div>
                        <div class="customer-details">
                            <h4 class="customer-name">${
                                reservation.firstname
                            } ${reservation.lastname}</h4>
                            <small class="customer-email">${
                                reservation.email
                            }</small>
                        </div>
                    </div>
                    <div class="status-badge ${reservation.status}">
                        <i class="material-icons-sharp">${this.getStatusIcon(
                            reservation.status
                        )}</i>
                        <span>${
                            reservation.status.charAt(0).toUpperCase() +
                            reservation.status.slice(1)
                        }</span>
                    </div>
                </div>
                
                <div class="reservation-details">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <i class="material-icons-sharp">event</i>
                            <div>
                                <label>Preferred Date</label>
                                <value>${reservation.formatted_date}</value>
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="material-icons-sharp">schedule</i>
                            <div>
                                <label>Preferred Time</label>
                                <value>${reservation.formatted_time}</value>
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="material-icons-sharp">local_shipping</i>
                            <div>
                                <label>Method</label>
                                <value>${reservation.delivery_method}</value>
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="material-icons-sharp">payments</i>
                            <div>
                                <label>Total Amount</label>
                                <value>₱${reservation.total_amount}</value>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="products-section">
                    <h5 class="section-title">
                        <i class="material-icons-sharp">shopping_bag</i>
                        Products (${reservation.products_count})
                    </h5>
                    <div class="products-list">
                        ${productsHtml}
                    </div>
                </div>

                ${
                    reservation.notes
                        ? `<div class="notes-section">
                    <h5 class="section-title">
                        <i class="material-icons-sharp">note</i>
                        Special Instructions
                    </h5>
                    <p class="notes-text">${reservation.notes}</p>
                </div>`
                        : ""
                }

                ${rejectionReason}
                
                <div class="reservation-footer">
                    <div class="created-info">
                        <i class="material-icons-sharp">schedule</i>
                        <span>Created: ${new Date(
                            reservation.created_at
                        ).toLocaleDateString()}</span>
                    </div>
                    ${actionsHtml}
                </div>
            </div>
        `);
    },

    updateStatus(reservationId, status, reason = null) {
        const data = {
            action: "update_status",
            id: reservationId,
            status: status,
        };

        if (reason) {
            data.rejection_reason = reason;
        }

        $.ajax({
            url: "/src/features/reservations/api/reservations.php",
            method: "POST",
            data: data,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    ReservationsManager.showNotification(
                        response.message,
                        "success"
                    );
                    ReservationsManager.loadReservations(); // Reload data
                    // Trigger a badge update notification (this would need a push notification system)
                    // For now, the user's badge will update when they next visit the cart page
                } else {
                    ReservationsManager.showNotification(
                        response.message,
                        "error"
                    );
                }
            },
            error: function (xhr, status, error) {
                console.error("Error updating status:", error);
                ReservationsManager.showNotification(
                    "Failed to update reservation status",
                    "error"
                );
            },
        });
    },

    showEmptyStatesIfNeeded() {
        const containers = [
            {
                id: "#pendingReservationsContainer",
                message: "No pending reservations",
            },
            {
                id: "#acceptedReservationsContainer",
                message: "No accepted reservations",
            },
            {
                id: "#rejectedReservationsContainer",
                message: "No rejected reservations",
            },
        ];

        containers.forEach((container) => {
            if ($(container.id).children().length === 0) {
                $(container.id).html(this.getEmptyState(container.message));
            }
        });
    },

    getEmptyState(message) {
        return `
            <div class="empty-state">
                <i class="material-icons-sharp">inbox</i>
                <h3>${message}</h3>
                <p>Reservations will appear here when available</p>
            </div>
        `;
    },

    showNotification(message, type) {
        // You can integrate with your existing notification system
        if (type === "success") {
            alert("✓ " + message);
        } else {
            alert("✗ " + message);
        }
    },

    getStatusIcon(status) {
        const icons = {
            pending: "hourglass_empty",
            accepted: "check_circle",
            rejected: "cancel",
            completed: "done_all",
        };
        return icons[status] || "help";
    },
};

// Initialize when document is ready
$(document).ready(function () {
    ReservationsManager.init();
});

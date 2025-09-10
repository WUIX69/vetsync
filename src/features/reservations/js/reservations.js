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
        const statusInfo = this.getStatusInfo(reservation.status);
        const products = JSON.parse(reservation.products || "[]");
        const productNames = products.map((p) => p.name).join(", ");

        // FIXED: Calculate total amount using correct field names
        const totalAmount = products.reduce((sum, product) => {
            // Use total_price if available, otherwise calculate from price and qty
            const price =
                parseFloat(product.total_price) ||
                parseFloat(product.price) ||
                0;
            const quantity =
                parseInt(product.qty) || parseInt(product.quantity) || 0;

            // If total_price exists, use it directly; otherwise calculate
            if (product.total_price) {
                return sum + parseFloat(product.total_price);
            } else {
                return sum + price * quantity;
            }
        }, 0);

        return $(`
            <div class="reservation-card" data-id="${reservation.id}">
                <div class="reservation-header">
                    <h4>Reservation #${reservation.id}</h4>
                    <span class="reservation-status ${reservation.status}">${
            statusInfo.label
        }</span>
                </div>
                
                <div class="reservation-info">
                    <div>
                        <strong>Customer:</strong> ${reservation.firstname} ${
            reservation.lastname
        }
                    </div>
                    <div>
                        <strong>Date:</strong> ${reservation.formatted_date}
                    </div>
                    <div>
                        <strong>Time:</strong> ${reservation.formatted_time}
                    </div>
                    <div>
                        <strong>Total Amount:</strong> ₱${totalAmount.toFixed(
                            2
                        )}
                    </div>
                </div>

                <div class="reservation-products">
                    <h5>Products (${products.length})</h5>
                    ${products
                        .map((product) => {
                            // FIXED: Use correct field names for price and quantity
                            const price = parseFloat(product.price) || 0;
                            const quantity =
                                parseInt(product.qty) ||
                                parseInt(product.quantity) ||
                                0;
                            const subtotal = product.total_price
                                ? parseFloat(product.total_price)
                                : price * quantity;

                            return `
                        <div class="product-item">
                            <i class="box icon"></i>
                            <div class="product-details">
                                <div class="product-name">${product.name}</div>
                                <div class="product-info">
                                    <span class="quantity">Qty: ${quantity}</span>
                                    <span class="price">₱${price.toFixed(
                                        2
                                    )} each</span>
                                    <span class="subtotal">Subtotal: ₱${subtotal.toFixed(
                                        2
                                    )}</span>
                                </div>
                            </div>
                        </div>
                    `;
                        })
                        .join("")}
                </div>

                <div class="reservation-footer">
                    <div class="reservation-actions">
                        ${this.getActionButtons(reservation)}
                    </div>
                </div>
            </div>
        `);
    },

    getStatusInfo(status) {
        const statusMap = {
            pending: { label: "Pending", class: "pending" },
            accepted: { label: "Accepted", class: "accepted" },
            rejected: { label: "Rejected", class: "rejected" },
            completed: { label: "Ready for Pickup", class: "completed" },
        };
        return statusMap[status] || { label: "Unknown", class: "unknown" };
    },

    // Add new method to create summary card
    createSummaryCard() {
        const allReservations = this.reservations || [];
        const acceptedReservations = allReservations.filter(
            (r) => r.status === "accepted"
        );
        const completedReservations = allReservations.filter(
            (r) => r.status === "completed"
        );

        // Calculate totals for accepted reservations
        let totalAcceptedAmount = 0;
        let totalAcceptedItems = 0;
        const acceptedProducts = [];

        acceptedReservations.forEach((reservation) => {
            const products = JSON.parse(reservation.products || "[]");
            products.forEach((product) => {
                const price = parseFloat(product.price) || 0;
                const quantity = parseInt(product.quantity) || 0;
                const subtotal = price * quantity;

                totalAcceptedAmount += subtotal;
                totalAcceptedItems += quantity;

                // Add to accepted products list
                const existingProduct = acceptedProducts.find(
                    (p) => p.name === product.name
                );
                if (existingProduct) {
                    existingProduct.quantity += quantity;
                    existingProduct.total += subtotal;
                } else {
                    acceptedProducts.push({
                        name: product.name,
                        quantity: quantity,
                        price: price,
                        total: subtotal,
                    });
                }
            });
        });

        // Calculate totals for completed reservations
        let totalCompletedAmount = 0;
        let totalCompletedItems = 0;

        completedReservations.forEach((reservation) => {
            const products = JSON.parse(reservation.products || "[]");
            products.forEach((product) => {
                const price = parseFloat(product.price) || 0;
                const quantity = parseInt(product.quantity) || 0;
                const subtotal = price * quantity;

                totalCompletedAmount += subtotal;
                totalCompletedItems += quantity;
            });
        });

        return $(`
            <div class="summary-card">
                <div class="summary-header">
                    <h4><i class="chart bar icon"></i> Reservation Summary</h4>
                </div>
                
                <div class="summary-stats">
                    <div class="stat-item accepted">
                        <div class="stat-icon">
                            <i class="clock icon"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-label">Accepted</div>
                            <div class="stat-value">${
                                acceptedReservations.length
                            } reservations</div>
                            <div class="stat-amount">₱${totalAcceptedAmount.toFixed(
                                2
                            )}</div>
                        </div>
                    </div>
                    
                    <div class="stat-item completed">
                        <div class="stat-icon">
                            <i class="check circle icon"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-label">Ready for Pickup</div>
                            <div class="stat-value">${
                                completedReservations.length
                            } reservations</div>
                            <div class="stat-amount">₱${totalCompletedAmount.toFixed(
                                2
                            )}</div>
                        </div>
                    </div>
                </div>

                ${
                    acceptedProducts.length > 0
                        ? `
                <div class="accepted-products">
                    <h5><i class="box icon"></i> Accepted Products</h5>
                    <div class="products-list">
                        ${acceptedProducts
                            .map(
                                (product) => `
                            <div class="summary-product-item">
                                <div class="product-name">${product.name}</div>
                                <div class="product-details">
                                    <span>Qty: ${product.quantity}</span>
                                    <span>₱${product.total.toFixed(2)}</span>
                                </div>
                            </div>
                        `
                            )
                            .join("")}
                    </div>
                </div>
                `
                        : ""
                }
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

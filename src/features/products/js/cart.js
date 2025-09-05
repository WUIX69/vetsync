// Complete Cart functionality - All-in-one
const Cart = {
    init() {
        this.updateCartBadge();
        this.bindEvents();
        this.injectRequiredStyles(); // NEW: Inject styles early

        // Initialize cart page if we're on cart page
        if (window.location.pathname.includes("/cart.php")) {
            this.initCartPage();
            this.markCartAsViewed(); // Reset badge when viewing cart
        }
    },

    // NEW: Inject required CSS styles early
    injectRequiredStyles() {
        const requiredStyles = `
            <style id="cart-required-styles">
                /* Cart Selection Styles */
                .cart-selection-header {
                    background: linear-gradient(135deg, #f8fffe, #e8f5f3);
                    padding: 1rem 1.5rem;
                    border-radius: 12px;
                    margin-bottom: 1rem;
                    border: 1px solid #e8f5f3;
                }

                .select-all-label {
                    display: flex;
                    align-items: center;
                    gap: 0.75rem;
                    font-weight: 600;
                    color: #2c3e50;
                    cursor: pointer;
                    margin: 0;
                }

                .cart-checkbox-container {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }

                .cart-item-checkbox, #selectAllItems {
                    width: 18px;
                    height: 18px;
                    cursor: pointer;
                    accent-color: #21ba45;
                }

                .reserve-btn.disabled {
                    background: linear-gradient(135deg, #bdc3c7, #95a5a6) !important;
                    cursor: not-allowed !important;
                    transform: none !important;
                    box-shadow: none !important;
                }

                /* Cancel Reservation Button */
                .reservation-actions {
                    margin-top: 1.5rem;
                    padding-top: 1.5rem;
                    border-top: 1px solid #f0f8f5;
                    text-align: center;
                }

                .cancel-reservation-btn {
                    background: linear-gradient(135deg, #e74c3c, #c0392b);
                    color: white;
                    border: none;
                    padding: 0.75rem 1.5rem;
                    border-radius: 12px;
                    cursor: pointer;
                    font-weight: 600;
                    font-size: 0.9rem;
                    transition: all 0.3s ease;
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                }

                .cancel-reservation-btn:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 6px 20px rgba(231, 76, 60, 0.3);
                }

                .cancel-reservation-btn i {
                    font-size: 1rem;
                }

                /* Status Badge Styles */
                .status-badge {
                    padding: 0.4rem 0.8rem;
                    border-radius: 12px;
                    font-weight: 600;
                    font-size: 0.85rem;
                    display: inline-block;
                    text-align: center;
                }

                .status-badge.pending {
                    background: linear-gradient(135deg, #f39c12, #e67e22);
                    color: white;
                    border: 1px solid rgba(243, 156, 18, 0.3);
                }

                .status-badge.accepted {
                    background: linear-gradient(135deg, #21ba45, #16ab39);
                    color: white;
                    border: 1px solid rgba(33, 186, 69, 0.3);
                }

                .status-badge.rejected {
                    background: linear-gradient(135deg, #e74c3c, #c0392b);
                    color: white;
                    border: 1px solid rgba(231, 76, 60, 0.3);
                }

                .status-badge.cancelled {
                    background: linear-gradient(135deg, #95a5a6, #7f8c8d);
                    color: white;
                    border: 1px solid rgba(149, 165, 166, 0.3);
                }
            </style>
        `;

        if (!$("#cart-required-styles").length) {
            $("head").append(requiredStyles);
        }
    },

    bindEvents() {
        // Add to cart buttons (for product pages)
        $(document).on("click", ".add-to-cart-btn", (e) => {
            e.preventDefault();
            const productUuid = $(e.currentTarget)
                .closest(".product-listing")
                .data("product-uuid");
            const quantity = parseInt(
                $(e.currentTarget)
                    .closest(".product-listing")
                    .find(".quantity-selector")
                    .val() || 1
            );
            const size = $(e.currentTarget)
                .closest(".product-listing")
                .find(".size-selector")
                .val();

            this.addToCart(productUuid, quantity, size);
        });

        // Size selector for add to cart
        $(document).on("click", ".size-option", function () {
            $(this).siblings().removeClass("active");
            $(this).addClass("active");
        });

        // Quantity controls (cart page)
        $(document).on("click", ".update-quantity", (e) => {
            e.preventDefault();
            const productUuid = $(e.currentTarget).data("product-uuid");
            const size = $(e.currentTarget).data("size");
            const action = $(e.currentTarget).data("action");
            const currentQty = parseInt(
                $(e.currentTarget).siblings(".quantity-display").text() || 1
            );

            let newQty =
                action === "increase" ? currentQty + 1 : currentQty - 1;
            if (newQty < 1) newQty = 1;

            this.updateQuantity(productUuid, size, newQty);
        });

        // Remove item (cart page)
        $(document).on("click", ".remove-item", (e) => {
            e.preventDefault();
            const productUuid = $(e.currentTarget).data("product-uuid");
            const size = $(e.currentTarget).data("size");
            this.removeItem(productUuid, size);
        });

        // Reserve button (cart page)
        $(document).on("click", ".reserve-btn", (e) => {
            e.preventDefault();
            this.openReservationModal();
        });

        // NEW: Select/Deselect cart items
        $(document).on("change", ".cart-item-checkbox", () => {
            this.updateSelectedItemsSummary();
        });

        // NEW: Select all / Deselect all
        $(document).on("click", "#selectAllItems", (e) => {
            const isChecked = $(e.currentTarget).is(":checked");
            $(".cart-item-checkbox").prop("checked", isChecked);
            this.updateSelectedItemsSummary();
        });

        // NEW: Cancel pending reservation
        $(document).on("click", ".cancel-reservation-btn", (e) => {
            const reservationId = $(e.currentTarget).data("reservation-id");
            this.cancelReservation(reservationId);
        });

        // FIXED: Submit reservation - Handle both form submit and button click
        $(document).on("submit", "#reservationForm", (e) => {
            e.preventDefault();
            if (this.validateReservationForm()) {
                this.submitReservation();
            }
        });

        // FIXED: Also handle the submit button click specifically
        $(document).on("click", "#reservationModal .submit.button", (e) => {
            e.preventDefault();
            if (this.validateReservationForm()) {
                this.submitReservation();
            }
        });

        $(document).on("click", ".cart-link", () => {
            // Reset badge when cart is clicked
            $("#cartBadge").hide();
            localStorage.setItem("cartLastViewed", Date.now());
            localStorage.setItem("reservationsLastViewed", Date.now()); // Also reset reservations badge
        });
    },

    // Initialize cart page
    initCartPage() {
        this.loadCartItems();
        this.loadUserReservations();
        this.bindTabEvents();
    },

    // Bind tab events
    bindTabEvents() {
        $(document).on("click", ".cart-tab", (e) => {
            const tab = $(e.currentTarget).data("tab");
            this.switchTab(tab);
        });
    },

    // Switch between tabs
    switchTab(tab) {
        // Update active tab
        $(".cart-tab").removeClass("active");
        $(`.cart-tab[data-tab="${tab}"]`).addClass("active");

        // Update active content
        $(".tab-content").removeClass("active");
        $(`#${tab}Tab`).addClass("active");

        // Update summary title based on active tab
        if (tab === "active") {
            $("#summaryTitle").text("Order Summary");
        } else {
            $("#summaryTitle").text("Reservation Summary");
            this.updateReservationSummary(tab);
        }

        // Mark reservations as viewed when switching to reservation tabs
        if (tab !== "active") {
            localStorage.setItem("reservationsLastViewed", Date.now());
            this.updateCartBadge(); // Refresh badge
        }
    },

    // Load user reservations
    loadUserReservations() {
        $.ajax({
            url: "/src/features/reservations/api/user-reservations.php",
            method: "GET",
            success: (response) => {
                if (response.success) {
                    this.renderReservations(response.data);
                }
            },
            error: (xhr, status, error) => {
                console.error("Error loading reservations:", error);
            },
        });
    },

    // Render reservations in tabs
    renderReservations(reservations) {
        const pendingReservations = reservations.filter(
            (r) => r.status === "pending"
        );
        const acceptedReservations = reservations.filter(
            (r) => r.status === "accepted"
        );
        // FIXED: Include user-cancelled reservations in rejected tab
        const rejectedReservations = reservations.filter(
            (r) => r.status === "rejected"
        );

        // Update counts
        $("#pendingCount").text(pendingReservations.length);
        $("#acceptedCount").text(acceptedReservations.length);
        $("#rejectedCount").text(rejectedReservations.length);

        // Render each tab
        this.renderReservationTab("pending", pendingReservations);
        this.renderReservationTab("accepted", acceptedReservations);
        this.renderReservationTab("rejected", rejectedReservations);
    },

    renderReservationTab(status, reservations) {
        const container = $(`#${status}Reservations`);
        container.empty();

        if (reservations.length === 0) {
            container.html(this.getEmptyState(status));
            return;
        }

        reservations.forEach((reservation) => {
            const card = this.createReservationCard(reservation);
            container.append(card);
        });
    },

    // ENHANCED: Create reservation card with cancel button for pending and proper display for cancelled items
    createReservationCard(reservation) {
        const products = JSON.parse(reservation.products || "[]");
        const statusClass = reservation.status.toLowerCase();

        let productsHtml = "";
        products.forEach((product) => {
            productsHtml += `
                <div class="product-item">
                    <div>
                        <div class="product-name">${product.name}</div>
                        <div class="product-meta">Size: ${product.size.toUpperCase()} | Qty: ${
                product.qty
            } | ₱${product.total_price}</div>
                    </div>
                    <div class="product-price">₱${product.total_price}</div>
                </div>
            `;
        });

        // FIXED: Show rejection reason, but distinguish between user cancellation and admin rejection
        const rejectionReason =
            reservation.status === "rejected" && reservation.rejection_reason
                ? `<div class="detail-item">
                <i class='bx ${
                    reservation.rejection_reason === "Cancelled by user"
                        ? "bx-user-x"
                        : "bx-message-square-error"
                }'></i>
                <div>
                    <strong>${
                        reservation.rejection_reason === "Cancelled by user"
                            ? "Cancellation"
                            : "Rejection Reason"
                    }:</strong>
                    <span>${
                        reservation.rejection_reason === "Cancelled by user"
                            ? "You cancelled this reservation"
                            : reservation.rejection_reason
                    }</span>
                </div>
            </div>`
                : "";

        // NEW: Add cancel button for pending reservations only
        const cancelButton =
            reservation.status === "pending"
                ? `<div class="reservation-actions">
                <button class="cancel-reservation-btn" data-reservation-id="${reservation.id}">
                    <i class='bx bx-x'></i> Cancel Reservation
                </button>
            </div>`
                : "";

        // FIXED: Show appropriate status badge
        const displayStatus =
            reservation.status === "rejected" &&
            reservation.rejection_reason === "Cancelled by user"
                ? "CANCELLED"
                : reservation.status.toUpperCase();

        const displayStatusClass =
            reservation.status === "rejected" &&
            reservation.rejection_reason === "Cancelled by user"
                ? "cancelled"
                : statusClass;

        return $(`
            <div class="reservation-item">
                <div class="reservation-header">
                    <div>
                        <div class="reservation-id">Reservation #${
                            reservation.id
                        }</div>
                        <div class="reservation-date">
                            <i class='bx bx-calendar'></i>
                            ${reservation.formatted_date} at ${
            reservation.formatted_time
        }
                        </div>
                    </div>
                    <div class="status-badge ${displayStatusClass}">${displayStatus}</div>
                </div>
                
                <div class="reservation-details">
                    <div class="detail-item">
                        <i class='bx bx-package'></i>
                        <span>Products: ${products.length} item(s)</span>
                    </div>
                    <div class="detail-item">
                        <i class='bx bx-money'></i>
                        <span>₱${parseFloat(
                            reservation.total_amount || 0
                        ).toFixed(2)}</span>
                    </div>
                    ${rejectionReason}
                </div>
                
                <div class="reservation-products">
                    <h6><i class='bx bx-package'></i> Products (${
                        products.length
                    })</h6>
                    <div class="product-list">
                        ${productsHtml}
                    </div>
                </div>
                
                ${cancelButton}
            </div>
        `);
    },

    // NEW: Cancel reservation function
    cancelReservation(reservationId) {
        if (!confirm("Are you sure you want to cancel this reservation?")) {
            return;
        }

        $.ajax({
            url: "/src/features/reservations/api/reservations.php",
            method: "POST",
            data: {
                action: "cancel_reservation",
                reservation_id: reservationId,
            },
            success: (response) => {
                if (response.success) {
                    this.showNotification(
                        "Reservation cancelled successfully",
                        "success"
                    );
                    this.loadUserReservations(); // Refresh reservations
                    this.updateCartBadge(); // Update badge
                } else {
                    this.showNotification(
                        response.message || "Failed to cancel reservation",
                        "error"
                    );
                }
            },
            error: (xhr, status, error) => {
                console.error("Cancel reservation error:", {
                    xhr,
                    status,
                    error,
                });
                this.showNotification("Failed to cancel reservation", "error");
            },
        });
    },

    updateReservationSummary(status) {
        // This updates the summary sidebar when viewing reservations
        const reservations =
            status === "pending"
                ? JSON.parse(
                      localStorage.getItem("pendingReservations") || "[]"
                  )
                : status === "accepted"
                ? JSON.parse(
                      localStorage.getItem("acceptedReservations") || "[]"
                  )
                : JSON.parse(
                      localStorage.getItem("rejectedReservations") || "[]"
                  );

        let totalAmount = 0;
        let totalItems = 0;

        reservations.forEach((reservation) => {
            totalAmount += parseFloat(reservation.total_amount || 0);
            const products = JSON.parse(reservation.products || "[]");
            totalItems += products.length;
        });

        const summaryHtml = `
            <div class="summary-row">
                <span>Reservations (${reservations.length}):</span>
                <span>${reservations.length}</span>
            </div>
            <div class="summary-row total">
                <span>Total Amount:</span>
                <span>₱${totalAmount.toFixed(2)}</span>
            </div>
        `;

        $("#cartSummary .summary-content").html(summaryHtml);
    },

    getEmptyState(status) {
        const messages = {
            pending: {
                icon: "bx-time",
                title: "No Pending Reservations",
                message: "You have no pending reservations at the moment.",
            },
            accepted: {
                icon: "bx-check-circle",
                title: "No Accepted Reservations",
                message: "You have no accepted reservations yet.",
            },
            rejected: {
                icon: "bx-x-circle",
                title: "No Rejected Reservations",
                message: "You have no rejected reservations.",
            },
        };

        const msg = messages[status];
        return `
            <div class="empty-state">
                <i class='bx ${msg.icon}'></i>
                <h3>${msg.title}</h3>
                <p>${msg.message}</p>
            </div>
        `;
    },

    markCartAsViewed() {
        localStorage.setItem("cartLastViewed", Date.now());
        localStorage.setItem("reservationsLastViewed", Date.now());
    },

    // Add to cart
    addToCart(productUuid, quantity, size = "m") {
        const formData = new FormData();
        formData.append("action", "add");
        formData.append("product_uuid", productUuid);
        formData.append("qty", quantity);
        formData.append("size", size);

        $.ajax({
            url: apiUrl("products") + "cart.php",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: (response) => {
                if (response.success) {
                    this.showNotification(
                        "Product added to cart successfully!",
                        "success"
                    );
                    this.updateCartBadge();
                } else {
                    this.showNotification(response.message, "error");
                }
            },
            error: (xhr, status, error) => {
                console.error("Add to cart error:", { xhr, status, error });
                this.showNotification("Failed to add product to cart", "error");
            },
        });
    },

    // Update cart badge - ENHANCED VERSION
    updateCartBadge() {
        $.ajax({
            url: apiUrl("products") + "cart.php",
            method: "GET",
            data: { action: "count" },
            success: (response) => {
                if (response.success) {
                    const cartLastViewed =
                        localStorage.getItem("cartLastViewed") || 0;
                    const cartItems = response.items || [];

                    // Count items added after last view
                    const newCartItemsCount = cartItems.filter((item) => {
                        const itemTimestamp = new Date(
                            item.created_at
                        ).getTime();
                        return itemTimestamp > cartLastViewed;
                    }).length;

                    // ONLY show cart items in cart badge
                    if (newCartItemsCount > 0) {
                        $("#cartBadge").text(newCartItemsCount).show();
                    } else {
                        $("#cartBadge").hide();
                    }
                } else {
                    $("#cartBadge").hide();
                }
            },
            error: () => {
                $("#cartBadge").hide();
            },
        });
    },

    // Load cart items (cart page)
    loadCartItems() {
        $.ajax({
            url: apiUrl("products") + "cart.php",
            method: "GET",
            data: { action: "items" },
            success: (response) => {
                if (response.success && response.data.length > 0) {
                    this.renderCartItems(response.data);
                    this.updateSummary(response.data);
                    // After loading, update active count
                    $("#activeCount").text(
                        response.data ? response.data.length : 0
                    );
                } else {
                    this.showEmptyCart();
                }
            },
            error: () => {
                this.showEmptyCart();
            },
        });
    },

    // ENHANCED: Render cart items with checkboxes for selection
    renderCartItems(items) {
        if (!items || items.length === 0) {
            this.showEmptyCart();
            return;
        }

        let html = `
            <div class="cart-selection-header">
                <label class="select-all-label">
                    <input type="checkbox" id="selectAllItems" checked>
                    <span>Select all items for reservation</span>
                </label>
            </div>
        `;

        items.forEach((item) => {
            const price = item.dc_price || item.og_price;
            const totalPrice = (parseFloat(price) * item.qty).toFixed(2);
            const itemKey = `${item.product_uuid}-${item.size}`;

            html += `
                <div class="cart-item">
                    <div class="row align-items-center">
                        <div class="col-md-1">
                            <div class="cart-checkbox-container">
                                <input type="checkbox" 
                                       class="cart-item-checkbox" 
                                       value="${itemKey}"
                                       data-product-uuid="${item.product_uuid}"
                                       data-size="${item.size}"
                                       data-price="${item.total_price}"
                                       data-qty="${item.qty}"
                                       data-name="${item.name}"
                                       checked>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <img src="${item.image}" alt="${
                item.name
            }" class="cart-item-image">
                        </div>
                        <div class="col-md-3">
                            <div class="cart-item-details">
                                <h5>${item.name}</h5>
                                <div class="mb-2">
                                    <span class="size-badge">Size: ${item.size.toUpperCase()}</span>
                                </div>
                                <div class="cart-item-price">₱${price}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="quantity-controls">
                                <button class="ui mini button update-quantity" 
                                        data-product-uuid="${item.product_uuid}"
                                        data-size="${item.size}"
                                        data-action="decrease">
                                    <i class="minus icon"></i>
                                </button>
                                <span class="quantity-display mx-2">${
                                    item.qty
                                }</span>
                                <button class="ui mini button update-quantity"
                                        data-product-uuid="${item.product_uuid}"
                                        data-size="${item.size}"
                                        data-action="increase">
                                    <i class="plus icon"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <strong>₱${totalPrice}</strong>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button class="ui red mini button remove-item"
                                    data-product-uuid="${item.product_uuid}"
                                    data-size="${item.size}">
                                <i class="trash icon"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        $("#cartItems").html(html);
        this.updateSelectedItemsSummary();
    },

    // NEW: Update summary based on selected items
    updateSelectedItemsSummary() {
        const selectedCheckboxes = $(".cart-item-checkbox:checked");
        let totalItems = 0;
        let totalAmount = 0;

        selectedCheckboxes.each(function () {
            totalItems += parseInt($(this).data("qty"));
            totalAmount += parseFloat($(this).data("price"));
        });

        const selectedCount = selectedCheckboxes.length;
        const totalCount = $(".cart-item-checkbox").length;

        // Update "Select All" checkbox state
        $("#selectAllItems").prop(
            "checked",
            selectedCount === totalCount && totalCount > 0
        );
        $("#selectAllItems").prop(
            "indeterminate",
            selectedCount > 0 && selectedCount < totalCount
        );

        const html = `
            <div class="summary-row">
                <span>Selected Items (${selectedCount}):</span>
                <span>${totalItems} items</span>
            </div>
            <div class="summary-row total">
                <span>Total:</span>
                <span>₱${totalAmount.toFixed(2)}</span>
            </div>
            <button class="reserve-btn ${
                selectedCount === 0 ? "disabled" : ""
            }" ${selectedCount === 0 ? "disabled" : ""}>
                <i class='bx bx-calendar-check'></i> Make Reservation (${selectedCount} items)
            </button>
        `;

        $("#cartSummary .summary-content").html(html);
    },

    // Update summary (keep original for compatibility)
    updateSummary(items) {
        this.updateSelectedItemsSummary();
    },

    // Show empty cart
    showEmptyCart() {
        const emptyHtml = `
            <div class="empty-state">
                <i class='bx bx-shopping-bag'></i>
                <h3>Your cart is empty</h3>
                <p>Add some products to get started!</p>
            </div>
        `;
        $("#cartItems").html(emptyHtml);
        $("#cartSummary .summary-content").html("<p>No items in cart</p>");
        $("#activeCount").text("0");
    },

    // Remove item
    removeItem(productUuid, size) {
        let url =
            apiUrl("products") +
            "cart.php?product_uuid=" +
            encodeURIComponent(productUuid);
        if (size) {
            url += "&size=" + encodeURIComponent(size);
        }

        $.ajax({
            url: url,
            method: "DELETE",
            success: (response) => {
                if (response.success) {
                    this.loadCartItems();
                    this.updateCartBadge();
                    this.showNotification("Item removed from cart", "success");
                } else {
                    this.showNotification(response.message, "error");
                }
            },
            error: (xhr, status, error) => {
                console.error("Remove item error:", { xhr, status, error });
                this.showNotification("Failed to remove item", "error");
            },
        });
    },

    // Update quantity
    updateQuantity(productUuid, size, qty) {
        $.ajax({
            url: apiUrl("products") + "cart.php",
            method: "PUT",
            data: {
                product_uuid: productUuid,
                size: size,
                qty: qty,
            },
            success: (response) => {
                if (response.success) {
                    this.loadCartItems();
                    this.updateCartBadge();
                } else {
                    this.showNotification(response.message, "error");
                }
            },
            error: () => {
                this.showNotification("Failed to update quantity", "error");
            },
        });
    },

    // ENHANCED: Open reservation modal with selected items only
    openReservationModal() {
        const selectedCheckboxes = $(".cart-item-checkbox:checked");

        if (selectedCheckboxes.length === 0) {
            this.showNotification(
                "Please select at least one item to reserve",
                "error"
            );
            return;
        }

        // Get selected items data
        const selectedItems = [];
        selectedCheckboxes.each(function () {
            selectedItems.push({
                product_uuid: $(this).data("product-uuid"),
                size: $(this).data("size"),
                qty: $(this).data("qty"),
                name: $(this).data("name"),
                total_price: $(this).data("price"),
            });
        });

        this.populateReservationModal(selectedItems);
        $("#reservationModal").modal("show");
    },

    // Populate reservation modal with selected items
    populateReservationModal(items) {
        let productsHtml = "";
        let totalItems = 0;
        let totalAmount = 0;

        items.forEach((item) => {
            totalItems += parseInt(item.qty);
            totalAmount += parseFloat(item.total_price);

            productsHtml += `
                <div class="product-item">
                    <div>
                        <div class="name">${item.name}</div>
                        <div class="details">Size: ${item.size.toUpperCase()} | Qty: ${
                item.qty
            } | ₱${item.total_price}</div>
                    </div>
                </div>
            `;
        });

        $("#selectedProducts").html(productsHtml);
        $("#totalItems").text(totalItems);
        $("#totalAmount").text(`₱${totalAmount.toFixed(2)}`);

        // Store selected items for submission
        this.selectedItemsForReservation = items;
    },

    // ENHANCED: Submit reservation with selected items only
    submitReservation() {
        console.log("Starting reservation submission..."); // Debug log

        // Use stored selected items instead of fetching all cart items
        const selectedItems = this.selectedItemsForReservation;

        if (!selectedItems || selectedItems.length === 0) {
            this.showNotification("No items selected for reservation", "error");
            return;
        }

        // Get form data
        const formData = $("#reservationForm").serializeArray();
        const reservationData = {};
        formData.forEach((field) => {
            reservationData[field.name] = field.value;
        });

        console.log("Form data:", reservationData); // Debug log

        // Add selected products and calculate total
        reservationData.products = JSON.stringify(selectedItems);

        const totalAmount = selectedItems.reduce(
            (sum, item) => sum + (parseFloat(item.total_price) || 0),
            0
        );
        reservationData.total_amount = totalAmount;

        console.log("Final reservation data:", reservationData); // Debug log

        // Submit reservation
        $.ajax({
            url: "/src/features/reservations/api/reservations.php",
            method: "POST",
            data: reservationData,
            success: (response) => {
                console.log("Reservation response:", response); // Debug log

                if (response.success) {
                    // CHANGED: Use plain notification instead of fancy center modal
                    this.showNotification(
                        "🎉 Reservation submitted successfully! Please wait for admin approval.",
                        "success"
                    );

                    // Hide modal after showing notification
                    setTimeout(() => {
                        $("#reservationModal").modal("hide");
                    }, 500);

                    // Remove selected items from cart
                    this.removeSelectedItemsFromCart();

                    // Redirect after longer delay to show notification
                    setTimeout(() => {
                        window.location.href = "/src/app/user/products.php";
                    }, 4000); // Increased to 4 seconds
                } else {
                    this.showNotification(
                        response.message || "Failed to submit reservation",
                        "error"
                    );
                }
            },
            error: (xhr, status, error) => {
                console.error("Reservation error:", {
                    xhr,
                    status,
                    error,
                    response: xhr.responseText,
                }); // Enhanced debug log
                this.showNotification(
                    "Failed to submit reservation. Please check your input and try again.",
                    "error"
                );
            },
        });
    },

    // NEW: Remove selected items from cart after successful reservation
    removeSelectedItemsFromCart() {
        const selectedItems = this.selectedItemsForReservation;

        selectedItems.forEach((item) => {
            // Remove each selected item from cart
            let url =
                apiUrl("products") +
                "cart.php?product_uuid=" +
                encodeURIComponent(item.product_uuid);
            if (item.size) {
                url += "&size=" + encodeURIComponent(item.size);
            }

            $.ajax({
                url: url,
                method: "DELETE",
                success: (response) => {
                    console.log(`Removed ${item.name} from cart`);
                },
                error: (xhr, status, error) => {
                    console.error(
                        `Failed to remove ${item.name} from cart:`,
                        error
                    );
                },
            });
        });

        // Refresh cart display after a short delay
        setTimeout(() => {
            this.loadCartItems();
            this.updateCartBadge();
        }, 1000);
    },

    // ENHANCED: Validate reservation form
    validateReservationForm() {
        console.log("Validating reservation form..."); // Debug log

        const form = $("#reservationForm")[0];
        const preferredDate = $("input[name='preferred_date']").val();
        const preferredTime = $("input[name='preferred_time']").val();

        // Check if required fields are filled
        if (!preferredDate) {
            this.showNotification("Please select a preferred date", "error");
            return false;
        }

        if (!preferredTime) {
            this.showNotification("Please select a preferred time", "error");
            return false;
        }

        // Check if date is not in the past
        const selectedDate = new Date(preferredDate);
        const today = new Date();
        today.setHours(0, 0, 0, 0); // Reset time to start of day for comparison

        if (selectedDate < today) {
            this.showNotification("Please select a future date", "error");
            return false;
        }

        console.log("Form validation passed"); // Debug log
        return true;
    },

    // ENHANCED: Show notification with better styling and positioning
    showNotification(message, type = "info") {
        const notification = $(`
            <div class="custom-notification ${type}">
                <div class="notification-content">
                    <i class="notification-icon ${this.getNotificationIcon(
                        type
                    )}"></i>
                    <span class="notification-message">${message}</span>
                </div>
            </div>
        `);

        $("body").append(notification);

        // Add CSS styles programmatically to ensure they apply
        const notificationStyles = `
            <style id="notification-styles">
                .custom-notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    padding: 1rem 1.5rem;
                    border-radius: 12px;
                    font-weight: 600;
                    z-index: 9999;
                    min-width: 300px;
                    max-width: 400px;
                    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
                    animation: slideInRight 0.4s ease-out;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                }
                
                .custom-notification.success {
                    background: linear-gradient(135deg, #21ba45, #16ab39);
                    color: white;
                    border-left: 4px solid #16ab39;
                }
                
                .custom-notification.error {
                    background: linear-gradient(135deg, #e74c3c, #c0392b);
                    color: white;
                    border-left: 4px solid #c0392b;
                }
                
                .custom-notification.info {
                    background: linear-gradient(135deg, #3498db, #2980b9);
                    color: white;
                    border-left: 4px solid #2980b9;
                }
                
                .notification-content {
                    display: flex;
                    align-items: center;
                    gap: 0.75rem;
                }
                
                .notification-icon {
                    font-size: 1.2rem;
                    flex-shrink: 0;
                }
                
                .notification-message {
                    flex: 1;
                    line-height: 1.4;
                }
                
                @keyframes slideInRight {
                    from {
                        opacity: 0;
                        transform: translateX(100px);
                    }
                    to {
                        opacity: 1;
                        transform: translateX(0);
                    }
                }
                
                @keyframes slideOutRight {
                    from {
                        opacity: 1;
                        transform: translateX(0);
                    }
                    to {
                        opacity: 0;
                        transform: translateX(100px);
                    }
                }

                /* Cart Selection Styles */
                .cart-selection-header {
                    background: linear-gradient(135deg, #f8fffe, #e8f5f3);
                    padding: 1rem 1.5rem;
                    border-radius: 12px;
                    margin-bottom: 1rem;
                    border: 1px solid #e8f5f3;
                }

                .select-all-label {
                    display: flex;
                    align-items: center;
                    gap: 0.75rem;
                    font-weight: 600;
                    color: #2c3e50;
                    cursor: pointer;
                    margin: 0;
                }

                .cart-checkbox-container {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }

                .cart-item-checkbox, #selectAllItems {
                    width: 18px;
                    height: 18px;
                    cursor: pointer;
                    accent-color: #21ba45;
                }

                .reserve-btn.disabled {
                    background: linear-gradient(135deg, #bdc3c7, #95a5a6) !important;
                    cursor: not-allowed !important;
                    transform: none !important;
                    box-shadow: none !important;
                }

                /* Cancel Reservation Button */
                .reservation-actions {
                    margin-top: 1.5rem;
                    padding-top: 1.5rem;
                    border-top: 1px solid #f0f8f5;
                    text-align: center;
                }

                .cancel-reservation-btn {
                    background: linear-gradient(135deg, #e74c3c, #c0392b);
                    color: white;
                    border: none;
                    padding: 0.75rem 1.5rem;
                    border-radius: 12px;
                    cursor: pointer;
                    font-weight: 600;
                    font-size: 0.9rem;
                    transition: all 0.3s ease;
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                }

                .cancel-reservation-btn:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 6px 20px rgba(231, 76, 60, 0.3);
                }

                .cancel-reservation-btn i {
                    font-size: 1rem;
                }

                /* Status Badge Styles */
                .status-badge {
                    padding: 0.4rem 0.8rem;
                    border-radius: 12px;
                    font-weight: 600;
                    font-size: 0.85rem;
                    display: inline-block;
                    text-align: center;
                }

                .status-badge.pending {
                    background: linear-gradient(135deg, #f8fffe, #e8f5f3);
                    color: #2c3e50;
                    border: 1px solid #e8f5f3;
                }

                .status-badge.accepted {
                    background: linear-gradient(135deg, #e8f5f3, #f8fffe);
                    color: #21ba45;
                    border: 1px solid #e8f5f3;
                }

                .status-badge.rejected {
                    background: linear-gradient(135deg, #f8fffe, #e8f5f3);
                    color: #e74c3c;
                    border: 1px solid #e8f5f3;
                }

                .status-badge.cancelled {
                    background: linear-gradient(135deg, #95a5a6, #7f8c8d);
                    color: white;
                    border-color: rgba(149, 165, 166, 0.3);
                }
            </style>
        `;

        if (!$("#notification-styles").length) {
            $("head").append(notificationStyles);
        }

        setTimeout(() => {
            notification.css("animation", "slideOutRight 0.4s ease-out");
            setTimeout(() => notification.remove(), 400);
        }, 5000); // Show for 5 seconds
    },

    // Helper function for notification icons
    getNotificationIcon(type) {
        const icons = {
            success: "bx bx-check-circle",
            error: "bx bx-error-circle",
            info: "bx bx-info-circle",
            warning: "bx bx-error",
        };
        return icons[type] || icons.info;
    },

    // Mark cart as viewed (when user clicks cart link)
    markCartAsViewed() {
        localStorage.setItem("cartLastViewed", Date.now());
        localStorage.setItem("reservationsLastViewed", Date.now());
        this.updateCartBadge(); // Refresh badge
    },

    // Add clear cart method
    clearCart() {
        $.ajax({
            url: apiUrl("products") + "cart.php",
            method: "DELETE",
            data: { product_uuid: "all" },
            success: (response) => {
                if (response.success) {
                    this.loadCartItems();
                    this.updateCartBadge();
                }
            },
        });
    },
};

// Initialize cart when DOM is ready
$(document).ready(() => Cart.init());

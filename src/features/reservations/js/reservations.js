$(document).ready(function () {
    // Initialize
    loadReservations();

    // Clean tab navigation - completely rewrite
    $(document).on("click", ".nav-pills .nav-link", function (e) {
        e.preventDefault();
        e.stopPropagation();

        console.log("Tab clicked:", $(this).attr("data-target"));

        // Remove active from all nav links
        $(".nav-pills .nav-link").removeClass("active");

        // Add active to clicked nav link
        $(this).addClass("active");

        // Hide all tab contents
        $(".tab-content").removeClass("active").hide();

        // Show target tab content
        const target = $(this).attr("data-target");
        $(`#${target}-tab`).addClass("active").show();

        console.log("Switched to tab:", target);
    });
});

let allReservations = [];

function loadReservations() {
    console.log("Loading reservations...");

    $.get("/src/features/reservations/api/reservations.php")
        .done(function (response) {
            console.log("Raw API response:", response); // Debug API response

            if (response.success) {
                allReservations = response.data;
                console.log("Parsed reservations:", allReservations); // Debug parsed data

                // Check the first reservation to see the data structure
                if (allReservations.length > 0) {
                    console.log(
                        "First reservation sample:",
                        allReservations[0]
                    );
                }

                renderReservations(allReservations);
                updateTabCounts();
            } else {
                showError("Failed to load reservations: " + response.message);
            }
        })
        .fail(function (xhr, status, error) {
            console.error("AJAX Error loading reservations:", {
                xhr,
                status,
                error,
            });
            showError("Failed to load reservations. Please try again.");
        });
}

// Remove or comment out the duplicate setupTabNavigation function
/*
function setupTabNavigation() {
    // This function is causing conflicts - remove it
}
*/

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

function renderReservations(reservations) {
    console.log("Rendering reservations:", reservations);

    // Clear all tables
    const statuses = [
        "all",
        "pending",
        "accepted",
        "ready_for_pickup",
        "picked_up",
        "rejected",
        "cancelled",
    ];
    statuses.forEach((status) => {
        $(`#${status}-reservations`).empty();
    });

    // Group reservations by user, date, and time
    const grouped = {};
    reservations.forEach((reservation) => {
        const groupKey = `${reservation.user_uuid}_${reservation.preferred_date}_${reservation.preferred_time}_${reservation.status}`;

        if (!grouped[groupKey]) {
            grouped[groupKey] = [];
        }
        grouped[groupKey].push(reservation);
    });

    // Update tab counts
    const statusCounts = {
        all: Object.keys(grouped).length, // Count groups, not individual reservations
        pending: 0,
        accepted: 0,
        ready_for_pickup: 0,
        picked_up: 0,
        rejected: 0,
        cancelled: 0,
    };

    Object.values(grouped).forEach(function (group) {
        const row =
            group.length > 1
                ? createGroupedReservationRow(group)
                : createReservationRow(group[0]);

        // Add to all reservations
        $("#all-reservations").append(row);

        // Add to specific status tab
        const status = group[0].status;
        if (status === "ready_for_pickup" || status === "completed") {
            const readyRow =
                group.length > 1
                    ? createGroupedReservationRow(group)
                    : createReservationRow(group[0]);
            $("#ready_for_pickup-reservations").append(readyRow);
            statusCounts.ready_for_pickup++;
        } else {
            const statusRow =
                group.length > 1
                    ? createGroupedReservationRow(group)
                    : createReservationRow(group[0]);
            $(`#${status}-reservations`).append(statusRow);
            if (statusCounts.hasOwnProperty(status)) {
                statusCounts[status]++;
            }
        }
    });

    // Update tab labels with counts
    Object.keys(statusCounts).forEach((status) => {
        const displayStatus =
            status === "ready_for_pickup"
                ? "Ready for Pickup"
                : status === "picked_up"
                ? "Picked Up"
                : status.charAt(0).toUpperCase() + status.slice(1);

        $(`.nav-link[data-target="${status}"]`).html(`
            <i class="material-icons-sharp">${getStatusIcon(status)}</i>
            ${displayStatus} (${statusCounts[status]})
        `);
    });
}

function createReservationRow(reservation) {
    console.log("Creating row for reservation:", reservation);

    // Handle products properly from the API
    let products = [];
    if (
        reservation.products_array &&
        Array.isArray(reservation.products_array)
    ) {
        products = reservation.products_array;
    } else if (reservation.products) {
        try {
            products =
                typeof reservation.products === "string"
                    ? JSON.parse(reservation.products)
                    : reservation.products;
        } catch (e) {
            console.error("Error parsing products:", e);
            products = [];
        }
    }

    // ✅ FIX: Use the correct field names from API response
    let customerName = "Unknown Customer";
    let customerEmail = "No email provided";
    let customerInitial = "U";

    if (reservation.user_name && reservation.user_name.trim() !== "") {
        customerName = reservation.user_name;
        customerInitial = customerName.charAt(0).toUpperCase();
    }

    if (reservation.user_email && reservation.user_email.trim() !== "") {
        customerEmail = reservation.user_email;
    }

    // ✅ SIMPLE: Use profile image or colorful avatar
    let profileImageHtml = "";
    if (reservation.profile_image) {
        if (reservation.profile_image.includes("ui-avatars.com")) {
            // It's a generated avatar
            profileImageHtml = `<div class="customer-avatar" style="
                width: 36px; 
                height: 36px; 
                border-radius: 50%; 
                background-image: url('${reservation.profile_image}');
                background-size: cover;
                background-position: center;
                flex-shrink: 0;
            "></div>`;
        } else {
            // It's a real uploaded image
            profileImageHtml = `<div class="customer-avatar" style="
                width: 36px; 
                height: 36px; 
                border-radius: 50%; 
                background-image: url('${reservation.profile_image}');
                background-size: cover;
                background-position: center;
                border: 2px solid #e9ecef;
                flex-shrink: 0;
            "></div>`;
        }
    } else {
        // Fallback to colored initial
        profileImageHtml = `<div class="customer-avatar" style="
            width: 36px; 
            height: 36px; 
            border-radius: 50%; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            color: white; 
            font-weight: bold; 
            font-size: 0.9rem;
            flex-shrink: 0;
        ">${customerInitial}</div>`;
    }

    // Create products list with better price handling
    let productsList = "";
    let calculatedTotal = 0;

    if (products && products.length > 0) {
        products.forEach((product, index) => {
            const productName =
                product.name ||
                product.product_name ||
                product.title ||
                `Product ${index + 1}`;
            const quantity = parseInt(
                product.quantity || product.qty || product.amount || 1
            );

            let price = 0;
            if (product.price) {
                price = parseFloat(product.price);
            } else if (product.unit_price) {
                price = parseFloat(product.unit_price);
            } else if (product.total_price) {
                price = parseFloat(product.total_price) / quantity;
            }

            const lineTotal = price * quantity;
            calculatedTotal += lineTotal;

            productsList += `
                <div class="product-item">
                    <div class="product-name">${productName}</div>
                    <div class="product-details">Qty: ${quantity} | ₱${price.toFixed(
                2
            )} = ₱${lineTotal.toFixed(2)}</div>
                </div>
            `;
        });
    } else {
        productsList = '<div class="product-item">No products found</div>';
    }

    const totalAmount =
        calculatedTotal > 0
            ? calculatedTotal
            : parseFloat(reservation.total_amount || 0);

    // Create row with profile image
    const rowId = `reservation-${reservation.id}`;
    const row = $(`
        <tr id="${rowId}" data-reservation-id="${reservation.id}">
            <td>
                <div style="font-weight: 600; color: #2c3e50;">${
                    reservation.formatted_date || "No date"
                }</div>
                <div style="color: #6c757d; font-size: 0.85rem;">${
                    reservation.formatted_time || "No time"
                }</div>
            </td>
            <td>
                <div class="customer-info" style="display: flex; align-items: center; gap: 12px;">
                    ${profileImageHtml}
                    <div class="customer-details">
                        <div style="font-weight: 600; color: #2c3e50; font-size: 0.95rem;">${customerName}</div>
                        <div style="color: #6c757d; font-size: 0.8rem;">${customerEmail}</div>
                    </div>
                </div>
            </td>
            <td>
                <div class="products-list" style="max-width: 200px;">
                    ${productsList}
                </div>
            </td>
            <td>
                <div style="font-weight: 600; color: #2c3e50;">₱${totalAmount.toFixed(
                    2
                )}</div>
            </td>
            <td>
                ${getStatusBadge(reservation.status)}
            </td>
            <td>
                ${getActionButtons(reservation)}
            </td>
        </tr>
    `);

    return row;
}

// Fix the action buttons with unique identifiers and proper event handling
function getActionButtons(reservation) {
    let buttons = "";
    const reservationId = reservation.id;

    switch (reservation.status) {
        case "pending":
            buttons = `
                <button class="btn btn-accept" onclick="updateReservationStatus(${reservationId}, 'accepted')" data-id="${reservationId}">
                    <i class="material-icons-sharp">check_circle</i> Accept
                </button>
                <button class="btn btn-reject" onclick="showRejectionModal(${reservationId})" data-id="${reservationId}">
                    <i class="material-icons-sharp">cancel</i> Reject
                </button>
                `;
            break;
        case "accepted":
            buttons = `
                <button class="btn btn-ready" onclick="markAsReady(${reservationId})" data-id="${reservationId}">
                    <i class="material-icons-sharp">inventory</i> Mark as Ready
                </button>
                <button class="btn btn-reject" onclick="showRejectionModal(${reservationId})" data-id="${reservationId}">
                    <i class="material-icons-sharp">cancel</i> Cancel
                </button>
                `;
            break;
        case "ready_for_pickup":
        case "completed": // Handle legacy status
            buttons = `
                <button class="btn btn-complete" onclick="markAsPickedUp(${reservationId})" data-id="${reservationId}">
                    <i class="material-icons-sharp">done_all</i> Mark as Picked Up
                </button>
                <button class="btn btn-cancel" onclick="showCancellationModal(${reservationId})" data-id="${reservationId}" 
                        style="background: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; margin-left: 5px; font-size: 0.875rem; font-weight: 500; display: inline-flex; align-items: center; transition: all 0.3s ease;"
                        onmouseover="this.style.background='#c82333'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'"
                        onmouseout="this.style.background='#dc3545'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <i class="material-icons-sharp" style="margin-right: 4px; font-size: 16px;">cancel</i> Cancel
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
                <span class="rejected-status">
                    <i class="material-icons-sharp">cancel</i>
                    Rejected
                </span>
                `;
            break;
        default:
            buttons = `<span>No actions available</span>`;
    }

    return buttons;
}

function getStatusBadge(status) {
    switch (status) {
        case "pending":
            return `<span class="status-badge pending">
                <i class="material-icons-sharp">schedule</i>
                Pending
            </span>`;
        case "accepted":
            return `<span class="status-badge accepted">
                <i class="material-icons-sharp">check_circle</i>
                Accepted
            </span>`;
        case "ready_for_pickup":
        case "completed": // Handle legacy status
            return `<span class="status-badge ready">
                <i class="material-icons-sharp">inventory</i>
                Ready for Pickup
            </span>`;
        case "picked_up":
            return `<span class="status-badge completed">
                <i class="material-icons-sharp">done_all</i>
                Picked Up
            </span>`;
        case "rejected":
            return `<span class="status-badge rejected">
                <i class="material-icons-sharp">cancel</i>
                Rejected
            </span>`;
        case "cancelled":
            return `<span class="status-badge cancelled">
                <i class="material-icons-sharp">cancel</i>
                Cancelled
            </span>`;
        default:
            return `<span class="status-badge unknown">
                <i class="material-icons-sharp">help</i>
                ${status}
            </span>`;
    }
}

function getStatusIcon(status) {
    switch (status) {
        case "all":
            return "all_inclusive";
        case "pending":
            return "schedule";
        case "accepted":
            return "check_circle";
        case "ready_for_pickup":
            return "inventory";
        case "picked_up":
            return "done_all";
        case "rejected":
            return "cancel";
        case "cancelled":
            return "cancel";
        default:
            return "help";
    }
}

// Add new function for marking as ready
function markAsReady(reservationId) {
    console.log("markAsReady called with ID:", reservationId);

    // Show confirmation dialog
    if (
        confirm(
            "Mark this reservation as ready for pickup? The customer will be notified."
        )
    ) {
        const data = {
            action: "update_status",
            id: reservationId,
            status: "ready_for_pickup",
        };

        console.log("Sending data:", data);

        $.post("/src/features/reservations/api/reservations.php", data)
            .done(function (response) {
                console.log("Response:", response);
                if (response.success) {
                    showSuccess("Reservation marked as ready for pickup!");
                    loadReservations(); // Reload data
                } else {
                    showError(response.message);
                }
            })
            .fail(function (xhr, status, error) {
                console.error("AJAX Error:", { xhr, status, error });
                showError("Failed to mark as ready. Please try again.");
            });
    }
}

function markAsPickedUp(reservationId) {
    console.log("markAsPickedUp called with ID:", reservationId);

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

        console.log("Sending data:", data);

        $.post("/src/features/reservations/api/reservations.php", data)
            .done(function (response) {
                console.log("Response:", response);
                if (response.success) {
                    showSuccess(
                        "Reservation marked as picked up successfully!"
                    );
                    loadReservations(); // Reload data
                } else {
                    showError(response.message);
                }
            })
            .fail(function (xhr, status, error) {
                console.error("AJAX Error:", { xhr, status, error });
                showError("Failed to mark as picked up. Please try again.");
            });
    }
}

function updateReservationStatus(reservationId, status, reason = null) {
    console.log("updateReservationStatus called:", {
        reservationId,
        status,
        reason,
    });

    const data = {
        action: "update_status",
        id: reservationId,
        status: status,
    };

    if (reason) {
        data.rejection_reason = reason;
    }

    console.log("Sending data:", data);

    $.post("/src/features/reservations/api/reservations.php", data)
        .done(function (response) {
            console.log("Response:", response);
            if (response.success) {
                showSuccess(response.message);
                loadReservations(); // Reload data
            } else {
                showError(response.message);
            }
        })
        .fail(function (xhr, status, error) {
            console.error("AJAX Error:", { xhr, status, error });
            showError("Failed to update reservation status. Please try again.");
        });
}

function showRejectionModal(reservationId) {
    $("#rejectionReservationId").val(reservationId);
    $("#rejectionModal").modal("show");
}

function setupActionHandlers() {
    // Rejection modal handlers
    $("#confirmRejection")
        .off("click")
        .on("click", function () {
            const reservationId = $("#rejectionReservationId").val();
            const reason = $("#rejectionReason").val().trim();

            if (!reason) {
                showError("Please provide a reason for rejection.");
                return;
            }

            updateReservationStatus(reservationId, "rejected", reason);
            $("#rejectionModal").modal("hide");
            $("#rejectionReason").val("");
        });

    // Cancel rejection
    $("#cancelRejection")
        .off("click")
        .on("click", function () {
            $("#rejectionModal").modal("hide");
            $("#rejectionReason").val("");
        });
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
                renderReservations(searchResults);
            } else {
                renderReservations(filteredReservations);
            }
        });
    });
}

function updateTabCounts() {
    const counts = {
        all: allReservations.length,
        pending: allReservations.filter((r) => r.status === "pending").length,
        accepted: allReservations.filter((r) => r.status === "accepted").length,
        ready_for_pickup: allReservations.filter(
            (r) => r.status === "ready_for_pickup" || r.status === "completed"
        ).length,
        picked_up: allReservations.filter((r) => r.status === "picked_up")
            .length,
        rejected: allReservations.filter((r) => r.status === "rejected").length,
        cancelled: allReservations.filter((r) => r.status === "cancelled")
            .length,
    };

    // Update tab labels with counts - Fix the outerHTML error
    Object.keys(counts).forEach((status) => {
        const tab = $(`.nav-link[data-target="${status}"]`);
        if (tab.length > 0) {
            const icon = tab.find("i");
            const iconHtml =
                icon.length > 0
                    ? icon[0].outerHTML
                    : '<i class="material-icons-sharp">help</i>';
            const displayName = getDisplayStatusName(status);
            tab.html(`${iconHtml} ${displayName} (${counts[status]})`);
        }
    });
}

function getDisplayStatusName(status) {
    switch (status) {
        case "all":
            return "All Reservations";
        case "pending":
            return "Pending";
        case "accepted":
            return "Accepted";
        case "ready_for_pickup":
            return "Ready for Pickup";
        case "picked_up":
            return "Picked Up";
        case "rejected":
            return "Rejected";
        case "cancelled":
            return "Cancelled";
        default:
            return status.charAt(0).toUpperCase() + status.slice(1);
    }
}

function showSuccess(message) {
    // You can implement your notification system here
    alert(message); // Temporary
}

function showError(message) {
    // You can implement your notification system here
    alert(message); // Temporary
}

// Add this function to inspect the actual data structure
function inspectReservationData(reservations) {
    console.log("=== RESERVATION DATA INSPECTION ===");
    console.log("Total reservations:", reservations.length);

    if (reservations.length > 0) {
        const sample = reservations[0];
        console.log("Sample reservation keys:", Object.keys(sample));
        console.log("Sample reservation:", sample);

        // Check customer fields
        console.log("Customer fields:", {
            firstname: sample.firstname,
            lastname: sample.lastname,
            email: sample.email,
        });

        // Check product fields
        console.log("Product fields:", {
            products: sample.products,
            products_array: sample.products_array,
            products_count: sample.products_count,
        });

        // Try to parse products if it's a string
        if (sample.products && typeof sample.products === "string") {
            try {
                const parsed = JSON.parse(sample.products);
                console.log("Parsed products:", parsed);
            } catch (e) {
                console.error("Error parsing products:", e);
            }
        }
    }
    console.log("=== END INSPECTION ===");
}

// Update the loadReservations function to use inspection
function loadReservations() {
    console.log("Loading reservations...");

    $.get("/src/features/reservations/api/reservations.php")
        .done(function (response) {
            console.log("Raw API response:", response);

            if (response.success) {
                allReservations = response.data;

                // Inspect the data structure
                inspectReservationData(allReservations);

                renderReservations(allReservations);
                updateTabCounts();
            } else {
                showError("Failed to load reservations: " + response.message);
            }
        })
        .fail(function (xhr, status, error) {
            console.error("AJAX Error loading reservations:", {
                xhr,
                status,
                error,
            });
            showError("Failed to load reservations. Please try again.");
        });
}

// Add new function for cancellation modal
function showCancellationModal(reservationId) {
    const modal = `
        <div class="ui modal" id="cancellationModal">
            <div class="header">
                <i class="cancel icon"></i>
                Cancel Reservation
            </div>
            <div class="content">
                <div class="ui form">
                    <div class="field">
                        <label>Cancellation Reason</label>
                        <select class="ui dropdown" id="cancellationReason">
                            <option value="">Select a reason...</option>
                            <option value="Not picked up within 3 days - NO SHOW">⚠️ Not picked up within 3 days - NO SHOW (Penalizes user)</option>
                            <option value="Item no longer available">Item no longer available</option>
                            <option value="Customer requested cancellation">Customer requested cancellation</option>
                            <option value="Payment issues">Payment issues</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Additional Notes (Optional)</label>
                        <textarea id="cancellationNotes" placeholder="Additional cancellation details..."></textarea>
                    </div>
                    <div class="ui warning message" id="noShowWarning" style="display: none;">
                        <div class="header">⚠️ User Health Penalty</div>
                        <p>Selecting "NO SHOW" will reduce the customer's user health by 20%. This affects their booking priority.</p>
                    </div>
                </div>
            </div>
            <div class="actions">
                <div class="ui cancel button">
                    <i class="remove icon"></i>
                    Cancel
                </div>
                <div class="ui red approve button">
                    <i class="checkmark icon"></i>
                    Confirm Cancellation
                </div>
            </div>
        </div>
    `;

    // Remove existing modal if any
    $("#cancellationModal").remove();

    // Add modal to body
    $("body").append(modal);

    // Initialize dropdown with change handler
    $("#cancellationReason").dropdown({
        onChange: function (value) {
            if (value.includes("NO SHOW")) {
                $("#noShowWarning").show();
            } else {
                $("#noShowWarning").hide();
            }
        },
    });

    // Show modal
    $("#cancellationModal")
        .modal({
            onApprove: function () {
                const reason = $("#cancellationReason").val();
                const notes = $("#cancellationNotes").val();

                if (!reason) {
                    alert("Please select a cancellation reason.");
                    return false;
                }

                // Combine reason and notes
                const fullReason = notes ? `${reason}: ${notes}` : reason;

                // Check if this is a no-show
                const isNoShow = reason.includes("NO SHOW");

                // Call cancellation function
                cancelReservation(reservationId, fullReason, isNoShow);
                return true;
            },
        })
        .modal("show");
}

// Update cancelReservation function to handle no-show penalty
function cancelReservation(reservationId, reason, isNoShow = false) {
    console.log(
        "cancelReservation called with ID:",
        reservationId,
        "Reason:",
        reason,
        "No Show:",
        isNoShow
    );

    const data = {
        action: "update_status",
        id: reservationId,
        status: "cancelled",
        rejection_reason: reason,
        is_no_show: isNoShow ? 1 : 0,
    };

    console.log("Sending cancellation data:", data);

    $.post("/src/features/reservations/api/reservations.php", data)
        .done(function (response) {
            console.log("Cancellation response:", response);
            if (response.success) {
                let message = "Reservation cancelled successfully!";
                if (isNoShow) {
                    message +=
                        "\n⚠️ User health reduced by 20% due to no-show.";
                }
                showSuccess(message);
                loadReservations(); // Reload data
            } else {
                showError(response.message);
            }
        })
        .fail(function (xhr, status, error) {
            console.error("AJAX Error:", { xhr, status, error });
            showError("Failed to cancel reservation. Please try again.");
        });
}

function createGroupedReservationRow(reservationGroup) {
    console.log("Creating grouped row for reservations:", reservationGroup);

    const firstReservation = reservationGroup[0];

    // Calculate total amount and collect all products
    let totalAmount = 0;
    let allProducts = [];

    reservationGroup.forEach((reservation) => {
        const amount = parseFloat(reservation.total_amount || 0);
        totalAmount += amount;

        // Parse products
        let products = [];
        if (
            reservation.products_array &&
            Array.isArray(reservation.products_array)
        ) {
            products = reservation.products_array;
        } else if (reservation.products) {
            try {
                products =
                    typeof reservation.products === "string"
                        ? JSON.parse(reservation.products)
                        : reservation.products;
            } catch (e) {
                products = [];
            }
        }
        allProducts.push(...products);
    });

    // Customer info
    let customerName = firstReservation.user_name || "Unknown Customer";
    let customerEmail = firstReservation.user_email || "No email provided";
    let customerInitial = customerName.charAt(0).toUpperCase();

    // Profile image
    let profileImageHtml = "";
    if (
        firstReservation.profile_image &&
        !firstReservation.profile_image.includes("placeholders")
    ) {
        profileImageHtml = `<div class="customer-avatar" style="
            width: 36px; 
            height: 36px; 
            border-radius: 50%; 
            background-image: url('${firstReservation.profile_image}');
            background-size: cover;
            background-position: center;
            border: 2px solid #e9ecef;
            flex-shrink: 0;
        "></div>`;
    } else {
        profileImageHtml = `<div class="customer-avatar" style="
            width: 36px; 
            height: 36px; 
            border-radius: 50%; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            color: white; 
            font-weight: bold; 
            font-size: 0.9rem;
            flex-shrink: 0;
        ">${customerInitial}</div>`;
    }

    // Create products list
    let productsList =
        '<div class="product-list" style="max-height: 150px; overflow-y: auto;">';
    allProducts.forEach((product, index) => {
        const productName =
            product.name || product.product_name || `Product ${index + 1}`;
        const quantity = parseInt(product.quantity || product.qty || 1);
        const price = parseFloat(product.price || product.unit_price || 0);
        const itemTotal = quantity * price;

        productsList += `
            <div class="product-item" style="padding: 0.4rem 0; border-bottom: 1px solid #f0f0f0;">
                <div class="product-name" style="font-weight: 600; color: #2c3e50;">${productName}</div>
                <div class="product-details" style="color: #6c757d; font-size: 0.85rem;">
                    Qty: ${quantity} × ₱${price.toFixed(
            2
        )} = ₱${itemTotal.toFixed(2)}
                </div>
            </div>
        `;
    });
    productsList += "</div>";

    // Status badge
    const statusClass = getStatusClass(firstReservation.status);
    const statusText = getStatusText(firstReservation.status);

    // Action buttons
    const actionButtons = createGroupActionButtons(reservationGroup);

    return `
        <tr style="background: linear-gradient(to right, #f0f8ff 0%, #ffffff 100%); border-left: 4px solid #2185d0;">
            <td>
                <div style="font-weight: 600; color: #2c3e50;">${
                    firstReservation.formatted_date || "No date"
                }</div>
                <div style="font-size: 0.85rem; color: #6c757d;">${
                    firstReservation.formatted_time || "No time"
                }</div>
            </td>
            <td>
                <div class="customer-info" style="display: flex; align-items: center; gap: 0.75rem;">
                    ${profileImageHtml}
                    <div class="customer-details">
                        <div class="customer-name" style="font-weight: 600; color: #2c3e50;">${customerName}</div>
                        <div class="customer-email" style="color: #6c757d; font-size: 0.85rem;">${customerEmail}</div>
                    </div>
                </div>
            </td>
            <td>
                <div style="margin-bottom: 0.5rem;">
                    <span class="ui mini teal label">
                        <i class="layer group icon"></i> Group Reservation (${
                            reservationGroup.length
                        } items)
                    </span>
                </div>
                ${productsList}
            </td>
            <td>
                <div class="amount" style="font-weight: 700; color: #28a745; font-size: 1.1rem;">
                    ₱${totalAmount.toFixed(2)}
                </div>
            </td>
            <td>
                <span class="status-badge ${statusClass}">${statusText}</span>
            </td>
            <td>
                <div class="action-buttons">
                    ${actionButtons}
                </div>
            </td>
        </tr>
    `;
}

function createGroupActionButtons(reservationGroup) {
    const firstReservation = reservationGroup[0];
    const status = firstReservation.status;
    const reservationIds = reservationGroup.map((r) => r.id).join(",");

    if (status === "pending") {
        return `
            <button class="btn btn-accept" onclick="acceptGroupReservation('${reservationIds}')">
                <i class="check icon"></i> Accept All
            </button>
            <button class="btn btn-reject" onclick="rejectGroupReservation('${reservationIds}')">
                <i class="times icon"></i> Reject All
            </button>
        `;
    } else if (status === "accepted") {
        return `
            <button class="btn btn-ready" onclick="markGroupAsReady('${reservationIds}')">
                <i class="box icon"></i> Ready for Pickup
            </button>
            <button class="btn btn-cancel" onclick="cancelGroupReservation('${reservationIds}')">
                <i class="ban icon"></i> Cancel All
            </button>
        `;
    } else if (status === "ready_for_pickup") {
        return `
            <button class="btn btn-complete" onclick="markGroupAsPickedUp('${reservationIds}')">
                <i class="check circle icon"></i> Mark as Picked Up
            </button>
            <button class="btn btn-cancel" onclick="cancelGroupReservation('${reservationIds}')">
                <i class="ban icon"></i> Cancel All
            </button>
        `;
    } else {
        return `<span class="status-info">No actions available</span>`;
    }
}

function getStatusClass(status) {
    switch (status) {
        case "pending":
            return "pending";
        case "accepted":
            return "accepted";
        case "ready_for_pickup":
        case "completed": // Handle legacy status
            return "ready";
        case "picked_up":
            return "completed";
        case "rejected":
            return "rejected";
        case "cancelled":
            return "cancelled";
        default:
            return "unknown";
    }
}

function getStatusText(status) {
    switch (status) {
        case "pending":
            return "Pending";
        case "accepted":
            return "Accepted";
        case "ready_for_pickup":
        case "completed": // Handle legacy status
            return "Ready for Pickup";
        case "picked_up":
            return "Picked Up";
        case "rejected":
            return "Rejected";
        case "cancelled":
            return "Cancelled";
        default:
            return status.charAt(0).toUpperCase() + status.slice(1);
    }
}

function acceptGroupReservation(reservationIds) {
    if (!confirm("Accept all reservations in this group?")) return;

    const ids = reservationIds.split(",");
    let completed = 0;

    ids.forEach((id) => {
        $.post(
            "/src/features/reservations/api/reservations.php",
            {
                action: "update_status",
                id: id,
                status: "accepted",
            },
            function (response) {
                completed++;
                if (completed === ids.length) {
                    loadReservations();
                    alert("All reservations accepted successfully!");
                }
            }
        );
    });
}

function rejectGroupReservation(reservationIds) {
    const reason = prompt("Reason for rejecting all reservations:");
    if (!reason) return;

    const ids = reservationIds.split(",");
    let completed = 0;

    ids.forEach((id) => {
        $.post(
            "/src/features/reservations/api/reservations.php",
            {
                action: "update_status",
                id: id,
                status: "rejected",
                rejection_reason: reason,
            },
            function (response) {
                completed++;
                if (completed === ids.length) {
                    loadReservations();
                    alert("All reservations rejected.");
                }
            }
        );
    });
}

function markGroupAsReady(reservationIds) {
    if (!confirm("Mark all reservations as ready for pickup?")) return;

    const ids = reservationIds.split(",");
    let completed = 0;

    ids.forEach((id) => {
        $.post(
            "/src/features/reservations/api/reservations.php",
            {
                action: "update_status",
                id: id,
                status: "ready_for_pickup",
            },
            function (response) {
                completed++;
                if (completed === ids.length) {
                    loadReservations();
                    alert("All reservations marked as ready for pickup!");
                }
            }
        );
    });
}

function markGroupAsPickedUp(reservationIds) {
    if (!confirm("Mark all reservations as picked up?")) return;

    const ids = reservationIds.split(",");
    let completed = 0;

    ids.forEach((id) => {
        $.post(
            "/src/features/reservations/api/reservations.php",
            {
                action: "update_status",
                id: id,
                status: "picked_up",
            },
            function (response) {
                completed++;
                if (completed === ids.length) {
                    loadReservations();
                    alert("All reservations marked as picked up!");
                }
            }
        );
    });
}

function cancelGroupReservation(reservationIds) {
    const reason = prompt("Reason for cancelling all reservations:");
    if (!reason) return;

    const ids = reservationIds.split(",");
    let completed = 0;

    ids.forEach((id) => {
        $.post(
            "/src/features/reservations/api/reservations.php",
            {
                action: "update_status",
                id: id,
                status: "cancelled",
                rejection_reason: reason,
            },
            function (response) {
                completed++;
                if (completed === ids.length) {
                    loadReservations();
                    alert("All reservations cancelled.");
                }
            }
        );
    });
}

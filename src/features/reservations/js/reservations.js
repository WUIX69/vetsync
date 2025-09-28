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
    ];
    statuses.forEach((status) => {
        $(`#${status}-reservations`).empty();
    });

    // Update tab counts
    const statusCounts = {
        all: reservations.length,
        pending: 0,
        accepted: 0,
        ready_for_pickup: 0,
        picked_up: 0,
        rejected: 0,
    };

    reservations.forEach(function (reservation) {
        const row = createReservationRow(reservation);

        // Add to all reservations
        $("#all-reservations").append(row);

        // Add to specific status tab
        const status = reservation.status;
        if (status === "ready_for_pickup" || status === "completed") {
            // Handle both ready_for_pickup and legacy completed status
            const readyRow = createReservationRow(reservation); // Create new row instead of cloning
            $("#ready_for_pickup-reservations").append(readyRow);
            statusCounts.ready_for_pickup++;
        } else {
            const statusRow = createReservationRow(reservation); // Create new row instead of cloning
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

    // Handle customer name properly
    const firstName = reservation.firstname || reservation.first_name || "";
    const lastName = reservation.lastname || reservation.last_name || "";

    let customerName = "";
    if (firstName && lastName) {
        customerName = `${firstName} ${lastName}`;
    } else if (firstName) {
        customerName = firstName;
    } else if (lastName) {
        customerName = lastName;
    } else {
        customerName = "Unknown Customer";
    }

    const customerInitial =
        firstName.charAt(0).toUpperCase() ||
        lastName.charAt(0).toUpperCase() ||
        "U";
    const customerEmail = reservation.email || "No email provided";

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

    // Create row with improved customer display
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
                    <div class="customer-avatar" style="
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
                    ">${customerInitial}</div>
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
        default:
            buttons = `
                    <span class="status-info">
                    <i class="material-icons-sharp">help</i>
                    Unknown Status: ${reservation.status}
                    </span>
                `;
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

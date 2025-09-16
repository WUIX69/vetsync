// Safely get elements - no errors if they don't exist
const $userFlyout = $("#userFlyout");

const $usersTable = $("#usersTable");
const $usersDataTable = $usersTable.DataTable({
    layout: {
        topStart: null,
        topEnd: null,
        bottomStart: "info",
        bottomEnd: {
            features: ["pageLength", "paging"],
        },
    },
    pageLength: 10,
    deferRender: true,
    responsive: false, // Disable responsive to maintain column widths
    processing: true,
    serverSide: true,
    searching: true,
    orderCellsTop: true,
    autoWidth: false,
    scrollCollapse: true,
    scrollX: true,
    scrollY: "565px",
    columnDefs: [
        { targets: [0], width: "280px" },
        { targets: [1], width: "200px" },
        { targets: [2], width: "100px" },
        { targets: [3], width: "80px" },
        { targets: [4], width: "150px" },
        { targets: [5], width: "120px" },
        { targets: [6], width: "100px" },
        { targets: [7], width: "100px" },
        { targets: [8], width: "120px" },
    ],
    language: {
        info: "Showing _START_ to _END_ of _TOTAL_ entries",
        lengthMenu: "Entries per page _MENU_",
        processing: '<div class="ui active inline elastic loader"></div>',
        infoEmpty: "No entries to show",
        emptyTable: `
            <div class="ui placeholder segment">
                <div class="ui icon header">
                    <i class="search icon"></i>
                    No Users Found
                </div>
                <div class="ui primary button">Add New User</div>
            </div>
        `,
    },
    columns: [
        {
            data: null,
            width: "280px",
            render: function (data) {
                return `
                    <div class="user-details">
                        <img class="ui avatar image" src="${
                            data.profile ||
                            "https://placehold.co/35x35?text=User"
                        }" alt="${data.name}" />
                        <div class="info d-flex flex-column">
                            <span class="text-capitalize" style="font-weight: 500;">${
                                data.name
                            }</span>
                            <small style="color: #666; font-size: 11px;">ID: ${data.user_uuid.substring(
                                0,
                                8
                            )}...</small>
                        </div>
                    </div>
                `;
            },
        },
        {
            data: "email",
            width: "200px",
            render: function (data) {
                return `<span style="word-break: break-all;">${data}</span>`;
            },
        },
        {
            data: "verification_status",
            width: "100px",
            render: function (data) {
                const statusColors = {
                    pending: "orange",
                    verified: "green",
                    rejected: "red",
                };
                const color = statusColors[data] || "orange";
                const status = data || "pending";
                return `<span class="ui ${color} label" style="font-size: 11px;">${
                    status.charAt(0).toUpperCase() + status.slice(1)
                }</span>`;
            },
        },
        {
            data: "role",
            width: "80px",
            orderable: false,
            render: function (data) {
                return `<span class="ui mini label">${data || "User"}</span>`;
            },
        },
        {
            data: "location",
            width: "150px",
            render: function (data) {
                return data || '<span style="color: #999;">--</span>';
            },
        },
        {
            data: "telephone",
            width: "120px",
            render: function (data) {
                return data || '<span style="color: #999;">--</span>';
            },
        },
        {
            data: "dob",
            width: "100px",
            render: function (data) {
                return data || '<span style="color: #999;">--</span>';
            },
        },
        {
            data: "created_at",
            width: "100px",
        },
        {
            data: null,
            width: "120px",
            orderable: false,
            render: function (data) {
                // Show all verification options with clear labels
                const currentStatus = data.verification_status || "pending";
                const statusBadge =
                    currentStatus === "verified"
                        ? "✅"
                        : currentStatus === "rejected"
                        ? "❌"
                        : "⏳";

                const verificationActions = `
                    <div class="header"><i class="shield icon"></i> Status: ${statusBadge} ${
                    currentStatus.charAt(0).toUpperCase() +
                    currentStatus.slice(1)
                }</div>
                    <div class="item" data-value="verify" ${
                        currentStatus === "verified"
                            ? 'style="background: #f0f8f0;"'
                            : ""
                    }><i class="check green icon"></i> Set as Verified</div>
                    <div class="item" data-value="reject" ${
                        currentStatus === "rejected"
                            ? 'style="background: #fff0f0;"'
                            : ""
                    }><i class="times red icon"></i> Set as Rejected</div>
                    <div class="item" data-value="pending" ${
                        currentStatus === "pending"
                            ? 'style="background: #fff8f0;"'
                            : ""
                    }><i class="clock orange icon"></i> Set as Pending</div>
                `;

                return `
                    <div class="ui compact floating selection dropdown actions-dd" style="min-width: 100px;">
                        <i class="dropdown icon"></i>
                        <div class="text">Actions</div>
                        <div class="menu">
                            <div class="item" data-value="view"><i class="eye icon"></i> View</div>
                            <div class="item" data-value="edit"><i class="edit blue icon"></i> Edit</div>
                            <div class="item" data-value="delete"><i class="trash alternate outline red icon"></i> Delete</div>
                            <div class="divider"></div>
                            ${verificationActions}
                        </div>
                    </div>
                `;
            },
        },
    ],
    ajax: {
        url: apiUrl("users") + "usersDataTable.php",
        method: "GET",
        dataType: "json",
        data: function (d) {
            return d;
        },
        dataSrc: function (response) {
            // console.log("DataTable response:", response);
            return response.data || [];
        },
        error: function (xhr, error, code) {
            // console.error("DataTable error:", error, code, xhr.responseText);
            if (typeof ajaxErrorHandler === "function") {
                ajaxErrorHandler(xhr, error, code);
            }
        },
    },
    drawCallback: function (settings) {
        $(this).find(".ui.dropdown").dropdown();
        $(this)
            .find(".actions-dd")
            .dropdown({
                onChange: function (value) {
                    // console.log("Action selected:", value);

                    // Get the user UUID from the row
                    const row = $(this).closest("tr");
                    const userUuid =
                        row.data("user-uuid") ||
                        row.find("[data-user-uuid]").data("user-uuid");

                    if (!userUuid) {
                        // console.error("User UUID not found");
                        return;
                    }

                    if (value === "view") {
                        singleUserWhereView(userUuid);
                    } else if (value === "edit") {
                        singleUserWhereEdit(userUuid);
                    } else if (value === "delete") {
                        deleteUser(userUuid);
                    } else if (value === "verify") {
                        if (
                            confirm(
                                "Are you sure you want to verify this user?"
                            )
                        ) {
                            updateVerificationStatus(userUuid, "verified");
                        }
                    } else if (value === "reject") {
                        if (
                            confirm(
                                "Are you sure you want to reject this user?"
                            )
                        ) {
                            updateVerificationStatus(userUuid, "rejected");
                        }
                    } else if (value === "pending") {
                        if (
                            confirm(
                                "Are you sure you want to change this user's status back to pending?"
                            )
                        ) {
                            updateVerificationStatus(userUuid, "pending");
                        }
                    }

                    // Reset dropdown
                    $(this).dropdown("clear");
                },
            });
    },
    initComplete: function (settings, json) {
        // Handle table filters if needed
        if (typeof tableListBaseFilters === "function") {
            tableListBaseFilters($usersDataTable);
        }
    },
});

function singleUserWhereEdit(userUuid = null) {
    if (!userUuid) return false;

    $.ajax({
        url: apiUrl("users") + "users.php",
        method: "GET",
        data: { action: "singleWhereEdit", uuid: userUuid },
        success: function (response) {
            // console.log("Edit response:", response);

            if (!response.success) {
                alert(response.message);
                return false;
            }

            // Use direct jQuery selectors - no variable conflicts
            const $modal = $("#userModal");
            if ($modal.length) {
                $modal.find(".header").text("Edit User");
                $modal.find("input[name='password']").prop("disabled", true);

                // Use the form that validateUserForm.js expects
                const $form = $("#userForm");
                if ($form.length && typeof $form.form === "function") {
                    $form.form("set values", response.data);
                }
                $modal.modal("show");
            } else {
                // console.error("User modal not found");
                alert("Edit modal not available");
            }
        },
        error: function (xhr, status, error) {
            // console.error("Edit error:", error);
            alert("Error loading user data");
        },
    });
}

function singleUserWhereView(userUuid = null) {
    if (!userUuid) return false;

    $.ajax({
        url: apiUrl("users") + "users.php",
        method: "GET",
        data: { action: "singleWhereView", uuid: userUuid },
        success: function (response) {
            // console.log("View response:", response);

            if (!response.success) {
                alert(response.message);
                return false;
            }

            if ($userFlyout.length) {
                const userData = response.data;

                // Update flyout content
                $.each(userData, function (key, value) {
                    if (key === "profile") {
                        $userFlyout
                            .find("#profile, .profile-img")
                            .attr(
                                "src",
                                value ||
                                    "https://placehold.co/110x110?text=User"
                            );
                    } else {
                        $userFlyout
                            .find("#" + key)
                            .text(value || "Not specified");
                    }
                });

                $userFlyout.flyout("show");
            } else {
                // console.error("User flyout not found");
                alert("View panel not available");
            }
        },
        error: function (xhr, status, error) {
            // console.error("View error:", error);
            alert("Error loading user data");
        },
    });
}

function deleteUser(userUuid = null) {
    if (!userUuid) return false;
    if (
        !confirm(
            "Are you sure you want to delete this user? This action cannot be undone."
        )
    ) {
        return false;
    }

    $.ajax({
        url: apiUrl("users") + "users.php?user_uuid=" + userUuid,
        method: "DELETE",
        success: function (response) {
            // console.log("Delete response:", response);

            if (response.success) {
                alert("✅ " + response.message);
                $usersDataTable.ajax.reload();
            } else {
                alert("❌ " + response.message);
            }
        },
        error: function (xhr, status, error) {
            // console.error("Delete error:", error);
            alert("❌ Error deleting user");
        },
    });
}

function updateVerificationStatus(userUuid, status) {
    // console.log("Updating verification status:", userUuid, status);

    if (!userUuid || !status) {
        alert("❌ Missing user ID or status");
        return;
    }

    $.ajax({
        url: apiUrl("users") + "users.php",
        method: "POST",
        data: {
            action: "update_verification",
            user_uuid: userUuid,
            status: status,
        },
        dataType: "json",
        timeout: 10000, // 10 second timeout
        success: function (response) {
            // console.log("Verification success response:", response);

            if (response && response.success) {
                alert(
                    "✅ " + (response.message || "Status updated successfully")
                );
                $usersDataTable.ajax.reload(null, false);
                if ($userFlyout.length && $userFlyout.hasClass("visible")) {
                    $userFlyout.flyout("hide");
                }
            } else {
                alert("❌ " + (response.message || "Failed to update status"));
            }
        },
        error: function (xhr, status, error) {
            // console.error("AJAX Error Details:", {
            //     status: status,
            //     error: error,
            //     responseText: xhr.responseText,
            //     readyState: xhr.readyState,
            //     httpStatus: xhr.status,
            // });

            let errorMessage = "Error updating verification status";

            // Try to extract meaningful error message
            if (xhr.responseText) {
                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    if (errorResponse && errorResponse.message) {
                        errorMessage = errorResponse.message;
                    }
                } catch (e) {
                    // If JSON parsing fails, check for common HTML error patterns
                    if (
                        xhr.responseText.includes("Warning") ||
                        xhr.responseText.includes("Error")
                    ) {
                        errorMessage =
                            "Server error occurred. Check console for details.";
                        // console.error("Server response:", xhr.responseText);
                    }
                }
            }

            alert("❌ " + errorMessage);
        },
    });
}

// Remove all existing dropdown event handlers and replace with this unified one
$("body").off("click", ".actions-dd .item");

// Unified event handler for all dropdown actions
$("body").on("click", ".actions-dd .item", function (e) {
    e.preventDefault();
    e.stopPropagation();

    const $item = $(this);
    const $dropdown = $item.closest(".actions-dd");
    const $row = $item.closest("tr");
    const userUuid = $row.data("user-uuid");
    const action = $item.data("value");

    // console.log("Action clicked:", { userUuid, action });

    if (!userUuid) {
        // console.warn("Missing userUuid");
        return;
    }

    // Prevent multiple rapid clicks
    if ($item.hasClass("processing") || $dropdown.hasClass("processing")) {
        // console.log("Already processing, ignoring click");
        return;
    }

    // Close the dropdown immediately to prevent multiple triggers
    $dropdown.dropdown("hide");

    // Handle different actions
    switch (action) {
        case "view":
            singleUserWhereView(userUuid);
            break;

        case "edit":
            singleUserWhereEdit(userUuid);
            break;

        case "delete":
            deleteUser(userUuid);
            break;

        case "verify":
            handleVerificationAction(userUuid, "verified", $dropdown);
            break;

        case "reject":
            handleVerificationAction(userUuid, "rejected", $dropdown);
            break;

        case "pending":
            handleVerificationAction(userUuid, "pending", $dropdown);
            break;

        default:
        // console.warn("Unknown action:", action);
    }
});

// Separate function to handle verification actions
function handleVerificationAction(userUuid, status, $dropdown) {
    // Mark as processing
    $dropdown.addClass("processing");

    // Confirm the action
    if (confirm(`Are you sure you want to set this user as ${status}?`)) {
        updateVerificationStatus(userUuid, status);
    }

    // Remove processing class after a delay
    setTimeout(() => {
        $dropdown.removeClass("processing");
    }, 2000);
}

// Updated dropdown initialization after DataTable is loaded
$usersDataTable.on("draw.dt", function () {
    // console.log("DataTable drawn, initializing dropdowns...");

    // Initialize dropdowns with specific settings to prevent recursion
    $(".actions-dd").dropdown({
        action: "nothing", // Prevent automatic action handling
        allowReselection: true,
        showOnFocus: false,
        allowTab: false,
        onShow: function () {
            // console.log("Dropdown shown");
        },
        onHide: function () {
            // console.log("Dropdown hidden");
        },
    });
});

// Remove any other dropdown event handlers
$("body").off("change", ".user-verification-dropdown");
$("body").off("change", ".actions-dd");

// Debug information
// console.log("UsersDataTable initialized");
// console.log("UserFlyout found:", $userFlyout.length > 0);

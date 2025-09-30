// Safely get elements - no errors if they don't exist
const $userFlyout = $("#userFlyout");

// ✅ STORE FILTER VALUES IN VARIABLES
let currentStatusFilter = "";
let currentRoleFilter = "";

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
    responsive: false,
    processing: true,
    serverSide: true,
    searching: true,
    orderCellsTop: true,
    autoWidth: false,
    scrollCollapse: true,
    scrollX: true,
    scrollY: "565px",
    columnDefs: [
        { targets: [0], width: "280px", orderable: false },
        { targets: [1], width: "200px" },
        { targets: [2], width: "120px" },
        { targets: [3], width: "100px" },
        { targets: [4], width: "120px" }, // Health column
        { targets: [5], width: "150px" },
        { targets: [6], width: "120px" },
        { targets: [7], width: "120px" },
        { targets: [8], width: "140px", orderable: false },
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
            render: function (data, type, row) {
                const fullName =
                    (data.firstname || "") + " " + (data.lastname || "");

                // ✅ USE REAL PROFILE IMAGE OR COLORFUL AVATAR FALLBACK
                let avatarSrc;
                if (
                    data.profile_image &&
                    !data.profile_image.includes("ui-avatars.com")
                ) {
                    // Use real profile image
                    avatarSrc = data.profile_image;
                } else {
                    // Use colorful avatar fallback
                    avatarSrc = `https://ui-avatars.com/api/?name=${encodeURIComponent(
                        fullName
                    )}&size=35&background=random&color=fff&font-size=0.6`;
                }

                return `
                    <div class="user-details" style="cursor: pointer; display: flex; align-items: center;">
                        <img class="ui avatar image" 
                             src="${avatarSrc}" 
                             alt="${fullName}" 
                             style="margin-right: 10px; width: 35px; height: 35px; border-radius: 50%; object-fit: cover;"
                             onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(
                                 fullName
                             )}&size=35&background=random&color=fff&font-size=0.6'" />
                        <div class="info">
                            <div style="font-weight: 500;">${fullName}</div>
                            <small style="color: #666; font-size: 11px;">ID: ${
                                data.user_uuid
                                    ? data.user_uuid.substring(0, 8)
                                    : "N/A"
                            }...</small>
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
            width: "120px",
            render: function (data) {
                // ✅ FIXED: Handle all three status types
                let statusClass, statusText;

                switch (data) {
                    case "verified":
                        statusClass = "green";
                        statusText = "Verified";
                        break;
                    case "rejected":
                        statusClass = "red";
                        statusText = "Rejected";
                        break;
                    case "pending":
                    default:
                        statusClass = "orange";
                        statusText = "Pending";
                        break;
                }

                return `<span class="ui ${statusClass} label">${statusText}</span>`;
            },
        },
        {
            data: null,
            width: "100px",
            render: function (data, type, row) {
                // ✅ FIX: Use your actual admin email
                const role = data.email === "admin@mail.com" ? "Admin" : "User";
                const roleClass = role === "Admin" ? "blue" : "teal";
                return `<span class="ui ${roleClass} label">${role}</span>`;
            },
        },
        {
            data: "user_health",
            width: "120px",
            render: function (data) {
                const health = parseFloat(data || 100);
                let healthClass = "green";
                let healthIcon = "favorite";

                if (health < 50) {
                    healthClass = "red";
                    healthIcon = "heart broken";
                } else if (health < 80) {
                    healthClass = "orange";
                    healthIcon = "heart";
                }

                return `
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <i class="ui ${healthIcon} ${healthClass} icon"></i>
                        <span class="ui ${healthClass} label">${health.toFixed(
                    0
                )}%</span>
                    </div>
                `;
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
            data: "created_at",
            width: "120px",
            render: function (data) {
                if (!data) return "--";
                try {
                    const date = new Date(data);
                    if (isNaN(date.getTime())) {
                        return data;
                    }
                    return date.toLocaleDateString("en-US", {
                        year: "numeric",
                        month: "short",
                        day: "numeric",
                    });
                } catch (e) {
                    return data;
                }
            },
        },
        {
            data: null,
            width: "140px",
            orderable: false,
            render: function (data, type, row) {
                // ✅ ENSURE WE HAVE A VALID UUID
                const userUuid = data.user_uuid || data.uuid || "";
                const currentStatus = data.verification_status || "pending";

                // ✅ BUILD VERIFICATION ACTIONS based on current status
                let verificationActions = "";

                // Show different verification options based on current status
                if (currentStatus !== "verified") {
                    verificationActions += `
                        <div class="item" data-action="verify" data-uuid="${userUuid}">
                            <i class="check green icon"></i> Verify User
                        </div>
                    `;
                }

                if (currentStatus !== "rejected") {
                    verificationActions += `
                        <div class="item" data-action="reject" data-uuid="${userUuid}">
                            <i class="times red icon"></i> Reject User
                        </div>
                    `;
                }

                if (currentStatus !== "pending") {
                    verificationActions += `
                        <div class="item" data-action="pending" data-uuid="${userUuid}">
                            <i class="clock orange icon"></i> Set Pending
                        </div>
                    `;
                }

                return `
                    <div class="ui compact dropdown button actions-dropdown" data-user-uuid="${userUuid}">
                        <div class="text">Actions</div>
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            <div class="item" data-action="view" data-uuid="${userUuid}">
                                <i class="eye icon"></i> View Details
                            </div>
                            <div class="item" data-action="edit" data-uuid="${userUuid}">
                                <i class="edit blue icon"></i> Edit User
                            </div>
                            <div class="divider"></div>
                            <div class="header">
                                <i class="shield icon"></i> Verification (Current: ${currentStatus.toUpperCase()})
                            </div>
                            ${verificationActions}
                            <div class="divider"></div>
                            <div class="item" data-action="delete" data-uuid="${userUuid}">
                                <i class="trash red icon"></i> Delete User
                            </div>
                        </div>
                    </div>
                `;
            },
        },
    ],
    ajax: {
        url: "../../features/users/api/usersDataTable.php",
        type: "GET",
        data: function (d) {
            console.log("🔍 Using stored filter values:", {
                statusFilter: currentStatusFilter,
                roleFilter: currentRoleFilter,
            });

            // Only send filters if they have actual values
            if (currentStatusFilter && currentStatusFilter !== "") {
                d.status_filter = currentStatusFilter;
                console.log("📊 Adding status filter:", currentStatusFilter);
            }
            if (currentRoleFilter && currentRoleFilter !== "") {
                d.role_filter = currentRoleFilter;
                console.log("🔑 Adding role filter:", currentRoleFilter);
            }

            console.log("📤 Final data being sent:", d);
            return d;
        },
        error: function (xhr, error, code) {
            console.error("DataTable AJAX Error:", { xhr, error, code });
        },
    },
    drawCallback: function () {
        // ✅ PROPERLY INITIALIZE ACTION DROPDOWNS
        console.log("🔧 Initializing action dropdowns...");

        // Wait a bit for DOM to be ready, then initialize dropdowns
        setTimeout(function () {
            // First destroy any existing dropdowns to avoid conflicts
            $(".actions-dropdown").dropdown("destroy");

            // Initialize all dropdowns in the table with simpler settings
            $(".actions-dropdown").dropdown({
                action: "hide",
                on: "click",
                allowReselection: true,
                showOnFocus: false,
                selectOnKeydown: false,
                transition: "slide down",
                duration: 150,
            });

            console.log(
                "✅ Initialized",
                $(".actions-dropdown").length,
                "action dropdowns"
            );
        }, 100);

        // ✅ SIMPLIFIED: Handle action clicks with direct event binding
        setTimeout(function () {
            // Remove any existing handlers first
            $(".actions-dropdown .menu .item").off("click.userActions");

            // Bind click events directly to menu items
            $(".actions-dropdown .menu .item").on(
                "click.userActions",
                function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const $item = $(this);
                    const action = $item.data("action");
                    const uuid = $item.data("uuid");

                    console.log("🔥 Direct click detected:", {
                        action: action,
                        uuid: uuid,
                        element: $item[0],
                        hasAction: $item.attr("data-action"),
                        hasUuid: $item.attr("data-uuid"),
                    });

                    if (!action) {
                        console.warn(
                            "⚠️ No action found on element:",
                            $item[0]
                        );
                        return;
                    }

                    if (!uuid) {
                        console.error("❌ No UUID found for action:", action);
                        return;
                    }

                    // Close the dropdown immediately
                    const $dropdown = $item.closest(".actions-dropdown");
                    $dropdown.dropdown("hide");

                    // Execute the appropriate action with a small delay
                    setTimeout(function () {
                        console.log(
                            "🚀 Executing action:",
                            action,
                            "for UUID:",
                            uuid
                        );

                        switch (action) {
                            case "view":
                                console.log("📖 Calling viewUser");
                                viewUser(uuid);
                                break;
                            case "edit":
                                console.log("✏️ Calling editUser");
                                editUser(uuid);
                                break;
                            case "verify":
                                console.log("✅ Calling verifyUser");
                                verifyUser(uuid);
                                break;
                            case "reject":
                                console.log("❌ Calling rejectUser");
                                rejectUser(uuid);
                                break;
                            case "pending":
                                console.log("⏳ Calling setPendingUser");
                                setPendingUser(uuid);
                                break;
                            case "delete":
                                console.log("🗑️ Calling deleteUser");
                                deleteUser(uuid);
                                break;
                            default:
                                console.warn("Unknown action:", action);
                                alert("Unknown action: " + action);
                                return;
                        }
                    }, 50);
                }
            );

            console.log(
                "✅ Bound click events to",
                $(".actions-dropdown .menu .item").length,
                "menu items"
            );
        }, 200);

        // ✅ PREVENT ROW CLICK when clicking dropdown
        $("#usersTable tbody tr")
            .off("click.rowClick")
            .on("click.rowClick", function (e) {
                // Don't trigger if clicking on dropdown or its children
                if ($(e.target).closest(".actions-dropdown").length > 0) {
                    console.log("🚫 Row click prevented - clicked on dropdown");
                    return;
                }

                const data = $usersDataTable.row(this).data();
                if (data && (data.user_uuid || data.uuid)) {
                    const uuid = data.user_uuid || data.uuid;
                    console.log("👆 Row clicked - viewing user:", uuid);
                    viewUser(uuid);
                }
            });

        // Initialize filter dropdowns
        initializeFilterDropdowns();
    },
});

// ✅ INITIALIZE FILTER DROPDOWNS ONLY ONCE - OUTSIDE OF DATATABLE
function initializeFilterDropdowns() {
    console.log("🔧 Initializing filter dropdowns...");

    // Destroy existing dropdowns first to avoid conflicts
    $("#status-filter").dropdown("destroy");
    $("#role-filter").dropdown("destroy");

    // Initialize status filter dropdown
    $("#status-filter").dropdown({
        onChange: function (value, text, $choice) {
            console.log("📊 Status filter changed to:", value, text);
            currentStatusFilter = value || "";
            console.log(
                "📊 Updated currentStatusFilter to:",
                currentStatusFilter
            );
            $usersDataTable.ajax.reload();
        },
    });

    // Initialize role filter dropdown
    $("#role-filter").dropdown({
        onChange: function (value, text, $choice) {
            console.log("🔑 Role filter changed to:", value, text);
            currentRoleFilter = value || "";
            console.log("🔑 Updated currentRoleFilter to:", currentRoleFilter);
            $usersDataTable.ajax.reload();
        },
    });

    console.log("✅ Filter dropdowns initialized successfully");
}

// ✅ INITIALIZE FILTERS AFTER DOM IS READY
$(document).ready(function () {
    // Small delay to ensure DataTable is fully initialized
    setTimeout(function () {
        initializeFilterDropdowns();
    }, 500);
});

// Add User button handler
$("#add-user-btn").on("click", function () {
    console.log("Add User button clicked");
    openAddUserModal();
});

// ✅ FIXED USER ACTION FUNCTIONS
function viewUser(uuid) {
    console.log("👁️ Viewing user:", uuid);

    if (!uuid) {
        alert("❌ Error: User ID not found");
        return;
    }

    // ✅ ACTUAL API CALL to get user details
    $.ajax({
        url: "../../features/users/api/users.php",
        method: "GET",
        data: {
            action: "singleWhereView",
            uuid: uuid,
        },
        dataType: "json",
        success: function (response) {
            console.log("✅ View API Response:", response);

            if (response && response.success === true) {
                const userData = response.data;
                console.log("👤 User data received:", userData);

                // ✅ OPEN USER FLYOUT WITH REAL DATA
                if ($("#userFlyout").length > 0) {
                    // Populate flyout with user data
                    $("#userFlyout #name").text(
                        `${userData.firstname || ""} ${
                            userData.lastname || ""
                        }`.trim() || "N/A"
                    );
                    $("#userFlyout #email").text(userData.email || "N/A");
                    $("#userFlyout #telephone").text(
                        userData.telephone || "N/A"
                    );
                    $("#userFlyout #location").text(userData.location || "N/A");
                    $("#userFlyout #role").text(
                        userData.email === "admin@mail.com" ? "Admin" : "User"
                    );

                    // Format and set created date
                    if (userData.created_at) {
                        try {
                            const date = new Date(userData.created_at);
                            $("#userFlyout #created_at").text(
                                date.toLocaleDateString("en-US", {
                                    year: "numeric",
                                    month: "long",
                                    day: "numeric",
                                })
                            );
                        } catch (e) {
                            $("#userFlyout #created_at").text(
                                userData.created_at
                            );
                        }
                    } else {
                        $("#userFlyout #created_at").text("N/A");
                    }

                    // Set verification status
                    const verificationStatus =
                        userData.verification_status || "pending";
                    $("#userFlyout #verified-text").text(
                        verificationStatus.charAt(0).toUpperCase() +
                            verificationStatus.slice(1)
                    );

                    // Set profile image
                    if (
                        userData.profile_image &&
                        !userData.profile_image.includes("ui-avatars.com")
                    ) {
                        $("#userFlyout #profile").attr(
                            "src",
                            userData.profile_image
                        );
                    } else {
                        const fullName = `${userData.firstname || ""} ${
                            userData.lastname || ""
                        }`.trim();
                        const avatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(
                            fullName
                        )}&size=200&background=random&color=fff&font-size=0.6`;
                        $("#userFlyout #profile").attr("src", avatarUrl);
                    }

                    // Show the flyout
                    $("#userFlyout").flyout("show");
                    console.log("✅ User flyout opened successfully");
                } else {
                    console.error("❌ User flyout not found in DOM");
                    // Fallback to alert if flyout doesn't exist
                    let userInfo = `👤 User Details:\n\n`;
                    userInfo += `Name: ${userData.firstname} ${userData.lastname}\n`;
                    userInfo += `Email: ${userData.email}\n`;
                    userInfo += `Phone: ${userData.telephone || "N/A"}\n`;
                    userInfo += `Location: ${userData.location || "N/A"}\n`;
                    userInfo += `Status: ${userData.verification_status}\n`;
                    userInfo += `Created: ${userData.created_at}\n`;
                    alert(userInfo);
                }
            } else {
                console.error("❌ View failed:", response);
                alert(
                    "❌ Error: " +
                        (response && response.message
                            ? response.message
                            : "Failed to load user details. Check console for details.")
                );
            }
        },
        error: function (xhr, status, error) {
            console.error("❌ AJAX Error:", { xhr, status, error });
            console.error("❌ Response Text:", xhr.responseText);
            alert("❌ Error loading user details: " + error);
        },
    });
}

function editUser(uuid) {
    console.log("✏️ Editing user:", uuid);

    if (!uuid) {
        alert("❌ Error: User ID not found");
        return;
    }

    // ✅ ACTUAL API CALL to get user data for editing
    $.ajax({
        url: "../../features/users/api/users.php",
        method: "GET",
        data: {
            action: "singleWhereEdit",
            uuid: uuid,
        },
        dataType: "json",
        success: function (response) {
            console.log("✅ Edit API Response:", response);

            if (response && response.success === true) {
                const userData = response.data;
                console.log("✏️ User data for editing:", userData);

                // ✅ OPEN EDIT MODAL WITH REAL DATA
                if ($("#userModal").length > 0) {
                    // Set modal to edit mode
                    $("#userModal .header").text("Edit User");

                    // Disable password field for editing
                    $("#userModal input[name='password']").prop(
                        "disabled",
                        true
                    );
                    $("#userModal input[name='password']")
                        .closest(".field")
                        .hide();

                    // Populate form fields
                    $("#userModal input[name='uuid']").val(userData.uuid || "");
                    $("#userModal input[name='firstname']").val(
                        userData.firstname || ""
                    );
                    $("#userModal input[name='lastname']").val(
                        userData.lastname || ""
                    );
                    $("#userModal input[name='email']").val(
                        userData.email || ""
                    );
                    $("#userModal input[name='telephone']").val(
                        userData.telephone || ""
                    );

                    // Set role dropdown
                    const role =
                        userData.email === "admin@mail.com" ? "admin" : "user";
                    $("#userModal input[name='role']").val(role);
                    $("#userModal .dropdown").dropdown("set selected", role);

                    // Initialize dropdowns
                    $("#userModal .dropdown").dropdown();

                    // Show the modal
                    $("#userModal").modal("show");
                    console.log("✅ Edit modal opened successfully");
                } else {
                    console.error("❌ User modal not found in DOM");
                    // Fallback to alert if modal doesn't exist
                    alert(
                        `✏️ Edit User: ${userData.firstname} ${userData.lastname}\n\nModal not available. Please check if user-modal.php is included.`
                    );
                }
            } else {
                console.error("❌ Edit failed:", response);
                alert(
                    "❌ Error: " +
                        (response && response.message
                            ? response.message
                            : "Failed to load user data for editing.")
                );
            }
        },
        error: function (xhr, status, error) {
            console.error("❌ AJAX Error:", { xhr, status, error });
            console.error("❌ Response Text:", xhr.responseText);
            alert("❌ Error loading user data: " + error);
        },
    });
}

function deleteUser(uuid) {
    console.log("🗑️ Deleting user:", uuid);

    if (!uuid) {
        alert("❌ Error: User ID not found");
        return;
    }

    // Confirm deletion
    if (
        !confirm(
            `⚠️ Are you sure you want to delete this user?\n\nUUID: ${uuid}\n\nThis action cannot be undone.`
        )
    ) {
        return;
    }

    // ✅ ACTUAL API CALL for deletion
    $.ajax({
        url: `../../features/users/api/users.php?user_uuid=${uuid}`,
        method: "DELETE",
        dataType: "json",
        success: function (response) {
            console.log("Delete response:", response);
            if (response && response.success) {
                alert("✅ User deleted successfully!");
                $usersDataTable.ajax.reload();
            } else {
                alert(
                    "❌ Error: " + (response.message || "Failed to delete user")
                );
            }
        },
        error: function (xhr, status, error) {
            console.error("Delete error:", error);
            alert("❌ Error deleting user: " + error);
        },
    });
}

function verifyUser(uuid) {
    console.log("✅ Verifying user:", uuid);

    if (!uuid) {
        alert("❌ Error: User ID not found");
        return;
    }

    if (!confirm("✅ Are you sure you want to verify this user?")) {
        return;
    }

    // ✅ ACTUAL API CALL for verification
    $.ajax({
        url: "../../features/users/api/users.php",
        method: "POST",
        data: {
            action: "update_verification",
            user_uuid: uuid,
            status: "verified",
        },
        dataType: "json",
        success: function (response) {
            console.log("✅ Verify API Response:", response);
            console.log("✅ Response Type:", typeof response);
            console.log(
                "✅ Response Success:",
                response ? response.success : "undefined"
            );

            if (response && response.success === true) {
                alert("✅ User verified successfully!");
                $usersDataTable.ajax.reload();
            } else {
                console.error("❌ Verification failed:", response);
                alert(
                    "❌ Error: " +
                        (response && response.message
                            ? response.message
                            : "Failed to verify user. Check console for details.")
                );
            }
        },
        error: function (xhr, status, error) {
            console.error("❌ AJAX Error:", { xhr, status, error });
            console.error("❌ Response Text:", xhr.responseText);
            alert(
                "❌ Error verifying user: " +
                    error +
                    "\nCheck console for more details."
            );
        },
    });
}

function rejectUser(uuid) {
    console.log("❌ Rejecting user:", uuid);

    if (!uuid) {
        alert("❌ Error: User ID not found");
        return;
    }

    if (!confirm("❌ Are you sure you want to reject this user?")) {
        return;
    }

    // ✅ ACTUAL API CALL for rejection
    $.ajax({
        url: "../../features/users/api/users.php",
        method: "POST",
        data: {
            action: "update_verification",
            user_uuid: uuid,
            status: "rejected",
        },
        dataType: "json",
        success: function (response) {
            console.log("❌ Reject API Response:", response);
            console.log("❌ Response Type:", typeof response);
            console.log(
                "❌ Response Success:",
                response ? response.success : "undefined"
            );

            if (response && response.success === true) {
                alert("❌ User rejected successfully!");
                $usersDataTable.ajax.reload();
            } else {
                console.error("❌ Rejection failed:", response);
                alert(
                    "❌ Error: " +
                        (response && response.message
                            ? response.message
                            : "Failed to reject user. Check console for details.")
                );
            }
        },
        error: function (xhr, status, error) {
            console.error("❌ AJAX Error:", { xhr, status, error });
            console.error("❌ Response Text:", xhr.responseText);
            alert(
                "❌ Error rejecting user: " +
                    error +
                    "\nCheck console for more details."
            );
        },
    });
}

function setPendingUser(uuid) {
    console.log("⏳ Setting user to pending:", uuid);

    if (!uuid) {
        alert("❌ Error: User ID not found");
        return;
    }

    if (!confirm("⏳ Are you sure you want to set this user as pending?")) {
        return;
    }

    // ✅ ACTUAL API CALL for pending status
    $.ajax({
        url: "../../features/users/api/users.php",
        method: "POST",
        data: {
            action: "update_verification",
            user_uuid: uuid,
            status: "pending",
        },
        dataType: "json",
        success: function (response) {
            console.log("⏳ Pending API Response:", response);
            console.log("⏳ Response Type:", typeof response);
            console.log(
                "⏳ Response Success:",
                response ? response.success : "undefined"
            );

            if (response && response.success === true) {
                alert("⏳ User set to pending successfully!");
                $usersDataTable.ajax.reload();
            } else {
                console.error("❌ Pending failed:", response);
                alert(
                    "❌ Error: " +
                        (response && response.message
                            ? response.message
                            : "Failed to set user as pending. Check console for details.")
                );
            }
        },
        error: function (xhr, status, error) {
            console.error("❌ AJAX Error:", { xhr, status, error });
            console.error("❌ Response Text:", xhr.responseText);
            alert(
                "❌ Error setting user as pending: " +
                    error +
                    "\nCheck console for more details."
            );
        },
    });
}

// ✅ ADD USER MODAL FUNCTION
function openAddUserModal() {
    console.log("➕ Opening add user modal");

    if ($("#userModal").length > 0) {
        // Set modal to add mode
        $("#userModal .header").text("Add New User");

        // Clear form
        $("#userForm")[0].reset();
        $("#userModal input[name='uuid']").val(""); // Clear UUID for new user

        // Enable password field for new users
        $("#userModal input[name='password']").prop("disabled", false);
        $("#userModal input[name='password']").closest(".field").show();

        // Initialize/reset dropdowns
        $("#userModal .dropdown").dropdown("clear");
        $("#userModal .dropdown").dropdown();

        // Show the modal
        $("#userModal").modal("show");
        console.log("✅ Add user modal opened successfully");
    } else {
        console.error("❌ User modal not found in DOM");
        alert(
            "➕ Add User Modal\n\nModal not available. Please check if user-modal.php is included."
        );
    }
}

console.log("UsersDataTable initialized successfully");

// ✅ CONNECT SEARCH INPUT TO DATATABLES
$(document).ready(function () {
    // Find the search input
    const $searchInput = $(".table-filters .ui.search input.prompt");

    if ($searchInput.length > 0) {
        console.log("🔍 Connecting search input to DataTables");

        // Add event listener for real-time search
        $searchInput.on("input keyup", function () {
            const searchValue = $(this).val();
            console.log("🔍 Search value:", searchValue);

            // Trigger DataTables search
            $usersDataTable.search(searchValue).draw();
        });

        console.log("✅ Search input connected successfully");
    } else {
        console.warn("⚠️ Search input not found");
    }
});

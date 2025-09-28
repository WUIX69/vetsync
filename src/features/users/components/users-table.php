<style>
    .users-table-container {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: var(--box-shadow);
        margin-top: 1rem;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .table-filters {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .user-details {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .user-details .info {
        display: flex;
        flex-direction: column;
    }

    /* Fix table alignment */
    .ui.celled.table th {
        text-align: left !important;
        vertical-align: middle !important;
        padding: 0.92857143em 0.78571429em !important;
    }

    .ui.celled.table td {
        text-align: left !important;
        vertical-align: middle !important;
        padding: 0.78571429em 0.78571429em !important;
    }

    /* Ensure proper column widths */
    .ui.celled.table {
        table-layout: fixed;
        width: 100%;
    }

    /* Fix actions dropdown positioning */
    .ui.dropdown .menu {
        z-index: 1000 !important;
        min-width: 140px !important;
    }

    .ui.dropdown .menu .item {
        padding: 0.5rem 1rem !important;
        white-space: nowrap !important;
    }

    .user-details {
        cursor: pointer !important;
    }

    .user-details:hover {
        background-color: #f8f9fa !important;
    }

    /* ✅ IMPROVED: Actions dropdown positioning and behavior */
    .actions-dropdown {
        position: relative !important;
    }

    .actions-dropdown .menu {
        position: absolute !important;
        top: 100% !important;
        right: 0 !important;
        left: auto !important;
        z-index: 1050 !important;
        min-width: 200px !important;
        max-width: 250px !important;
        white-space: nowrap !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        border: 1px solid #d4d4d5 !important;
        margin-top: 2px !important;
        background: white !important;
        border-radius: 4px !important;
    }

    .actions-dropdown .menu .item {
        padding: 12px 16px !important;
        white-space: nowrap !important;
        font-size: 0.9em !important;
        cursor: pointer !important;
        transition: background-color 0.2s ease !important;
    }

    .actions-dropdown .menu .item:hover {
        background-color: #f8f9fa !important;
    }

    .actions-dropdown .menu .header {
        padding: 10px 16px !important;
        font-weight: bold !important;
        font-size: 0.85em !important;
        color: #666 !important;
        background-color: #fafafa !important;
    }

    .actions-dropdown .menu .divider {
        margin: 8px 0 !important;
        border-top: 1px solid #e0e0e0 !important;
    }

    /* ✅ PREVENT DROPDOWN FROM BEING CUT OFF */
    .table-responsive {
        overflow: visible !important;
    }

    #usersTable_wrapper {
        overflow: visible !important;
    }

    .dataTables_scrollBody {
        overflow: visible !important;
    }

    /* ✅ ENSURE TABLE CELLS DON'T INTERFERE */
    #usersTable td {
        overflow: visible !important;
    }

    /* Additional spacing for last column */
    table th:last-child,
    table td:last-child {
        padding-right: 25px !important;
    }

    /* ✅ PREVENT TABLE HOVER FROM INTERFERING WITH DROPDOWN */
    .actions-dropdown.active,
    .actions-dropdown.visible {
        z-index: 1060 !important;
    }

    /* ✅ MAKE DROPDOWN BUTTON MORE STABLE */
    .actions-dropdown.ui.button {
        padding: 8px 12px !important;
        min-width: 80px !important;
    }
</style>

<div class="users-table-container">
    <div class="table-header">
        <h3 class="ui header">Users List</h3>

        <div class="table-filters">
            <div class="ui search">
                <div class="ui icon input">
                    <input class="prompt" type="text" placeholder="Search users...">
                    <i class="search icon"></i>
                </div>
            </div>

            <div class="ui selection dropdown" id="status-filter">
                <input type="hidden" name="status">
                <i class="dropdown icon"></i>
                <div class="default text">All Status</div>
                <div class="menu">
                    <div class="item" data-value="">All Status</div>
                    <div class="item" data-value="verified">Verified</div>
                    <div class="item" data-value="pending">Pending</div>
                    <div class="item" data-value="rejected">Rejected</div>
                </div>
            </div>

            <div class="ui selection dropdown" id="role-filter">
                <input type="hidden" name="role">
                <i class="dropdown icon"></i>
                <div class="default text">All Roles</div>
                <div class="menu">
                    <div class="item" data-value="">All Roles</div>
                    <div class="item" data-value="user">User</div>
                    <div class="item" data-value="admin">Admin</div>
                </div>
            </div>

            <button class="ui primary button" id="add-user-btn">
                <i class="plus icon"></i> Add User
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="ui celled table" id="usersTable">
            <thead>
                <tr>
                    <th style="width: 280px;">User</th>
                    <th style="width: 200px;">Email</th>
                    <th style="width: 120px;">Status</th>
                    <th style="width: 100px;">Role</th>
                    <th style="width: 150px;">Location</th>
                    <th style="width: 120px;">Telephone</th>
                    <th style="width: 120px;">Created At</th>
                    <th style="width: 140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Users Table DataTable Dynamic Data -->
            </tbody>
        </table>
    </div>
</div>
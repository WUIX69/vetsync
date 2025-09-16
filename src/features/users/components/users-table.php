<style>
    main section.users-table table .user-details {
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 260px;
    }

    main section.users-table table .user-details .image {
        height: 35px !important;
        width: 35px !important;
        border: var(--img-border) !important;
        flex-shrink: 0;
    }

    /* Fix table layout */
    #usersTable {
        table-layout: fixed !important;
        width: 100% !important;
    }

    #usersTable th,
    #usersTable td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding: 8px 6px !important;
        vertical-align: middle;
    }

    /* Actions column specific styling */
    #usersTable th:last-child,
    #usersTable td:last-child {
        text-align: center;
    }

    /* Status column styling */
    #usersTable td:nth-child(3) {
        text-align: center;
    }
</style>
<!-- User List -->
<section class="users-table">
    <h2 class="title">Users List</h2>
    <div class="container table-list box">
        <div class="table-filters">
            <div class="base-filters">
                <div class="ui fluid mini category search user-search">
                    <div class="ui icon input">
                        <input class="prompt" type="text" placeholder="Search users..." />
                        <i class="search icon"></i>
                    </div>
                    <div class="results"></div>
                </div>
                <div class="quick-filters">
                    <div class="ui mini compact selection floating labeled icon dropdown">
                        <input type="hidden" name="status" />
                        <i class="signal icon"></i>
                        <div class="default text">Status</div>
                        <div class="menu">
                            <div class="header">
                                <i class="tags icon"></i>
                                Filter by Status
                            </div>
                            <div class="item" data-value="active">
                                Active
                            </div>
                            <div class="item" data-value="inactive">
                                Inactive
                            </div>
                            <div class="item" data-value="pending">
                                Pending
                            </div>
                        </div>
                    </div>
                    <div class="ui mini compact selection floating labeled icon dropdown">
                        <input type="hidden" name="role" />
                        <i class="user icon"></i>
                        <div class="default text">Role</div>
                        <div class="menu">
                            <div class="header">
                                <i class="tags icon"></i>
                                Filter by Role
                            </div>
                            <div class="item" data-value="admin">
                                Admin
                            </div>
                            <div class="item" data-value="user">
                                User
                            </div>
                        </div>
                    </div>
                    <button class="ui mini secondary button filter-reset-btn">
                        <i class="times icon"></i>
                        Reset
                    </button>
                </div>
            </div>
            <button class="ui mini primary button add-user-btn" id="addUserBtn" data-open-modal="#userModal">
                <i class="plus icon"></i>
                Add User
            </button>
        </div>
        <div class="table-container">
            <table class="ui fixed single line small very basic nowrap selectable quick-view display table"
                id="usersTable">
                <thead>
                    <tr>
                        <th width="280">User</th>
                        <th width="200">Email</th>
                        <th width="100">Status</th>
                        <th width="80">Role</th>
                        <th width="150">Location</th>
                        <th width="120">Telephone</th>
                        <th width="100">Birth Date</th>
                        <th width="100">Created At</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Users Table DataTable Dynamic Data -->
                </tbody>
            </table>
        </div>
    </div>
</section>
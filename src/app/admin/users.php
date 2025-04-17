<?php include_once '../../../src/utils/php/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta') ?>
    <title>Admin Dashboard - users</title>
    <?= shared('elements/styles') ?>
    <?= featured('admin/dashboard/styles') ?>
    <style>
        .user-details {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .user-details .image {
            height: 35px !important;
            width: 35px !important;
            border: var(--img-border) !important;
        }

        .ui.feed * {
            background-color: var(--color-white) !important;
            font-size: 0.8rem !important;
        }

        .ui.feed .content *:not(a.user) {
            color: var(--color-dark) !important;
        }
    </style>
</head>

<body>

    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window') ?> <!-- Window Spinner -->
        <?= shared('components/reminder-modal') ?> <!-- Reminder Modal -->
        <?= shared('components/flyout') ?> <!-- Flyout -->
    </div>
    <!-- add-edit-usermodal -->
    <?= featured('admin/users/components/add-edit-usermodal') ?> <!-- Sidebar -->

    <div class="container-body pusher">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-auto">
                <?= featured('admin/shared/layouts/sidebar') ?> <!-- Sidebar -->
            </div>

            <!-- Main Content -->
            <div class="col">
                <main class="container-main">
                    <h1>Users Management</h1>
                    <!-- user-stats -->
                    <?= featured('admin/users/components/user-stats') ?>

                    <!-- new-user-->
                    <?= featured('admin/users/components/new-users') ?>

                    <!-- user-list -->
                    <?= featured('admin/users/components/user-list') ?>

                </main>
            </div>

            <!-- Right Section -->
            <div class="col-lg-3">
                <?= featured('admin/users/components/right-section') ?>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts') ?>
    <?= featured('admin/dashboard/scripts') ?>
    <script type="text/javascript">
        // Cache DOM elements
        const $userModal = $("#userModal");
        const $userForm = $userModal.find("#userForm");
        const $usersTable = $('#usersTable');
        const $usersDataTable = $usersTable.DataTable({
            dom: 't<"bottom-controls"<"info"i><"right-controls"l<"pagination"p>>>',
            pageLength: 10,
            deferRender: true,
            stateSave: true,
            responsive: true,
            processing: true,
            serverSide: true,
            searching: true,
            orderCellsTop: true,
            autoWidth: false,
            scrollCollapse: true,
            scrollX: true,
            scrollY: '500px',
            language: {
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                lengthMenu: "Entries per page _MENU_",
                processing: '<div class="ui active inline elastic loader"></div>',
                // infoEmpty: "No entries to show",
                // emptyTable: `
                //     <div class="ui placeholder segment">
                //         <div class="ui icon header">
                //             <i class="search icon"></i>
                //             No Users Found
                //         </div>
                //         <div class="ui primary button">Add New User</div>
                //     </div>
                // `,
            },
            columnDefs: [
                { orderable: false, targets: [6] },
                // { width: '13%', targets: 0 }, // User column
                // { width: '15%', targets: 1 }, // Email column
                // { width: '6%', targets: [2, 3] }, // Role and Gender columns
                // { width: '9%', targets: [4, 5] }, // Phone and Birth Date columns
                // { width: '6%', targets: 6 }  // Actions column
            ],
            columns: [
                { data: 'user' },
                { data: 'email' },
                { data: 'role' },
                { data: 'gender' },
                { data: 'phone' },
                { data: 'birthDate' },
                { data: 'actions' }
            ],
            ajax: {
                url: 'https://dummyjson.com/users/search',
                method: 'GET',
                dataType: 'json',
                data: function (d) {
                    return {
                        q: d.search.value,
                        limit: d.length,
                        skip: d.start || 0
                    };
                },
                dataSrc: function (response) {
                    response.recordsTotal = response.total;
                    response.recordsFiltered = response.total;
                    return userMap(response.users);
                },
                error: ajaxErrorHandler
            },
            drawCallback: function (settings) {
                $(this).find('.ui.dropdown').dropdown();
            },
            initComplete: function (settings, json) {
                // this.api().columns().every(function () {
                //     $(this.header()).css('position', 'static');
                // });

                // Bind search event
                // $('.user-search input').on('keyup', _.debounce(function () {
                //     const searchQuery = $(this).val().trim();
                //     $usersDataTable.search(searchQuery).draw();
                // }, 300));
            }
        });

        function userMap(users) {
            return users.map(user => ({
                user: `<div class="user-details">
                        <img class="ui avatar image" src="${user.image}" alt="${user.username}" />
                        <div class="info d-flex flex-column">
                            <span>${user.firstName} ${user.lastName}</span>
                            <small>ID: ${user.ein}</small>
                        </div>
                    </div>`,
                email: `<span class="email-td">${user.email}</span>`,
                role: `<span class="role-td text-capitalize">${user.role}</span>`,
                gender: `<span class="gender-td text-capitalize">${user.gender}</span>`,
                phone: `<span class="phone-td">${user.phone}</span>`,
                birthDate: `<span class="birth-date-td">${dateToMDY(user.birthDate)}</span>`,
                actions: `<div class="ui compact floating selection dropdown recent-orders-dd">
                            <i class="dropdown icon"></i>
                            <div class="text">Actions</div>
                            <div class="menu">
                                <div class="item" data-value="view">View</div>
                                <div class="item" data-value="edit">Edit</div>
                                <div class="item" data-value="delete">Delete</div>
                            </div>
                        </div>`,
                DT_RowId: `${user.id}`,
                DT_RowClass: 'user-item',
            }));
        }

        function validateUserForm($form = null) {
            let userFormConf = {
                form: $form,
                fields: {
                    name: {
                        identifier: "name",
                        rules: [
                            {
                                type: "empty",
                                prompt: "Please enter a name"
                            },
                            {
                                type: "minLength[2]",
                                prompt: "Name must be at least 2 characters"
                            }
                        ]
                    },
                    email: {
                        identifier: "email",
                        rules: [
                            {
                                type: "empty",
                                prompt: "Please enter an email"
                            },
                            {
                                type: "email",
                                prompt: "Please enter a valid email"
                            }
                        ]
                    },
                    role: {
                        identifier: "role",
                        rules: [
                            {
                                type: "empty",
                                prompt: "Please select a role"
                            }
                        ]
                    }
                },
            };
            validateHandler(userFormConf);
        }

        $(function () {
            tableListBaseFilters($usersDataTable);
            validateUserForm($userForm);
        });
    </script>
</body>

</html>
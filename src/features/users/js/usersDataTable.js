const $usersTable = $("#usersTable");
const $usersDataTable = $usersTable.DataTable({
    // dom: 't<"bottom-controls"<"info"i><"right-controls"l<"pagination"p>>>',
    // dom: 'rt<"dt-layout-row"<"dt-layout-start"i><"dt-layout-end"lp>>',
    layout: {
        topStart: null,
        topEnd: null,

        bottomStart: "info",
        bottomEnd: {
            pageLength: {},
            paging: {},
        },
    },
    pageLength: 10,
    deferRender: true,
    // stateSave: true,
    responsive: true,
    processing: true,
    serverSide: true,
    searching: true,
    orderCellsTop: true,
    autoWidth: false,
    scrollCollapse: true,
    scrollX: true,
    scrollY: "565px",
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
        { data: "user" },
        { data: "email" },
        { data: "role" },
        { data: "gender" },
        { data: "phone" },
        { data: "birthDate" },
        { data: "actions" },
    ],
    ajax: {
        url: "https://dummyjson.com/users/search",
        method: "GET",
        dataType: "json",
        data: function (d) {
            return {
                q: d.search.value,
                limit: d.length,
                skip: d.start || 0,
            };
        },
        dataSrc: function (response) {
            response.recordsTotal = response.total;
            response.recordsFiltered = response.total;
            return userMap(response.users);
        },
        error: ajaxErrorHandler,
    },
    drawCallback: function (settings) {
        $(this).find(".ui.dropdown").dropdown();
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
    },
});

function userMap(users) {
    return users.map((user) => ({
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
        birthDate: `<span class="birth-date-td">${dateToMDY(
            user.birthDate
        )}</span>`,
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
        DT_RowClass: "user-item",
    }));
}

$(function () {
    tableListBaseFilters($usersDataTable);
});

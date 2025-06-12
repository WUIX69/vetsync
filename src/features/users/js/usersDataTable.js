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
    columns: [
        { data: "user" },
        { data: "email" },
        { data: "role", orderable: false },
        { data: "location" },
        { data: "telephone" },
        { data: "dob" },
        { data: "created_at" },
        { data: "actions", orderable: false },
    ],
    // columns: [
    //     {
    //         data: null,
    //         render: function (data) {
    //             return `
    //                 <div class="user-details">
    //                     <img class="ui avatar image" src="${data.profile}" alt="${data.name}" />
    //                     <div class="info d-flex flex-column">
    //                         <span class="text-capitalize">${data.name}</span>
    //                         <small>ID: ${data.user_uuid}</small>
    //                     </div>
    //                 </div>
    //             `;
    //         },
    //     },
    //     { data: "email" },
    //     {
    //         data: null,
    //         orderable: false,
    //         render: function (data) {
    //             return `${data.role}`;
    //         },
    //     },
    //     { data: "location", orderable: false },
    //     { data: "telephone" },
    //     { data: "dob" },
    //     { data: "created_at" },
    //     {
    //         data: null,
    //         orderable: false,
    //         render: function (data) {
    //             return `
    //                 <div class="ui compact floating selection dropdown recent-orders-dd">
    //                     <i class="dropdown icon"></i>
    //                     <div class="text">Actions</div>
    //                     <div class="menu">
    //                         <div class="item" data-value="view"><i class="eye icon"></i> View</div>
    //                         <div class="item" data-value="edit"><i class="edit blue icon"></i> Edit</div>
    //                         <div class="item" data-value="delete"><i class="trash alternate outline red icon"></i> Delete</div>
    //                     </div>
    //                 </div>
    //             `;
    //         },
    //     },
    // ],
    ajax: {
        url: apiUrl("users") + "usersDataTable.php",
        method: "GET",
        dataType: "json",
        data: function (d) {
            return d;
        },
        dataSrc: function (response) {
            // console.log(response);
            // return false;
            return userMap(response.data);
            // return response.data;
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
                        <img class="ui avatar image" src="${user.profile}" alt="${user.name}" />
                        <div class="info d-flex flex-column">
                            <span class="text-capitalize">${user.name}</span>
                            <small>ID: ${user.user_uuid}</small>
                        </div>
                    </div>`,
        email: `${user.email}`,
        role: `${user.role}`,
        location: `${user.location}`,
        telephone: `${user.telephone}`,
        dob: `${user.dob}`,
        created_at: `${user.created_at}`,
        actions: `<div class="ui compact floating selection dropdown recent-orders-dd">
                        <i class="dropdown icon"></i>
                        <div class="text">Actions</div>
                        <div class="menu">
                            <div class="item" data-value="view"><i class="eye icon"></i> View</div>
                            <div class="item" data-value="edit"><i class="edit blue icon"></i> Edit</div>
                            <div class="item" data-value="delete"><i class="trash alternate outline red icon"></i> Delete</div>
                        </div>
                    </div>`,
        DT_RowId: `${user.user_uuid}`,
        DT_RowClass: "user-item",
    }));
}

$(function () {
    tableListBaseFilters($usersDataTable);
});

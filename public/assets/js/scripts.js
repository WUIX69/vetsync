function initDropdowns() {
    $(".ui.dropdown").dropdown({
        allowAdditions: false,
        forceSelection: true,
        autofocus: false,
        clearable: true,
    });
}

function initModal(options = {}) {
    const defaults = {
        modal_id: ".ui.modal",
        transition: "fly down",
        duration: 700,
    };

    const config = { ...defaults, ...options };
    const $modal = $(config.modal_id);
    const $form = $($modal.find("form")) ?? null;
    const $submitBtn = $modal.find("button[type='submit']") ?? null;

    if (!$modal || !$modal.length) return false;
    // if ($modal) $modal.modal("destroy");

    $modal.modal({
        blurring: false,
        closable: true,
        transition: config.transition,
        duration: config.duration,
        autofocus: false,
        onShow: () => {
            $modal.find(".ui.dropdown").dropdown();
        },
        onHide: () => {
            // $form[0].reset();
            $form.form("clear");
            $submitBtn.removeClass("loading");
        },
        onApprove: () => {
            if (!$form.form("is valid")) return false; //: Optional
            return false;
        },
    });
}

function ajaxErrorHandler(jqXHR, textStatus, errorThrown, error) {
    console.log("Detailed error information:");
    console.log("Stringy Response:", JSON.stringify(jqXHR));
    console.log("Server Response: ", jqXHR);
    console.log("Status of the request: ", textStatus);
    console.log("Error thrown by the request: ", errorThrown);
    console.log("Error:", error);
    return false;
}

function tableListBaseFilters($dataTable = null) {
    let isResetBaseFilters = false;

    const $tableListBaseFilters = $(".table-list .base-filters");
    const $tableListBaseFiltersResetBtn =
        $tableListBaseFilters.find(".filter-reset-btn");

    const showTblBfResetBtn = () => {
        return $tableListBaseFiltersResetBtn.show();
    };
    const hideTblBfResetBtn = () => {
        return $tableListBaseFiltersResetBtn.hide();
    };

    $tableListBaseFiltersResetBtn.on("click", function () {
        isResetBaseFilters = true; // Set flag to true

        $tableListBaseFilters.find(".ui.dropdown").dropdown("restore defaults");
        $tableListBaseFilters.find(".ui.search input").val("");
        hideTblBfResetBtn();
        if ($dataTable) $dataTable.search(null).draw();

        isResetBaseFilters = false; // Reset flag to false after use
    });

    $tableListBaseFilters.find(".ui.dropdown").dropdown({
        onChange: function (value) {
            if (isResetBaseFilters) return; // Prevent multiple resets

            showTblBfResetBtn();
            if (value === "clear") {
                $(this).dropdown("reset defaults");
                return false;
            }
        },
    });

    $tableListBaseFilters.find(".ui.search input").on(
        "keyup",
        _.debounce(function () {
            let value = $(this).val().trim();
            if ($dataTable) $dataTable.search(value).draw();

            let isAnyDropdownActive =
                $tableListBaseFilters.find(".ui.dropdown .item.active.selected")
                    .length > 0;
            if (!value && !isAnyDropdownActive) {
                hideTblBfResetBtn();
            } else {
                showTblBfResetBtn();
            }
        }, 500)
    );
}

function dateToMDY(date) {
    const d = new Date(date);
    return `${d.toLocaleString("default", {
        month: "long",
    })} ${d.getDate()}, ${d.getFullYear()}`;
}

function timeAgo(date) {
    const providedTime = new Date(date).getTime();
    const now = Date.now();

    const timeDifference = now - providedTime;

    if (timeDifference < 0) {
        return "Time is in the future";
    }

    const seconds = Math.floor(timeDifference / 1000);
    const minutes = Math.floor(seconds / 60);
    const hours = Math.floor(minutes / 60);
    const days = Math.floor(hours / 24);
    const years = Math.floor(days / 365.25);

    // More efficient and readable approach using an object lookup:
    const units = [
        { threshold: 5, text: "Just now" }, // Special case for very recent
        { threshold: 60, unit: "second", value: seconds },
        { threshold: 3600, unit: "minute", value: minutes },
        { threshold: 86400, unit: "hour", value: hours },
        { threshold: 31536000, unit: "day", value: days },
        { threshold: Infinity, unit: "year", value: years }, // Default case
    ];

    for (const unit of units) {
        if (seconds < unit.threshold) {
            return (
                unit.text ||
                `${unit.value} ${unit.unit}${unit.value > 1 ? "s" : ""} ago`
            );
        }
    }

    return null;
}

// Initialize form validation using Fomantic UI
function validateHandler(options = {}) {
    const defaults = {
        fields: {},
        form: null,
    };

    const config = { ...defaults, ...options };
    let $form = $(config.form); // Ensure $form is a jQuery object
    if (!$form || !$form.length) return false;

    let $submitBtn = $form.find("button[type=submit]");
    $form.attr("data-is-configured", true); // Set flag for later use

    $form.form({
        fields: config.fields,
        inline: true,
        on: "blur",
        onSuccess: function (event) {
            event.preventDefault();

            // // Double check button state before proceeding
            if ($submitBtn.hasClass("loading")) return false;
            $submitBtn.addClass("loading");

            const formData = new FormData(this);
            console.log(formData);
            return false;
        },
    });

    // $form.api({
    //     url: "",
    //     method: "POST",
    //     data: {},
    //     dataType: "json",
    //     serializeForm: true,
    //     beforeSend: function (settings) {
    //         console.log(settings);
    //         return false;
    //     },
    //     onSuccess: function (response) {
    //         console.log(response);
    //         return false;
    //     },
    // });
}

// Not used yet
function initDataTables(options = {}) {
    const defaults = {
        table: null,
        data: {},
    };

    const config = { ...defaults, ...options };
    const $table = $(config.table);

    if (!$table || !$table.length) return false;
    // if ($table.DataTable()) $table.DataTable().destroy();

    $table.DataTable({
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
        scrollY: "500px",
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
        columnDefs: config.columnDefs,
        columns: config.columns,
        ajax: {
            url: config.url,
            method: "GET",
            dataType: "json",
            data: config.data,
            dataSrc: function (response) {
                response.recordsTotal = response.total;
                response.recordsFiltered = response.total;
                return config.map(response.users);
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
}

$(function () {
    // Initialize core components
    if ($(".ui.dropdown").length) initDropdowns();
    if ($(".ui.modal").length) initModal();

    // Modal opening via data attribute
    $("body").on("click", "[data-open-modal]", function () {
        const modalId = $(this).data("open-modal");
        $(modalId).modal("show");
    });

    // Prevent submission of unconfigured forms
    $("body").on("click", ".ui.form button[type=submit]", function () {
        let isConfigured = $(this).closest(".ui.form").data("is-configured");
        if (!isConfigured) return false;
    });
});

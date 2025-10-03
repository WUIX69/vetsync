// Products Table variables
const productSection = $("section.products-table");
const productsTableBody = productSection.find("table tbody");

// Product Modal variables
const productModal = $("#productModal");
const productModalForm = productModal.find("form");

// Store all products and categories for filtering
let allProducts = [];
let allCategories = [];

// Filepond Flags (Required)
let isModalHide = false;
let isPondRender = false;

// Product Image FilePond
const productImagePond = FilePond.create(
    document.querySelector(".product-pond"),
    {
        maxFiles: 2,
        maxFileSize: "2MB",
        allowMultiple: true,
        allowFileTypes: ["image/*"],
        labelIdle: `Drag & Drop your image or <span class="filepond--label-action">Browse</span>`,
        imagePreviewHeight: 170,
        imageCropAspectRatio: "1:1",
        imageResizeTargetWidth: 200,
        imageResizeTargetHeight: 200,
        onprocessfile: function (error, file) {
            // console.log("On Process Files:", file);
        },
        onaddfile: function (error, file) {
            // console.log("On Add File:", file);
        },
        onremovefile: function (error, file) {
            // console.log("On Remove File:", file);

            // Only handle "local" files (already on server) and only if not modal hide
            if (file.origin === 3 && !isModalHide) {
                console.log("is local delete");
                $.ajax({
                    url: apiUrl("shared") + "filepond.php",
                    headers: {
                        "X-Reference-Model": "products",
                    },
                    method: "DELETE",
                    data: file.serverId,
                    processData: false,
                    contentType: false,
                    error: ajaxErrorHandler,
                });
            }
        },
        onupdatefiles: function (files) {
            // const totalFileSize = files.reduce(
            //     (total, file) => total + file.fileSize,
            //     0
            // );
            // const showErrorState = totalFileSize > 26214400; // 25MB in bytes
            // console.log({ totalFileSize, showErrorState });
        },
        server: {
            url: apiUrl() + "filepond.php",
            headers: {
                "X-Reference-Model": "products",
            },
            timeout: 7000,
            withCredentials: false,
            process: {
                url: "",
            },
            revert: {
                url: "",
            },
            load: {
                url: "?folder=",
            },
        },
    }
);

function getAllProducts() {
    productsTableBody.empty();

    $.ajax({
        url: apiUrl("products") + "products.php",
        method: "GET",
        data: {
            action: "all",
        },
        dataType: "json",
        timeout: 5000,
        success: function (response) {
            if (!response.success) {
                alert(response.message);
                return false;
            }

            allProducts = response.data;
            renderProducts(allProducts);
        },
        complete: function () {
            productsTableBody.find(".ui.dropdown").dropdown();
            productsTableBody.find(".actions-dd").dropdown({
                onChange: function (value) {
                    const productUuid = $(this)
                        .closest(".product-item")
                        .data("product-uuid");

                    if (value === "view" || value === "edit") {
                        getSingleProduct(productUuid);
                    } else if (value === "delete") {
                        deleteProduct(productUuid);
                    }
                },
            });
        },
        error: ajaxErrorHandler,
    });
}

function loadProductCategories() {
    $.ajax({
        url: apiUrl("shared") + "categories.php",
        method: "GET",
        headers: {
            "X-Reference-Model": "products",
        },
        data: {
            action: "all",
        },
        dataType: "json",
        timeout: 5000,
        success: function (response) {
            if (response.success) {
                allCategories = response.data;
                populateCategoryDropdown();
            }
        },
        error: ajaxErrorHandler,
    });
}

function populateCategoryDropdown() {
    const $dropdown = $(".table-filters .ui.dropdown").has(
        'input[name="category-filter"]'
    );
    const $menu = $dropdown.find(".menu");

    $menu.empty();
    $menu.append('<div class="item" data-value="all">All Categories</div>');

    allCategories.forEach((category) => {
        if (category.status === "active") {
            $menu.append(`
                <div class="item" data-value="${category.name}">
                    <i class="${category.icon} icon"></i>
                    ${category.name}
                </div>
            `);
        }
    });

    $dropdown.dropdown("refresh");
}

function renderProducts(products) {
    productsTableBody.empty();

    if (products.length === 0) {
        productsTableBody.append(`
            <tr>
                <td colspan="10" class="center aligned">
                    <p class="text-muted">No products found</p>
                </td>
            </tr>
        `);
        return;
    }

    let productsHTML = "";

    products.forEach((product) => {
        productsHTML += `
            <tr class="product-item" data-product-uuid="${product.uuid}">
                <td>
                    <img src="${product.image}" alt="Product" class="product-image">
                </td>
                <td class="product-name">${product.name}</td>
                <td class="product-category">
                    <i class="${product.category.icon} icon"></i>
                    ${product.category.label}
                </td>
                <td class="product-price">&#8369; ${product.og_price}</td>
                <td>
                    <span class="text-capitalize product-status ${product.status.label}">
                        <i class="${product.status.icon} icon"></i>
                        ${product.status.label}
                    </span>
                </td>
                <td>
                    <span class="text-capitalize product-tags">
                        ${product.tags}
                    </span>
                </td>
                <td>
                    <span class="text-capitalize product-specs">
                        ${product.specs}
                    </span>
                </td>
                <td>${product.created_at}</td>
                <td>${product.updated_at}</td>
                <td>
                    <div class="ui compact floating selection dropdown actions-dd">
                        <i class="dropdown icon"></i>
                        <div class="text">Actions</div>
                        <div class="menu">
                            <div class="item" data-value="view"><i class="eye icon"></i>View</div>
                            <div class="item" data-value="edit"><i class="edit blue icon"></i>Edit</div>
                            <div class="item" data-value="delete"><i class="trash alternate outline red icon"></i>Delete</div>
                        </div>
                    </div>
                </td>
            </tr>
        `;
    });

    productsTableBody.append(productsHTML);

    productsTableBody.find(".ui.dropdown").dropdown();
    productsTableBody.find(".actions-dd").dropdown({
        onChange: function (value) {
            const productUuid = $(this)
                .closest(".product-item")
                .data("product-uuid");

            if (value === "view" || value === "edit") {
                getSingleProduct(productUuid);
            } else if (value === "delete") {
                deleteProduct(productUuid);
            }
        },
    });
}

function filterProducts() {
    const searchTerm = $(".service-search input.prompt")
        .val()
        .toLowerCase()
        .trim();
    const statusFilter = $('input[name="status-filter"]').val();
    const categoryFilter = $('input[name="category-filter"]').val();

    let filtered = allProducts;

    // Search filter
    if (searchTerm) {
        filtered = filtered.filter((product) => {
            const tags = Array.isArray(product.tags)
                ? product.tags.join(" ")
                : product.tags || "";
            const specs = Array.isArray(product.specs)
                ? product.specs.join(" ")
                : product.specs || "";

            return (
                product.name.toLowerCase().includes(searchTerm) ||
                product.description.toLowerCase().includes(searchTerm) ||
                product.category.label.toLowerCase().includes(searchTerm) ||
                tags.toLowerCase().includes(searchTerm) ||
                specs.toLowerCase().includes(searchTerm)
            );
        });
    }

    // Status filter
    if (statusFilter && statusFilter !== "all") {
        filtered = filtered.filter((product) => {
            return (
                product.status.label.toLowerCase() ===
                statusFilter.toLowerCase()
            );
        });
    }

    // Category filter
    if (categoryFilter && categoryFilter !== "all") {
        filtered = filtered.filter((product) => {
            return (
                product.category.label.toLowerCase() ===
                categoryFilter.toLowerCase()
            );
        });
    }

    renderProducts(filtered);
}

function getSingleProduct(productUuid = null) {
    if (!productUuid) return false;
    // loadExistingFiles(productUuid);

    $.ajax({
        url: apiUrl("products") + "products.php",
        method: "GET",
        data: {
            action: "single",
            uuid: productUuid,
        },
        dataType: "json",
        timeout: 5000,
        success: function (response) {
            // console.log("API Response:", response);
            // return false;

            if (!response.success) {
                alert(response.message);
                return false;
            }

            // Populate the form fields
            const product = response.data;
            $.each(product, function (key, value) {
                if (key === "files") {
                    // Prevent FilePond from deleting files when modal is hidden and !empty files
                    if (value.length > 0) isPondRender = true;

                    // Add files to FilePond
                    value.forEach(function (file) {
                        productImagePond
                            .addFile(file.folder, {
                                type: "local",
                                options: {
                                    file: {
                                        name: file.filename,
                                    },
                                    metadata: {
                                        serverId: file.folder,
                                    },
                                },
                            })
                            .then(function (fileItem) {
                                // console.log("Added fileItem:", fileItem);
                            });
                    });
                } else {
                    productModalForm.find('[name="' + key + '"]').val(value);
                }
            });

            // Show the modal
            productModal.modal("show");
        },
        error: ajaxErrorHandler,
    });
}

function deleteProduct(productUuid = null) {
    if (!productUuid) return false;

    // console.log(productUuid);
    // return false;

    $.ajax({
        url: apiUrl("products") + "products.php?uuid=" + productUuid,
        method: "DELETE",
        dataType: "json",
        timeout: 5000,
        success: function (response) {
            // console.log("API Response:", response);
            // return false;

            alert(response.message);
            if (!response.success) return false;
            getAllProducts(); // Refresh the products data
        },
        error: ajaxErrorHandler,
    });
}

$(function () {
    loadProductCategories();
    getAllProducts();

    // Initialize dropdowns with onChange
    $(".table-filters .ui.dropdown").dropdown({
        onChange: function () {
            filterProducts();
        },
    });

    // Search on input
    $(".service-search input.prompt").on("input", function () {
        filterProducts();
    });

    // Click on table row to open edit modal
    $("body").on("click", ".product-item", function (e) {
        // Don't trigger if clicking on dropdown
        if ($(e.target).closest(".ui.dropdown").length) return;

        const productUuid = $(this).data("product-uuid");
        getSingleProduct(productUuid);
    });

    // Validate Product Modal Form
    productModalForm.form({
        fields: {
            name: {
                identifier: "name",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter a name",
                    },
                ],
            },
            description: {
                identifier: "description",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter a description",
                    },
                ],
            },
            og_price: {
                identifier: "og_price",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please enter an original price",
                    },
                    {
                        type: "decimal",
                        prompt: "Please enter a valid original price",
                    },
                ],
            },
            dc_price: {
                identifier: "dc_price",
                optional: true,
                rules: [
                    {
                        type: "decimal",
                        prompt: "Please enter a valid discounted price",
                    },
                ],
            },
            status: {
                identifier: "status",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please select a status",
                    },
                ],
            },
            category_id: {
                identifier: "category_id",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please select a category",
                    },
                ],
            },
            tags: {
                identifier: "tags",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please select a tag",
                    },
                    {
                        type: "minCount[2]",
                        prompt: "Please select at least 2 tags",
                    },
                ],
            },
            specs: {
                identifier: "specs",
                rules: [
                    {
                        type: "empty",
                        prompt: "Please select a spec",
                    },
                    {
                        type: "minCount[2]",
                        prompt: "Please select at least 2 specs",
                    },
                ],
            },
        },
        inline: true,
        on: "submit",
        onSuccess: function (event, fields) {
            event.preventDefault();
            const $submitBtn = $(this).find("button[type=submit]");
            const formData = new FormData(productModalForm[0]);

            let action = "store";
            if (formData.get("uuid")) action = "update";
            formData.append("action", action);

            // Collect all FilePond serverIds (folder names)
            let files = productImagePond.getFiles().map((f) => f.serverId);
            formData.set("files", files.join(","));
            formData.delete("file");

            // console.log(formData);
            // return false;

            $.ajax({
                url: apiUrl("products") + "products.php", // Change to your actual products endpoint if needed
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                timeout: 5000,
                beforeSend: function () {
                    $submitBtn.addClass("loading");
                },
                success: function (response) {
                    // console.log(response);
                    // return false;

                    alert(response.message);
                    getAllProducts(); // Refresh the products data
                    isPondRender = true; // Set the flag BEFORE hiding the modal, For FilePond to know that the form is submitting on hide
                    productModal.modal("hide");
                },
                complete: function () {
                    $submitBtn.removeClass("loading");
                },
                error: ajaxErrorHandler,
            });
        },
    });
});

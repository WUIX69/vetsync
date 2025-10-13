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
            renderProducts(response.data);
        },
        error: ajaxErrorHandler,
    });
}

function renderProducts(products = []) {
    productsTableBody.empty();

    if (products.length === 0) {
        productsTableBody.append(`
            <tr>
                <td colspan="9" class="center aligned">
                    <div class="ui icon message">
                        <i class="inbox icon"></i>
                        <div class="content">
                            <div class="header">No Products Found</div>
                            <p>No products match your current filters.</p>
                        </div>
                    </div>
                </td>
            </tr>
        `);
        return;
    }

    products.forEach((product) => {
        productsTableBody.append(`
            <tr class="product-item" data-product-uuid="${product.uuid}">
                <td>
                    <img src="${product.image}" alt="${
            product.name
        }" class="ui mini image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                </td>
                <td>${product.name}</td>
                <td>${product.category}</td>
                <td>₱${parseFloat(product.og_price).toFixed(2)}</td>
                <td>${product.status}</td>
                <td>
                    ${
                        product.tagsCount > 0
                            ? `<span class="ui mini label">${product.tagsCount} tags</span>`
                            : '<span class="ui mini basic label">No tags</span>'
                    }
                </td>
                <td>
                    ${
                        product.specsCount > 0
                            ? `<span class="ui mini label">${product.specsCount} specs</span>`
                            : '<span class="ui mini basic label">No specs</span>'
                    }
                </td>
                <td>${product.created_at}</td>
                <td>
                    <div class="ui compact menu">
                        <div class="ui simple dropdown item">
                            <i class="ellipsis vertical icon"></i>
                            <div class="menu">
                                <div class="item edit-product" data-product-uuid="${
                                    product.uuid
                                }">
                                    <i class="edit icon"></i> Edit
                                </div>
                                <div class="item delete-product" data-product-uuid="${
                                    product.uuid
                                }">
                                    <i class="trash icon"></i> Delete
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        `);
    });

    // Reinitialize dropdown after rendering
    $(".ui.dropdown").dropdown();

    // Delete product
    $("body").on("click", ".delete-product", function (e) {
        e.stopPropagation();
        const productUuid = $(this).data("product-uuid");

        if (!confirm("Are you sure you want to delete this product?")) return;

        $.ajax({
            url: apiUrl("products") + "products.php?uuid=" + productUuid,
            method: "DELETE",
            dataType: "json",
            timeout: 5000,
            success: function (response) {
                alert(response.message);
                getAllProducts();
            },
            error: ajaxErrorHandler,
        });
    });
}

function loadProductCategories() {
    $.ajax({
        url: apiUrl("categories") + "categories.php",
        method: "GET",
        data: {
            action: "all",
            reference: "products",
        },
        dataType: "json",
        timeout: 5000,
        success: function (response) {
            if (!response.success) {
                alert(response.message);
                return false;
            }

            allCategories = response.data;

            // Populate category filter
            const categoryFilter = $(".table-filters .category-filter .menu");
            categoryFilter.empty();
            categoryFilter.append(
                `<div class="item" data-value="">All Categories</div>`
            );

            response.data.forEach((category) => {
                categoryFilter.append(`
                    <div class="item" data-value="${category.id}">
                        <i class="${category.icon} icon"></i>
                        ${category.name}
                    </div>
                `);
            });

            // Reinitialize dropdown
            $(".table-filters .ui.dropdown").dropdown();
        },
        error: ajaxErrorHandler,
    });
}

function filterProducts() {
    const searchTerm = $(".service-search input.prompt")
        .val()
        .toLowerCase()
        .trim();
    const categoryId = $(".table-filters .category-filter").dropdown(
        "get value"
    );
    const status = $(".table-filters .status-filter").dropdown("get value");

    let filtered = allProducts;

    // Filter by search term
    if (searchTerm) {
        filtered = filtered.filter((product) => {
            return product.name.toLowerCase().includes(searchTerm);
        });
    }

    // Filter by category
    if (categoryId) {
        filtered = filtered.filter((product) => {
            return product.category_id == categoryId;
        });
    }

    // Filter by status
    if (status) {
        filtered = filtered.filter((product) => {
            return product.status.toLowerCase().includes(status.toLowerCase());
        });
    }

    renderProducts(filtered);
}

function getSingleProduct(productUuid = null) {
    if (!productUuid) return false;

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

            // Initialize dropdowns with current values
            productModalForm.find(".ui.dropdown").each(function () {
                const value = $(this).find("input[type=hidden]").val();
                if (value) {
                    $(this).dropdown("set selected", value);
                }
            });

            // Show the modal
            productModal.modal("show");
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

    // Remove files from FilePond when modal is hidden
    productModal.modal("setting", "onHide", function () {
        isModalHide = true;
        if (!isPondRender) {
            // Delete files from storage and FilePond
            productImagePond.removeFiles({ revert: true });
        } else {
            // Delete files from FilePond UI
            productImagePond.removeFiles();
            // Reset the flag for next time
            isPondRender = false;
        }
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
                url: apiUrl("products") + "products.php",
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

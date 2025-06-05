/*
Products Management JavaScript
$(document).ready(function () {
    // Open Product Modal
    $("#addProductBtn").on("click", function () {
        $("#modalTitle").text("Add New Product");
        $("#productForm")[0].reset();
        $("#imagePreview").attr("src", '<?= asset("img/placeholder.jpg") ?>');
        $("#productId").val("");
        $("#productModal").addClass("active");
    });

    // Close Product Modal
    $("#closeModal, #cancelBtn").on("click", function () {
        $("#productModal").removeClass("active");
    });

    // Image Preview
    $("#productImage").on("change", function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $("#imagePreview").attr("src", e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    // Edit Product
    $(".btn-edit").on("click", function () {
        const productId = $(this).data("product-id");
        $("#modalTitle").text("Edit Product");
        $("#productId").val(productId);

        // Populate form with product data (would come from API/database in real app)
        if (productId === 1) {
            $("#productName").val("Premium Dry Dog Food");
            $("#productCategory").val("dog-food");
            $("#productPrice").val("22.99");
            $("#productStock").val("45");
            $("#productDescription").val(
                "High-quality dry dog food with balanced nutrition for adult dogs. Contains chicken, rice, and essential vitamins."
            );
            $("#productStatus").val("available");
            $("#imagePreview").attr(
                "src",
                '<?= asset("img/contents/products/pdogfood.jpg") ?>'
            );
        } else if (productId === 2) {
            $("#productName").val("Joint Health Supplements");
            $("#productCategory").val("supplements");
            $("#productPrice").val("18.50");
            $("#productStock").val("32");
            $("#productDescription").val(
                "Support your pet's joint health with these premium supplements. Ideal for senior pets or those with mobility issues."
            );
            $("#productStatus").val("available");
            $("#imagePreview").attr(
                "src",
                '<?= asset("img/contents/products/vitamins.jpg") ?>'
            );
        } else if (productId === 3) {
            $("#productName").val("Puppy Growth Formula");
            $("#productCategory").val("dog-food");
            $("#productPrice").val("28.50");
            $("#productStock").val("0");
            $("#productDescription").val(
                "Specially formulated for growing puppies with essential nutrients to support healthy development and strong immune systems."
            );
            $("#productStatus").val("unavailable");
            $("#imagePreview").attr(
                "src",
                '<?= asset("img/contents/products/petfood.jpg") ?>'
            );
        }

        $("#productModal").addClass("active");
    });

    // Toggle Product Status
    $(".btn-toggle").on("click", function () {
        const productId = $(this).data("product-id");
        const currentStatus = $(this).closest("tr").find(".product-status");

        if (currentStatus.hasClass("status-available")) {
            currentStatus
                .removeClass("status-available")
                .addClass("status-unavailable")
                .text("Unavailable");
            $(this).find("i").removeClass("toggle off").addClass("toggle on");
        } else {
            currentStatus
                .removeClass("status-unavailable")
                .addClass("status-available")
                .text("Available");
            $(this).find("i").removeClass("toggle on").addClass("toggle off");
        }
    });

    // Delete Product
    $(".btn-delete").on("click", function () {
        const productId = $(this).data("product-id");
        if (confirm("Are you sure you want to delete this product?")) {
            $(this).closest("tr").remove();
        }
    });

    // Save Product
    $("#saveProductBtn").on("click", function () {
        // Validate form
        if (!$("#productForm")[0].checkValidity()) {
            $("#productForm")[0].reportValidity();
            return;
        }

        // In a real application, you would submit form data to server
        alert("Product saved successfully!");
        $("#productModal").removeClass("active");
    });

    // Search Products
    $("#searchProducts").on("keyup", function () {
        const value = $(this).val().toLowerCase();
        $("table tbody tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Filter by Category
    $("#categoryFilter").on("change", function () {
        const value = $(this).val().toLowerCase();
        if (value === "") {
            $("table tbody tr").show();
        } else {
            $("table tbody tr")
                .filter(function () {
                    return (
                        $(this)
                            .find("td:nth-child(3)")
                            .text()
                            .toLowerCase()
                            .replace(" ", "-") === value
                    );
                })
                .show();
            $("table tbody tr")
                .filter(function () {
                    return (
                        $(this)
                            .find("td:nth-child(3)")
                            .text()
                            .toLowerCase()
                            .replace(" ", "-") !== value
                    );
                })
                .hide();
        }
    });
});
*/

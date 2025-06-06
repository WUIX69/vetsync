function getProductsData() {
    // Table Data
    const products = [
        {
            image: "img/contents/products/pdogfood.jpg",
            name: "Premium Dry Dog Food",
            category: "Dog Food",
            price: "$22.99",
            stock: 45,
            status: "available",
        },
        {
            image: "img/contents/products/vitamins.jpg",
            name: "Joint Health Supplements",
            category: "Supplements",
            price: "$18.50",
            stock: 32,
            status: "available",
        },
        {
            image: "img/contents/products/petfood.jpg",
            name: "Puppy Growth Formula",
            category: "Dog Food",
            price: "$28.50",
            stock: 0,
            status: "unavailable",
        },
        {
            image: "img/contents/products/petcollar.jpg",
            name: "Pet Collar",
            category: "Accessories",
            price: "$24.99",
            stock: 18,
            status: "available",
        },
        {
            image: "img/contents/products/supplements.jpg",
            name: "Pet Supplements",
            category: "Supplements",
            price: "$12.00",
            stock: 27,
            status: "available",
        },
        {
            image: "img/contents/products/pet-accessories.jpg",
            name: "Pet Accessories",
            category: "Accessories",
            price: "$9.99",
            stock: 0,
            status: "unavailable",
        },
    ];

    let productsTableBody = $("#productsTableBody");
    productsTableBody.empty();

    let productsHTML = "";
    products.forEach((product, idx) => {
        const statusClass =
            product.status === "available"
                ? "product-status available"
                : "product-status unavailable";
        const statusText =
            product.status.charAt(0).toUpperCase() + product.status.slice(1);
        const toggleIcon =
            product.status === "available"
                ? "toggle off icon"
                : "toggle on icon";
        productsHTML += `
            <tr>
                <td>
                    <img src="${asset(
                        product.image
                    )}" alt="Product" class="product-image">
                </td>
                <td class="product-name">${product.name}</td>
                <td class="product-category">${product.category}</td>
                <td class="product-price">${product.price}</td>
                <td class="product-stock">${product.stock}</td>
                <td><span class="${statusClass}">${statusText}</span></td>
                <td>
                    <div class="ui compact floating selection dropdown actions-dd">
                        <i class="dropdown icon"></i>
                        <div class="text">Actions</div>
                        <div class="menu">
                            <div class="item" data-value="view">View</div>
                            <div class="item" data-value="edit">Edit</div>
                            <div class="item" data-value="delete">Delete</div>
                        </div>
                    </div>
                </td>
            </tr>
        `;
    });

    productsTableBody.append(productsHTML);
    productsTableBody.find(".ui.dropdown").dropdown();

    // Add event listener to dropdown
    productsTableBody.find(".actions-dd").dropdown({
        onChange: function (value) {
            console.log(value);
            // Add your logic for view, edit, delete here
        },
    });
}
$(function () {
    getProductsData();
});

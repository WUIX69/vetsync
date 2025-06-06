function getProductCategoriesData() {
    // Product Categories Data
    const categories = [
        {
            icon: "paw",
            name: "Accessories",
            description: "Collars, leashes, toys, and other pet accessories.",
            status: "Available",
        },
        {
            icon: "medkit",
            name: "Supplements",
            description:
                "Vitamins and supplements for pet health and wellness.",
            status: "Available",
        },
        {
            icon: "food",
            name: "Dog Food",
            description: "Premium dry and wet food for dogs of all ages.",
            status: "Available",
        },
        {
            icon: "shower",
            name: "Grooming",
            description: "Shampoos, conditioners, and grooming tools for pets.",
            status: "Available",
        },
    ];

    let categoriesTableBody = $("#productCategoriesTableBody");
    categoriesTableBody.empty();

    let categoriesHTML = "";
    categories.forEach((cat) => {
        categoriesHTML += `
            <tr>
                <td><i class="${cat.icon} icon"></i></td>
                <td>${cat.name}</td>
                <td>${cat.description}</td>
                <td>${cat.status}</td>
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

    categoriesTableBody.append(categoriesHTML);
    categoriesTableBody.find(".ui.dropdown").dropdown();

    // Add event listener to dropdown
    categoriesTableBody.find(".actions-dd").dropdown({
        onChange: function (value) {
            console.log(value);
            // Add your logic for view, edit, delete here
        },
    });
}
$(function () {
    getProductCategoriesData();
});

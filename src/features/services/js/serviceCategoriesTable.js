function getServiceCategoriesData() {
    // Service Categories Data
    const categories = [
        {
            icon: "stethoscope",
            name: "Examination",
            description:
                "Routine and comprehensive health checks for your pets.",
            status: "Available",
        },
        {
            icon: "syringe",
            name: "Treatment",
            description:
                "Medical treatments and care for various pet conditions.",
            status: "Available",
        },
        {
            icon: "cut",
            name: "Surgery",
            description: "Safe and professional surgical procedures for pets.",
            status: "Available",
        },
        {
            icon: "paw",
            name: "Grooming",
            description: "Bathing, trimming, and overall grooming services.",
            status: "Available",
        },
    ];

    let categoriesTableBody = $("#serviceCategoriesTableBody");
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
    getServiceCategoriesData();
});

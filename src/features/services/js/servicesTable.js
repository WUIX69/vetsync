function getServicesData() {
    // Table Data
    const services = [
        {
            name: "General Examination",
            description:
                "Comprehensive check-up of your pet's overall health status, including weight, temperature, heart and lung sounds, and more.",
            price: "$45.00",
            duration: "30 minutes",
            category: "Examination",
            status: "available",
        },
        {
            name: "Pet Grooming",
            description:
                "Professional grooming service including bath, hair trimming, nail clipping, ear cleaning, and more based on your pet's needs.",
            price: "$65.00",
            duration: "60 minutes",
            category: "Grooming",
            status: "busy",
        },
        {
            name: "Vaccination",
            description:
                "Essential vaccinations to protect your pet against common diseases. Based on age, lifestyle, and previous vaccination history.",
            price: "$35.00",
            duration: "15 minutes",
            category: "Treatment",
            status: "soon",
        },
        {
            name: "Dental Cleaning",
            description:
                "Professional dental cleaning to prevent oral diseases and maintain your pet's dental health.",
            price: "$80.00",
            duration: "45 minutes",
            category: "Treatment",
            status: "available",
        },
        {
            name: "X-Ray",
            description:
                "Digital radiography for accurate diagnosis of internal issues and injuries.",
            price: "$120.00",
            duration: "20 minutes",
            category: "Diagnostics",
            status: "busy",
        },
        {
            name: "Surgery Consultation",
            description:
                "Consultation and pre-surgical assessment for planned procedures.",
            price: "$55.00",
            duration: "40 minutes",
            category: "Surgery",
            status: "available",
        },
        {
            name: "Nutritional Advice",
            description:
                "Personalized nutrition plans and advice for your pet's optimal health.",
            price: "$30.00",
            duration: "25 minutes",
            category: "Consultation",
            status: "available",
        },
    ];

    let servicesTableBody = $("#servicesTableBody");
    servicesTableBody.empty();

    let servicesHTML = "";
    services.forEach((service) => {
        const statusClass =
            service.status === "available"
                ? "status available"
                : service.status === "unavailable"
                ? "status unavailable"
                : service.status === "busy"
                ? "status busy"
                : service.status === "soon"
                ? "status soon"
                : "status";
        const statusIcon =
            service.status === "available"
                ? '<i class="check circle icon"></i>'
                : service.status === "unavailable"
                ? '<i class="times circle icon"></i>'
                : service.status === "busy"
                ? '<i class="clock outline icon"></i>'
                : service.status === "soon"
                ? '<i class="hourglass half icon"></i>'
                : "";
        const statusText =
            service.status.charAt(0).toUpperCase() + service.status.slice(1);
        servicesHTML += `
            <tr>
                <td>${service.name}</td>
                <td>${service.description}</td>
                <td>${service.price}</td>
                <td>${service.duration}</td>
                <td>${service.category}</td>
                <td><span class="service-status ${statusClass}">${statusIcon} ${statusText}</span></td>
                <td>
                    <div class="ui compact floating selection dropdown services-list-actions-dd">
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

    servicesTableBody.append(servicesHTML);
    servicesTableBody.find(".ui.dropdown").dropdown();

    // Add event listener to dropdown
    $(".services-list-actions-dd").dropdown({
        onChange: function (value) {
            console.log(value);
            // Add your logic for view, edit, delete here
        },
    });
}

$(function () {
    getServicesData();
});

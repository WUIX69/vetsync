// src/features/services/js/user-services-list.js

const servicesList = {
    init() {
        this.servicesContainer = $(".services .row.g-4");
        this.loadServices();
        this.startAutoRefresh();
    },

    loadServices() {
        $.ajax({
            url: apiUrl("services") + "services.php",
            method: "GET",
            data: {
                action: "all",
            },
            success: (response) => {
                if (!response.success) {
                    console.error("API Error:", response.message);
                    return;
                }
                this.renderServices(response.data);
            },
            error: (xhr, status, error) => {
                console.error("Error loading services:", error);
            },
        });
    },

    renderServices(services) {
        let html = "";

        services.forEach((service) => {
            // Format price to include peso sign if not already included
            const price = service.price.startsWith("₱")
                ? service.price
                : `₱${service.price}`;

            // Handle status badge styling
            const statusClass = service.status.label.toLowerCase();
            const statusBadge = `
                <span class="${statusClass}">
                    <i class="circle icon"></i>
                    ${service.status.label}
                </span>
            `;

            // Handle tag if exists
            const tagHtml = service.tag
                ? `
                <div class="service-tag">
                    <span class="ui ${service.tag.color} tag label">
                        <i class="tag icon"></i>
                        ${service.tag.label}
                    </span>
                </div>
            `
                : "";

            // Get category icon - use a default icon if none exists
            const categoryIcon = service.category?.icon || "syringe"; // Default to syringe icon

            // Check if service is available for booking
            const isAvailable = statusClass === "available";

            // Generate booking button based on availability
            const bookingButtonHtml = isAvailable
                ? `
                <button type="button" 
                        class="book-now-btn" 
                        data-open-modal="#bookNowModal"
                        data-service-uuid="${service.uuid}">
                    Book Now <i class="arrow right icon"></i>
                </button>
            `
                : `
                <button type="button" 
                        class="book-now-btn disabled" 
                        disabled
                        title="Service is currently unavailable">
                    <i class="ban icon"></i> Unavailable
                </button>
            `;

            html += `
                <div class="col-lg-4">
                    <div class="service-card card">
                        <div class="card-img">
                            <!-- Status badge with original styling -->
                            ${statusBadge}
                            
                            <img src="${service.image}" alt="${service.name}">
                            ${tagHtml}
                        </div>
                        <div class="card-body">
                            <div class="service-header">
                                <h4>${service.name}</h4>
                                <i class="icon ${categoryIcon}"></i>
                            </div>
                            <div class="service-details">
                                <p>${service.description}</p>
                                <p class="duration"><strong>Duration:</strong> ${service.duration}</p>
                            </div>
                            <div class="service-meta">
                                <span class="price">${price}</span>
                                <div class="actions">
                                    <div class="service-view-btn-wrapper">
                                        <a href="/src/app/user/service-single-view.php?uuid=${service.uuid}" 
                                           class="service-view-btn ui eye icon" 
                                           title="View Service">
                                            <i class="eye icon"></i>View
                                        </a>
                                    </div>
                                    ${bookingButtonHtml}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        this.servicesContainer.html(html);
        $(".ui.dropdown").dropdown();
    },

    startAutoRefresh() {
        setInterval(() => this.loadServices(), 5000);
    },
};

$(document).ready(() => servicesList.init());

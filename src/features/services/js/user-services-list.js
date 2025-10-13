// src/features/services/js/user-services-list.js

const servicesList = {
    allServices: [],
    currentSort: "",
    searchTerm: "",

    init() {
        this.servicesContainer = $(".services .row.g-4");

        this.bindEvents();
        this.loadServices();
        this.startAutoRefresh();
    },

    bindEvents() {
        // Initialize sort dropdown with onChange handler
        $(".sort-dropdown").dropdown({
            onChange: (value) => {
                console.log("Sort changed to:", value); // Debug log
                this.currentSort = value;
                this.filterAndSort();
            },
        });

        // ✅ Get initial dropdown value if it exists
        const initialSort = $(".sort-dropdown").dropdown("get value");
        if (initialSort) {
            this.currentSort = initialSort;
            console.log("Initial sort value:", initialSort);
        }

        // Search with debounce
        let searchTimeout;
        $(".ui.search input.prompt").on("input", (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.searchTerm = $(e.target).val().toLowerCase().trim();
                this.filterAndSort();
            }, 300);
        });
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
                this.allServices = response.data;
                this.filterAndSort();
            },
            error: (xhr, status, error) => {
                console.error("Error loading services:", error);
            },
        });
    },

    filterAndSort() {
        let filtered = [...this.allServices];

        // Apply search filter
        if (this.searchTerm) {
            filtered = filtered.filter((service) => {
                return (
                    service.name.toLowerCase().includes(this.searchTerm) ||
                    service.description
                        .toLowerCase()
                        .includes(this.searchTerm) ||
                    (service.category?.label || "")
                        .toLowerCase()
                        .includes(this.searchTerm)
                );
            });
        }

        // Apply sorting
        if (this.currentSort) {
            filtered = this.sortServices(filtered, this.currentSort);
        } else {
            // Default sort by category ID (order in database)
            filtered.sort((a, b) => {
                const catA = parseInt(a.category_id) || 0;
                const catB = parseInt(b.category_id) || 0;
                return catA - catB;
            });
        }

        this.renderServices(filtered);
    },

    sortServices(services, sortBy) {
        const sorted = [...services];

        switch (sortBy) {
            case "newest":
                sorted.sort(
                    (a, b) =>
                        new Date(b.created_at_raw || 0) -
                        new Date(a.created_at_raw || 0)
                );
                break;

            case "price-low":
                sorted.sort((a, b) => {
                    const priceA = parseFloat(a.price) || 0;
                    const priceB = parseFloat(b.price) || 0;
                    return priceA - priceB;
                });
                break;

            case "price-high":
                sorted.sort((a, b) => {
                    const priceA = parseFloat(a.price) || 0;
                    const priceB = parseFloat(b.price) || 0;
                    return priceB - priceA;
                });
                break;

            case "popular":
                // Sort by appointment count if available, or booking_count
                sorted.sort(
                    (a, b) => (b.booking_count || 0) - (a.booking_count || 0)
                );
                break;

            case "rating":
                // Sort by rating if available
                sorted.sort(
                    (a, b) => (b.average_rating || 0) - (a.average_rating || 0)
                );
                break;

            default:
                // Default: sort by category_id (database order)
                sorted.sort((a, b) => {
                    const catA = parseInt(a.category_id) || 0;
                    const catB = parseInt(b.category_id) || 0;
                    return catA - catB;
                });
        }

        return sorted;
    },

    renderServices(services) {
        if (services.length === 0) {
            this.servicesContainer.html(`
                <div class="col-12">
                    <div class="ui placeholder segment" style="min-height: 200px;">
                        <div class="ui icon header">
                            <i class="search icon"></i>
                            No services found
                        </div>
                        <p>Try adjusting your search.</p>
                    </div>
                </div>
            `);
            return;
        }

        let html = "";

        services.forEach((service) => {
            // Format price to include peso sign if not already included
            const price = service.price.startsWith("₱")
                ? service.price
                : `₱${service.price}`;

            // ✅ Add "minutes" to duration
            const duration = service.duration + " minutes";

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
            const categoryIcon = service.category?.icon || "syringe";

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

            // ✅ Add rating display
            const averageRating = service.average_rating || 0;
            const reviewCount = service.review_count || 0;
            const ratingStars = generateRatingStars(averageRating);

            const ratingHtml =
                reviewCount > 0
                    ? `
                <div class="service-rating" style="display: flex; align-items: center; gap: 5px; margin-top: 10px;">
                    <div style="color: #ffc107; font-size: 14px;">${ratingStars}</div>
                    <span style="font-size: 13px; color: #6c757d;">${averageRating} (${reviewCount} review${
                          reviewCount !== 1 ? "s" : ""
                      })</span>
                </div>
            `
                    : "";

            html += `
                <div class="col-lg-4">
                    <div class="service-card card">
                        <div class="card-img">
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
                                <p class="duration"><strong>Duration:</strong> ${duration}</p>
                                ${ratingHtml}
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
        // ✅ Only initialize dropdowns inside service cards, NOT the sort dropdown
        this.servicesContainer.find(".ui.dropdown").dropdown();
    },

    startAutoRefresh() {
        setInterval(() => this.loadServices(), 30000);
    },
};

$(document).ready(() => servicesList.init());

// Helper function to generate rating stars
function generateRatingStars(rating) {
    let stars = "";
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 >= 0.5;

    // Add full stars
    for (let i = 0; i < fullStars; i++) {
        stars += "★";
    }

    // Add half star if needed
    if (hasHalfStar) {
        stars += "☆";
    }

    // Calculate remaining empty stars (fix the bug)
    const totalShown = fullStars + (hasHalfStar ? 1 : 0);
    const emptyStars = 5 - totalShown;

    // Add empty stars
    for (let i = 0; i < emptyStars; i++) {
        stars += "☆";
    }

    return stars;
}

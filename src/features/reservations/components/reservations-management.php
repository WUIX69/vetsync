<style>
    /**
     * Reservations Navigation START
     */
    main section.reservations .navigation {
        padding: 1.6rem !important;
    }

    main section.reservations .navigation .nav-pills {
        background: var(--color-white);
        display: flex !important;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin: 0;
    }

    main section.reservations .navigation .nav-pills .nav-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.7rem;
        padding: 1rem 1.5rem;
        border-radius: 1rem;
        transition: all 0.3s ease;
        text-align: center;
        cursor: pointer;
        width: 100%;
        color: var(--color-dark);
    }

    main section.reservations .navigation .nav-pills .nav-link:hover {
        background-color: rgba(0, 0, 0, .03);
    }

    main section.reservations .navigation .nav-pills .nav-link.active {
        background-color: var(--color-dark-variant);
        color: var(--color-white);
        box-shadow: 0 4px 15px rgba(33, 186, 69, 0.2);
    }

    /**
     * Reservations Cards START
     */
    .reservation-card {
        background: var(--color-white);
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-left: 4px solid var(--color-primary);
    }

    .reservation-card.pending {
        border-left-color: #f39c12;
    }

    .reservation-card.accepted {
        border-left-color: #27ae60;
    }

    .reservation-card.rejected {
        border-left-color: #e74c3c;
    }

    .reservation-card.completed {
        border-left-color: #3498db;
    }

    .reservation-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .reservation-status {
        padding: 0.4rem 0.8rem;
        border-radius: 0.5rem;
        font-size: 0.8rem;
        font-weight: bold;
        text-transform: uppercase;
    }

    .reservation-status.pending {
        background: #f39c12;
        color: white;
    }

    .reservation-status.accepted {
        background: #27ae60;
        color: white;
    }

    .reservation-status.rejected {
        background: #e74c3c;
        color: white;
    }

    .reservation-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .reservation-products {
        margin-bottom: 1rem;
    }

    .reservation-products h5 {
        margin-bottom: 0.5rem;
        color: var(--color-dark);
    }

    .product-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem;
        background: var(--color-light);
        border-radius: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .reservation-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .reservation-actions .btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        border: none;
        cursor: pointer;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .btn-accept {
        background: #27ae60;
        color: white;
    }

    .btn-reject {
        background: #e74c3c;
        color: white;
    }

    .btn-accept:hover {
        background: #219a52;
    }

    .btn-reject:hover {
        background: #c0392b;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--color-dark-variant);
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /**
     * Rejection Modal START
     */
    .rejection-modal .content {
        padding: 2rem;
    }

    .rejection-modal textarea {
        width: 100%;
        min-height: 100px;
        border: 1px solid #ddd;
        border-radius: 0.5rem;
        padding: 1rem;
        resize: vertical;
    }

    /* Modern Reservation Cards Design */
    .reservation-card.modern {
        background: var(--color-white);
        border-radius: 16px;
        padding: 0;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #f0f0f0;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .reservation-card.modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .reservation-card.modern.pending {
        border-top: 4px solid #f39c12;
    }

    .reservation-card.modern.accepted {
        border-top: 4px solid #27ae60;
    }

    .reservation-card.modern.rejected {
        border-top: 4px solid #e74c3c;
    }

    /* Header Section */
    .reservation-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid #f8f9fa;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    }

    .customer-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1rem;
    }

    .customer-details h4 {
        margin: 0;
        color: #2c3e50;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .customer-email {
        color: #7f8c8d;
        font-size: 0.85rem;
    }

    .status-badge {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.pending {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }

    .status-badge.accepted {
        background: #d4edda;
        color: #155724;
        border: 1px solid #00b894;
    }

    .status-badge.rejected {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #e17055;
    }

    /* Details Section */
    .reservation-details {
        padding: 1.5rem;
        border-bottom: 1px solid #f8f9fa;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .detail-item i {
        color: #6c757d;
        font-size: 1.2rem;
    }

    .detail-item label {
        font-size: 0.75rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        margin: 0;
    }

    .detail-item value {
        font-weight: 600;
        color: #2c3e50;
        font-size: 0.9rem;
    }

    /* Products Section */
    .products-section,
    .notes-section {
        padding: 1.5rem;
        border-bottom: 1px solid #f8f9fa;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0 0 1rem 0;
        color: #2c3e50;
        font-size: 1rem;
        font-weight: 600;
    }

    .section-title i {
        color: #6c757d;
    }

    .products-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .product-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 3px solid #007bff;
    }

    .product-info {
        flex: 1;
    }

    .product-name {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.25rem;
    }

    .product-details {
        display: flex;
        gap: 1rem;
        font-size: 0.85rem;
        color: #6c757d;
    }

    .product-price {
        font-weight: 700;
        color: #27ae60;
        font-size: 1.1rem;
    }

    /* Notes Section */
    .notes-text {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        border-left: 3px solid #17a2b8;
        margin: 0;
        color: #495057;
        line-height: 1.5;
    }

    /* Rejection Reason */
    .rejection-reason {
        padding: 1.5rem;
        background: #f8d7da;
        border-bottom: 1px solid #f5c6cb;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .rejection-reason i {
        color: #721c24;
        margin-top: 0.25rem;
    }

    .rejection-reason strong {
        color: #721c24;
    }

    .rejection-reason p {
        margin: 0.5rem 0 0 0;
        color: #721c24;
    }

    /* Footer Section */
    .reservation-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        background: #f8f9fa;
    }

    .created-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: #6c757d;
    }

    /* Action Buttons */
    .reservation-actions {
        display: flex;
        gap: 0.75rem;
    }

    .reservation-actions .btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-size: 0.9rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-transform: none;
    }

    .btn-accept {
        background: #27ae60;
        color: white;
    }

    .btn-accept:hover {
        background: #219a52;
        transform: translateY(-1px);
    }

    .btn-reject {
        background: #e74c3c;
        color: white;
    }

    .btn-reject:hover {
        background: #c0392b;
        transform: translateY(-1px);
    }

    .status-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #6c757d;
        font-style: italic;
    }

    /* Tab Content */
    .tab-content {
        display: none;
        padding: 1rem 0;
    }

    .tab-content.active {
        display: block;
    }

    .tab-content h3 {
        margin: 0 0 1.5rem 0;
        color: #2c3e50;
        font-weight: 600;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #007bff;
        display: inline-block;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .reservation-footer {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .reservation-actions {
            justify-content: center;
        }

        .product-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }
</style>

<section class="reservations">
    <div class="container-xl">
        <div class="row">
            <!-- Navigation -->
            <div class="col-lg-2">
                <div class="navigation">
                    <div class="nav nav-pills">
                        <div class="nav-link active" data-target="#reservationsCardsAll">
                            <i class="material-icons-sharp">all_inclusive</i>
                            <span>All</span>
                        </div>
                        <div class="nav-link" data-target="#reservationsCardsPending">
                            <i class="material-icons-sharp">schedule</i>
                            <span>Pending</span>
                        </div>
                        <div class="nav-link" data-target="#reservationsCardsAccepted">
                            <i class="material-icons-sharp">check_circle</i>
                            <span>Accepted</span>
                        </div>
                        <div class="nav-link" data-target="#reservationsCardsRejected">
                            <i class="material-icons-sharp">cancel</i>
                            <span>Rejected</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="col-lg-10">
                <div class="header-section">
                    <h1>Product Reservations Management</h1>
                    <p>Manage customer product reservations and pickup requests</p>
                </div>

                <!-- All Reservations -->
                <div class="tab-content active" id="reservationsCardsAll">
                    <h3>All Reservations</h3>
                    <div class="reservations-container" id="allReservationsContainer">
                        <!-- Reservations will be loaded here -->
                    </div>
                </div>

                <!-- Pending Reservations -->
                <div class="tab-content" id="reservationsCardsPending">
                    <h3>Pending Reservations</h3>
                    <div class="reservations-container" id="pendingReservationsContainer">
                        <!-- Pending reservations will be loaded here -->
                    </div>
                </div>

                <!-- Accepted Reservations -->
                <div class="tab-content" id="reservationsCardsAccepted">
                    <h3>Accepted Reservations</h3>
                    <div class="reservations-container" id="acceptedReservationsContainer">
                        <!-- Accepted reservations will be loaded here -->
                    </div>
                </div>

                <!-- Rejected Reservations -->
                <div class="tab-content" id="reservationsCardsRejected">
                    <h3>Rejected Reservations</h3>
                    <div class="reservations-container" id="rejectedReservationsContainer">
                        <!-- Rejected reservations will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Rejection Modal -->
<div class="ui small modal rejection-modal" id="rejectionModal">
    <i class="close icon"></i>
    <div class="header">
        <i class="cancel icon"></i> Reject Reservation
    </div>
    <div class="content">
        <form class="ui form" id="rejectionForm">
            <input type="hidden" id="rejectionReservationId">
            <div class="field">
                <label>Reason for Rejection</label>
                <textarea id="rejectionReason" placeholder="Please provide a reason for rejecting this reservation..."
                    required></textarea>
            </div>
        </form>
    </div>
    <div class="actions">
        <button class="ui black deny button" type="button">Cancel</button>
        <button class="ui negative button" id="confirmRejection">
            <i class="cancel icon"></i> Reject Reservation
        </button>
    </div>
</div>
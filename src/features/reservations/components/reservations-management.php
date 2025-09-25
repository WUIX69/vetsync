<style>
    /**
     * Reservations Table Layout (matching appointments)
     */
    .header-section {
        text-align: center;
        padding: 2rem 0;
    }

    .header-section h1 {
        margin: 0 0 0.5rem 0;
        color: #2c3e50;
        font-weight: 600;
    }

    .header-section p {
        margin: 0;
        color: #6c757d;
        font-size: 1.1rem;
    }

    /* Navigation Pills */
    .nav-pills {
        display: flex !important;
        justify-content: center !important;
        background: #f8f9fa;
        border-radius: 50px;
        padding: 0.5rem;
        margin-bottom: 2rem;
        gap: 0.5rem;
    }

    .nav-pills .nav-link {
        border-radius: 25px !important;
        padding: 0.75rem 1.5rem !important;
        font-weight: 500 !important;
        transition: all 0.3s ease !important;
        border: none !important;
        background: transparent !important;
        color: #6c757d !important;
    }

    .nav-pills .nav-link.active {
        background: #007bff !important;
        color: white !important;
        box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3) !important;
    }

    .nav-pills .nav-link:hover {
        background: #e9ecef !important;
        color: #495057 !important;
    }

    .nav-pills .nav-link.active:hover {
        background: #0056b3 !important;
        color: white !important;
    }

    /* Tab Content */
    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    /* Search Input */
    .search-container {
        margin-bottom: 1rem;
    }

    .search-input {
        width: 100%;
        max-width: 400px;
        padding: 0.75rem 1rem;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
    }

    /* Table Styles */
    .reservations-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .reservations-table th {
        background: #f8f9fa;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid #dee2e6;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .reservations-table td {
        padding: 1rem;
        border-bottom: 1px solid #dee2e6;
        vertical-align: top;
        font-size: 0.95rem;
    }

    .reservations-table tr:hover {
        background: #f8f9fa;
    }

    /* Column widths */
    .reservations-table th:nth-child(1) {
        width: 12%;
    }

    /* Date */
    .reservations-table th:nth-child(2) {
        width: 18%;
    }

    /* Customer */
    .reservations-table th:nth-child(3) {
        width: 25%;
    }

    /* Products */
    .reservations-table th:nth-child(4) {
        width: 12%;
    }

    /* Amount */
    .reservations-table th:nth-child(5) {
        width: 10%;
    }

    /* Status */
    .reservations-table th:nth-child(6) {
        width: 23%;
    }

    /* Actions */

    /* Status Badges */
    .status-badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 15px;
        font-size: 0.8rem;
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

    .status-badge.completed {
        background: #d1ecf1;
        color: #0c5460;
        border: 1px solid #74c0fc;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        justify-content: center;
    }

    .action-buttons .btn {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.3s ease;
        text-transform: none;
        white-space: nowrap;
    }

    .btn-accept {
        background: #28a745;
        color: white;
    }

    .btn-accept:hover {
        background: #218838;
    }

    .btn-reject {
        background: #dc3545;
        color: white;
    }

    .btn-reject:hover {
        background: #c82333;
    }

    .btn-complete {
        background: #17a2b8;
        color: white;
    }

    .btn-complete:hover {
        background: #138496;
    }

    /* Product Items */
    .product-list {
        max-height: 120px;
        overflow-y: auto;
    }

    .product-item {
        padding: 0.25rem 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 0.85rem;
    }

    .product-item:last-child {
        border-bottom: none;
    }

    .product-name {
        font-weight: 600;
        color: #2c3e50;
    }

    .product-details {
        color: #6c757d;
        margin-top: 0.25rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state h3 {
        color: #495057;
        margin-bottom: 0.5rem;
    }

    /* Customer Info */
    .customer-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .customer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 0.9rem;
    }

    .customer-details {
        flex: 1;
    }

    .customer-name {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.25rem;
    }

    .customer-email {
        color: #6c757d;
        font-size: 0.85rem;
    }

    /* Amount Display */
    .amount {
        font-weight: 700;
        color: #28a745;
        font-size: 1.1rem;
    }

    /* Status Info */
    .status-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #6c757d;
        font-style: italic;
        font-size: 0.85rem;
    }

    .ready-status {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #155724;
        font-weight: 600;
        font-size: 0.85rem;
    }

    /* Responsive */
    @media (max-width: 768px) {

        .reservations-table th,
        .reservations-table td {
            padding: 0.75rem;
            font-size: 0.85rem;
        }

        .customer-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .action-buttons {
            flex-direction: column;
            gap: 0.25rem;
        }

        .action-buttons .btn {
            width: 100%;
            font-size: 0.8rem;
        }
    }
</style>

<section class="reservations">
    <div class="container-xl">
        <!-- Header -->
        <div class="header-section">
            <h1>Product Reservations Management</h1>
            <p>Manage customer product reservations and pickup requests</p>
        </div>

        <!-- Navigation Pills -->
        <div class="d-flex justify-content-center mb-4">
            <ul class="nav nav-pills" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-target="all">
                        <i class="material-icons-sharp">all_inclusive</i>
                        All Reservations
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-target="pending">
                        <i class="material-icons-sharp">schedule</i>
                        Pending
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-target="accepted">
                        <i class="material-icons-sharp">check_circle</i>
                        Accepted
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-target="rejected">
                        <i class="material-icons-sharp">cancel</i>
                        Rejected
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-target="completed">
                        <i class="material-icons-sharp">done_all</i>
                        Completed
                    </a>
                </li>
            </ul>
        </div>

        <!-- All Reservations Tab -->
        <div class="tab-content active" id="all-tab">
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Search reservations..." id="search-all">
            </div>
            <table class="reservations-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Products</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="all-reservations-table">
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
        </div>

        <!-- Pending Reservations Tab -->
        <div class="tab-content" id="pending-tab">
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Search pending reservations..."
                    id="search-pending">
            </div>
            <table class="reservations-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Products</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="pending-reservations-table">
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
        </div>

        <!-- Accepted Reservations Tab -->
        <div class="tab-content" id="accepted-tab">
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Search accepted reservations..."
                    id="search-accepted">
            </div>
            <table class="reservations-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Products</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="accepted-reservations-table">
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
        </div>

        <!-- Rejected Reservations Tab -->
        <div class="tab-content" id="rejected-tab">
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Search rejected reservations..."
                    id="search-rejected">
            </div>
            <table class="reservations-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Products</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="rejected-reservations-table">
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
        </div>

        <!-- Completed Reservations Tab -->
        <div class="tab-content" id="completed-tab">
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Search completed reservations..."
                    id="search-completed">
            </div>
            <table class="reservations-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Products</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="completed-reservations-table">
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
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
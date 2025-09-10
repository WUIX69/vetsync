<style>
    /*----------- MAIN (Cart) -----------*/
    main section.cart {
        background: linear-gradient(135deg, #f8fffe 0%, #e8f5f3 100%);
        padding: 2rem 0;
        min-height: 70vh;
    }

    /* Cart Container - Fix Bootstrap Grid Issues */
    main section.cart .container-xl {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    main section.cart .row {
        margin: 0;
        display: flex;
        gap: 1.5rem;
    }

    main section.cart .col-lg-8 {
        flex: 0 0 calc(66.666667% - 1rem);
        max-width: calc(66.666667% - 1rem);
        padding: 0;
    }

    main section.cart .col-lg-4 {
        flex: 0 0 calc(33.333333% - 1rem);
        max-width: calc(33.333333% - 1rem);
        padding: 0;
    }

    /*----------- CART TABS -----------*/
    main section.cart .cart-tabs {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        background: var(--color-white);
        border-radius: 16px;
        padding: 1rem;
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.3);
        width: 100%;
    }

    main section.cart .cart-tab {
        flex: 1;
        padding: 1rem 1.5rem;
        background: transparent;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 600;
        color: #6c757d;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 0.95rem;
        min-height: 50px;
        white-space: nowrap;
    }

    main section.cart .cart-tab:hover {
        background: linear-gradient(135deg, #f8fffe, #e8f5f3);
        color: #495057;
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
    }

    main section.cart .cart-tab.active {
        background: linear-gradient(135deg, #21ba45 0%, #16ab39 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(33, 186, 69, 0.3);
    }

    main section.cart .cart-tab i {
        font-size: 1.2rem;
    }

    main section.cart .cart-tab .count-badge {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        padding: 0.2rem 0.6rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 700;
        margin-left: 0.5rem;
        min-width: 20px;
        text-align: center;
    }

    main section.cart .cart-tab:not(.active) .count-badge {
        background: #e9ecef;
        color: #6c757d;
    }

    /*----------- TAB CONTENT -----------*/
    main section.cart .tab-content {
        display: none;
        background: var(--color-white);
        border-radius: 16px;
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.08);
        min-height: 400px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.3);
        width: 100%;
        padding: 1.5rem;
    }

    main section.cart .tab-content.active {
        display: block;
        animation: fadeInUp 0.4s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(15px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /*----------- CART ITEMS (Active Cart) -----------*/
    main section.cart .cart-item {
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, #ffffff, #f8fffe);
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid #f0f8f5;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
    }

    main section.cart .cart-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    main section.cart .cart-item:last-child {
        margin-bottom: 0;
    }

    /* Fix Bootstrap Row in Cart Items */
    main section.cart .cart-item .row {
        margin: 0;
        gap: 0;
        align-items: center;
    }

    main section.cart .cart-item .col-md-2,
    main section.cart .cart-item .col-md-4,
    main section.cart .cart-item .col-md-3,
    main section.cart .cart-item .col-md-1 {
        padding: 0.5rem;
    }

    /* Image Column */
    main section.cart .cart-item .col-md-2 {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    main section.cart .cart-item-image {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 12px;
        background: linear-gradient(135deg, #f8fffe, #e8f5f3);
        border: 2px solid white;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
    }

    /* Details Column */
    main section.cart .cart-item-details {
        padding: 0;
    }

    main section.cart .cart-item-details h5 {
        margin: 0 0 0.5rem 0;
        font-weight: 700;
        color: var(--color-dark);
        font-size: 1.1rem;
        line-height: 1.3;
    }

    main section.cart .cart-item-price {
        font-weight: 800;
        color: #21ba45;
        font-size: 1.1rem;
        margin: 0.5rem 0 0 0;
    }

    /* Quantity Controls Column */
    main section.cart .quantity-controls {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: #f8fffe;
        border-radius: 10px;
        padding: 0.5rem;
        width: fit-content;
        margin: 0 auto;
    }

    main section.cart .quantity-controls button {
        width: 32px;
        height: 32px;
        border: none;
        background: white;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        font-weight: 600;
        color: #495057;
    }

    main section.cart .quantity-controls button:hover {
        background: #21ba45;
        color: white;
        transform: scale(1.05);
    }

    main section.cart .quantity-controls .quantity-display {
        font-weight: 700;
        color: var(--color-dark);
        font-size: 1rem;
        min-width: 24px;
        text-align: center;
        margin: 0 0.5rem;
    }

    /* Price Column */
    main section.cart .cart-item .col-md-2:last-of-type {
        text-align: center;
    }

    main section.cart .cart-item .col-md-2:last-of-type strong {
        font-size: 1.2rem;
        color: #21ba45;
        font-weight: 800;
    }

    /* Remove Button Column */
    main section.cart .cart-item .col-md-1 {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    main section.cart .remove-item {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        color: white;
        border: none;
        padding: 0.6rem;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        font-size: 0.9rem;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    main section.cart .remove-item:hover {
        transform: scale(1.05);
        box-shadow: 0 3px 12px rgba(231, 76, 60, 0.3);
    }

    /*----------- BADGES -----------*/
    main section.cart .size-badge {
        background: linear-gradient(135deg, #21ba45, #16ab39);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
        display: inline-block;
    }

    /*----------- SUMMARY SIDEBAR -----------*/
    main section.cart .cart-summary {
        background: var(--color-white);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.08);
        position: sticky;
        top: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
        width: 100%;
        box-sizing: border-box;
    }

    main section.cart .cart-summary h4 {
        margin-bottom: 1.5rem;
        color: var(--color-dark);
        font-weight: 700;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    main section.cart .cart-summary h4 i {
        color: #21ba45;
        font-size: 1.3rem;
    }

    main section.cart .reserve-btn {
        background: linear-gradient(135deg, #21ba45 0%, #16ab39 100%);
        color: white;
        width: 100%;
        padding: 1.2rem;
        font-size: 1rem;
        border: none;
        border-radius: 12px;
        margin-top: 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 6px 18px rgba(33, 186, 69, 0.3);
    }

    main section.cart .reserve-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(33, 186, 69, 0.4);
    }

    main section.cart .reserve-btn:disabled {
        background: linear-gradient(135deg, #bdc3c7, #95a5a6);
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /*----------- SUMMARY CONTENT -----------*/
    main section.cart .summary-content .summary-row {
        padding: 0.75rem 0;
        border-bottom: 1px solid #f8fffe;
        color: var(--color-dark);
        font-size: 0.95rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    main section.cart .summary-content .summary-row.total {
        border-top: 2px solid #21ba45;
        font-weight: 700;
        font-size: 1.1rem;
        color: #21ba45;
        margin-top: 0.75rem;
        padding-top: 1rem;
    }

    /*----------- RESERVATION ITEMS -----------*/
    main section.cart .reservation-item {
        padding: 2.5rem;
        margin-bottom: 1.5rem;
        border-left: 6px solid #21ba45;
        background: linear-gradient(135deg, #ffffff, #f8fffe);
        border-radius: 16px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid #f0f8f5;
    }

    main section.cart .reservation-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
    }

    /* Ready for Pickup Item Styling */
    main section.cart .reservation-item.ready-for-pickup-item {
        border-left: 6px solid #3498db;
        background: linear-gradient(135deg, #e3f2fd, #ffffff);
        box-shadow: 0 6px 20px rgba(52, 152, 219, 0.15);
    }

    main section.cart .reservation-item.ready-for-pickup-item:hover {
        box-shadow: 0 10px 25px rgba(52, 152, 219, 0.2);
    }

    main section.cart .reservation-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    main section.cart .reservation-id {
        font-weight: 800;
        color: var(--color-dark);
        font-size: 1.3rem;
        margin-bottom: 0.5rem;
    }

    main section.cart .reservation-date {
        color: #6c757d;
        font-size: 0.95rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    main section.cart .reservation-date i {
        color: #21ba45;
        font-size: 1rem;
    }

    main section.cart .status-badge {
        padding: 0.6rem 1.2rem;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    main section.cart .status-badge.pending {
        background: linear-gradient(135deg, #f39c12, #e67e22);
        color: white;
        border-color: rgba(243, 156, 18, 0.3);
    }

    main section.cart .status-badge.accepted {
        background: linear-gradient(135deg, #21ba45, #16ab39);
        color: white;
        border-color: rgba(33, 186, 69, 0.3);
    }

    main section.cart .status-badge.rejected {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        color: white;
        border-color: rgba(231, 76, 60, 0.3);
    }

    main section.cart .status-badge.ready-for-pickup {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        border-color: rgba(52, 152, 219, 0.3);
    }

    main section.cart .reservation-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    main section.cart .detail-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        background: rgba(255, 255, 255, 0.7);
        padding: 1.2rem;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    main section.cart .detail-item:hover {
        background: white;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
    }

    main section.cart .detail-item i {
        color: #21ba45;
        font-size: 1.3rem;
        width: 28px;
        text-align: center;
    }

    /*----------- RESERVATION PRODUCTS -----------*/
    main section.cart .reservation-products {
        background: linear-gradient(135deg, #f8fffe, #ffffff);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e8f5f3;
    }

    main section.cart .reservation-products h6 {
        margin-bottom: 1.5rem;
        color: var(--color-dark);
        font-weight: 700;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    main section.cart .reservation-products h6 i {
        color: #21ba45;
        font-size: 1.2rem;
    }

    main section.cart .product-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    main section.cart .product-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        background: white;
        border-radius: 12px;
        font-size: 1rem;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
        border-left: 4px solid #21ba45;
        transition: all 0.3s ease;
    }

    main section.cart .product-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    }

    main section.cart .product-name {
        font-weight: 700;
        color: var(--color-dark);
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    main section.cart .product-meta {
        color: #6c757d;
        font-size: 0.9rem;
        font-weight: 500;
    }

    main section.cart .product-price {
        font-weight: 800;
        color: #21ba45;
        font-size: 1.2rem;
    }

    /*----------- EMPTY STATES -----------*/
    main section.cart .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #7f8c8d;
    }

    main section.cart .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        opacity: 0.3;
        background: linear-gradient(135deg, #21ba45, #16ab39);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    main section.cart .empty-state h3 {
        margin-bottom: 0.75rem;
        color: var(--color-dark);
        font-weight: 600;
        font-size: 1.2rem;
    }

    main section.cart .empty-state p {
        font-size: 0.9rem;
        line-height: 1.5;
    }

    /* Enhanced Summary Styles */
    .selected-items,
    .order-items {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #f0f8f5;
    }

    .selected-items h6,
    .order-items h6 {
        margin: 0 0 1rem 0;
        color: #2c3e50;
        font-weight: 600;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .selected-items h6 i,
    .order-items h6 i {
        color: #21ba45;
        font-size: 1rem;
    }

    .items-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        max-height: 300px;
        overflow-y: auto;
    }

    .summary-item {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 0.75rem;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .summary-item:hover {
        background: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .summary-item .item-name {
        font-weight: 600;
        color: #2c3e50;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .summary-item .item-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }

    .summary-item .item-details span:last-child {
        color: #21ba45;
        font-weight: 600;
    }

    .summary-item .item-status {
        display: flex;
        justify-content: flex-end;
    }

    .summary-item .status-badge {
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .summary-item .status-badge.pending {
        background: #fff3cd;
        color: #856404;
    }

    .summary-item .status-badge.accepted {
        background: #d4edda;
        color: #155724;
    }

    .summary-item .status-badge.rejected {
        background: #f8d7da;
        color: #721c24;
    }

    .summary-item .status-badge.ready-for-pickup {
        background: #d1ecf1;
        color: #0c5460;
    }

    /*----------- RESPONSIVE DESIGN -----------*/
    @media screen and (max-width: 1200px) {
        main section.cart .container-xl {
            max-width: 100%;
            padding: 0 1rem;
        }
    }

    @media screen and (max-width: 992px) {
        main section.cart .row {
            flex-direction: column;
            gap: 1.5rem;
        }

        main section.cart .col-lg-8,
        main section.cart .col-lg-4 {
            flex: none;
            max-width: 100%;
            width: 100%;
        }

        main section.cart .cart-summary {
            position: relative;
            top: auto;
        }

        /* Stack cart item columns vertically on mobile */
        main section.cart .cart-item .row {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        main section.cart .cart-item .col-md-2,
        main section.cart .cart-item .col-md-4,
        main section.cart .cart-item .col-md-3,
        main section.cart .cart-item .col-md-1 {
            flex: none;
            max-width: 100%;
            width: 100%;
        }
    }

    @media screen and (max-width: 768px) {
        main section.cart {
            padding: 1.5rem 0;
        }

        main section.cart .container-xl {
            padding: 0 1rem;
        }

        main section.cart .cart-tabs {
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            padding: 0.75rem;
        }

        main section.cart .cart-tab {
            text-align: center;
            padding: 1rem;
            min-height: auto;
        }

        main section.cart .cart-item {
            padding: 1.25rem;
        }

        main section.cart .cart-item-image {
            width: 60px;
            height: 60px;
        }

        main section.cart .cart-summary {
            padding: 1.5rem;
        }
    }

    @media screen and (max-width: 480px) {
        main section.cart .cart-tab {
            flex-direction: column;
            gap: 0.3rem;
        }

        main section.cart .cart-tab span {
            font-size: 0.85rem;
        }

        main section.cart .cart-item {
            padding: 1rem;
        }

        main section.cart .cart-item-image {
            width: 50px;
            height: 50px;
        }
    }

    /*----------- ANIMATIONS -----------*/
    main section.cart .reservation-item {
        animation: slideInUp 0.6s ease-out;
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /*----------- LOADING STATES -----------*/
    main section.cart .loading {
        opacity: 0.6;
        pointer-events: none;
    }

    main section.cart .loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 32px;
        height: 32px;
        margin: -16px 0 0 -16px;
        border: 3px solid #e9ecef;
        border-top: 3px solid #21ba45;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /*----------- NOTIFICATION STYLES -----------*/
    main section.cart .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        z-index: 1000;
        animation: slideInRight 0.4s ease-out;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    main section.cart .notification.success {
        background: linear-gradient(135deg, #21ba45, #16ab39);
        color: white;
    }

    main section.cart .notification.error {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        color: white;
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
</style>

<section class="cart">
    <div class="container-xl">
        <!-- Tab Navigation -->
        <div class="cart-tabs">
            <button class="cart-tab active" data-tab="active">
                <i class='bx bx-shopping-bag'></i>
                <span>Active Cart</span>
                <span class="count-badge" id="activeCount">0</span>
            </button>
            <button class="cart-tab" data-tab="pending">
                <i class='bx bx-time'></i>
                <span>Pending</span>
                <span class="count-badge" id="pendingCount">0</span>
            </button>
            <button class="cart-tab" data-tab="accepted">
                <i class='bx bx-check-circle'></i>
                <span>Accepted</span>
                <span class="count-badge" id="acceptedCount">0</span>
            </button>
            <button class="cart-tab" data-tab="ready">
                <i class='bx bx-package'></i>
                <span>Ready for Pickup</span>
                <span class="count-badge" id="readyCount">0</span>
            </button>
            <button class="cart-tab" data-tab="rejected">
                <i class='bx bx-x-circle'></i>
                <span>Rejected</span>
                <span class="count-badge" id="rejectedCount">0</span>
            </button>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Active Cart Tab -->
                <div class="tab-content active" id="activeTab">
                    <div id="cartItems"></div>
                </div>

                <!-- Pending Reservations Tab -->
                <div class="tab-content" id="pendingTab">
                    <div id="pendingReservations"></div>
                </div>

                <!-- Accepted Reservations Tab -->
                <div class="tab-content" id="acceptedTab">
                    <div id="acceptedReservations"></div>
                </div>

                <!-- Ready for Pickup Tab -->
                <div class="tab-content" id="readyTab">
                    <div id="readyReservations"></div>
                </div>

                <!-- Rejected Reservations Tab -->
                <div class="tab-content" id="rejectedTab">
                    <div id="rejectedReservations"></div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="cart-summary" id="cartSummary">
                    <h4><i class='bx bx-receipt'></i> <span id="summaryTitle">Order Summary</span></h4>
                    <div class="summary-content"></div>
                </div>
            </div>
        </div>
    </div>
</section>
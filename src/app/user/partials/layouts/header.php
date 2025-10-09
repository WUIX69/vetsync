<?php
global $activeLink;
$activeLink = uriPagePath();
?>

<style>
    /* Simple Cart Icon Styles */
    .cart-icon {
        position: relative;
        display: inline-block;
        margin-right: 20px;
    }

    .cart-link {
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: inherit;
        padding: 10px;
        border-radius: 6px;
        transition: all 0.3s ease;
        position: relative;
    }

    .cart-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: inherit;
        text-decoration: none;
    }

    .cart-icon i {
        font-size: 22px;
        color: white;
        /* Changed from #333 to white */
        transition: color 0.3s ease;
    }

    .cart-link:hover i {
        color: #007bff;
    }

    /* Cart Badge */
    .cart-badge {
        position: absolute;
        top: 2px;
        right: 2px;
        background-color: #e74c3c;
        color: white;
        font-size: 11px;
        font-weight: bold;
        padding: 2px 5px;
        border-radius: 50%;
        min-width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
        border: 2px solid white;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    }

    /* Active state when on cart page */
    .nav-link.active~.right-section .cart-link,
    .cart-page .cart-link {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .cart-page .cart-icon i {
        color: #007bff;
    }

    /* Notification Bell Icon Styles */
    .notification-bell {
        position: relative;
        display: inline-block;
        margin-right: 20px;
        cursor: pointer;
    }

    .notification-bell i {
        font-size: 22px;
        color: white;
        /* Changed from #333 to white */
        transition: color 0.3s ease;
        padding: 10px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .notification-bell:hover i {
        background-color: rgba(255, 255, 255, 0.1);
        color: #007bff;
    }

    .notification-badge {
        position: absolute;
        top: 2px;
        right: 2px;
        background-color: #e74c3c;
        color: white;
        font-size: 11px;
        font-weight: bold;
        padding: 2px 5px;
        border-radius: 50%;
        min-width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
        border: 2px solid white;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    }

    /* Notification Dropdown Menu */
    .notification-menu {
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        width: 400px;
        z-index: 1000;
        display: none;
        max-height: 500px;
        overflow-y: auto;
    }

    .notification-menu-header {
        padding: 18px 24px;
        border-bottom: 2px solid #e8e8e8;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #fafbfc;
    }

    .notification-menu-header span {
        font-weight: 700;
        font-size: 16px;
        color: #1a1a1a;
    }

    .notification-header-actions {
        display: flex;
        gap: 8px;
    }

    .filter-unread-btn {
        font-size: 12px !important;
        padding: 6px 12px !important;
        transition: all 0.2s ease !important;
    }

    .filter-unread-btn.active {
        background-color: #2185d0 !important;
        color: white !important;
    }

    .mark-all-read {
        font-size: 12px !important;
        padding: 6px 12px !important;
        color: #666 !important;
        transition: all 0.2s ease !important;
    }

    .mark-all-read:hover {
        background-color: #f0f0f0 !important;
    }

    .notifications-list {
        max-height: 380px;
        overflow-y: auto;
    }

    .notification-item {
        padding: 14px 18px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        transition: all 0.2s ease;
        position: relative;
    }

    .notification-item:hover {
        background-color: #f8f9fa;
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .notification-item.empty {
        text-align: center;
        color: #999;
        font-style: italic;
        padding: 40px 24px;
    }

    .notification-item-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
    }

    .notification-item-icon i {
        font-size: 18px;
        margin: 0 !important;
    }

    .notification-item-icon.green {
        background: linear-gradient(135deg, #21ba45 0%, #16ab39 100%);
        color: white;
    }

    .notification-item-icon.red {
        background: linear-gradient(135deg, #db2828 0%, #ca1010 100%);
        color: white;
    }

    .notification-item-icon.blue {
        background: linear-gradient(135deg, #2185d0 0%, #1678c2 100%);
        color: white;
    }

    .notification-item-icon.orange {
        background: linear-gradient(135deg, #f2711c 0%, #e8590c 100%);
        color: white;
    }

    .notification-item-icon.yellow {
        background: linear-gradient(135deg, #fbbd08 0%, #eaae00 100%);
        color: white;
    }

    .notification-item-icon.teal {
        background: linear-gradient(135deg, #00b5ad 0%, #009c95 100%);
        color: white;
    }

    .notification-content {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
    }

    .notification-item-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 6px;
        gap: 10px;
    }

    .notification-item-header strong {
        font-size: 14px;
        color: #1a1a1a;
        font-weight: 600;
        line-height: 1.3;
    }

    .notification-time {
        font-size: 11px;
        color: #999;
        white-space: nowrap;
        font-weight: 500;
    }

    .notification-message {
        font-size: 13px;
        color: #555;
        margin: 0 0 10px 0;
        line-height: 1.5;
        word-wrap: break-word;
    }

    .notification-message strong,
    .notification-message b {
        color: #333;
        font-weight: 600;
    }

    .notification-actions {
        display: flex;
        gap: 6px;
        margin-top: auto;
    }

    .notification-actions .ui.button {
        margin: 0 !important;
        padding: 6px 12px !important;
        font-size: 12px !important;
        border-radius: 6px !important;
        font-weight: 500 !important;
        transition: all 0.2s ease !important;
    }

    .notification-actions .ui.button i {
        margin: 0 4px 0 0 !important;
        font-size: 13px;
    }

    .notification-actions .ui.primary.button {
        background: linear-gradient(135deg, #2185d0 0%, #1678c2 100%) !important;
        box-shadow: 0 2px 4px rgba(33, 133, 208, 0.2) !important;
    }

    .notification-actions .ui.primary.button:hover {
        background: linear-gradient(135deg, #1678c2 0%, #1a69a4 100%) !important;
        box-shadow: 0 3px 6px rgba(33, 133, 208, 0.3) !important;
        transform: translateY(-1px);
    }

    .notification-actions .ui.basic.button {
        background: white !important;
        border: 1px solid #ddd !important;
        color: #666 !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
    }

    .notification-actions .ui.basic.button:hover {
        background: #f8f9fa !important;
        border-color: #999 !important;
        color: #333 !important;
        transform: translateY(-1px);
    }

    .notification-footer {
        padding: 14px 20px;
        border-top: 1px solid #e8e8e8;
        text-align: center;
        background-color: #fafbfc;
    }

    .notification-footer a {
        color: #2185d0;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: color 0.2s ease;
    }

    .notification-footer a:hover {
        color: #1678c2;
        text-decoration: none;
    }

    /* Unread notification styling */
    .notification-item.unread {
        background-color: #f0f9ff;
        border-left: 4px solid #2185d0;
    }

    .notification-item.unread .notification-item-header strong {
        color: #0c5460;
        font-weight: 700;
    }

    .notification-item.unread::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 80%;
        background: linear-gradient(180deg, #2185d0 0%, #1678c2 100%);
        border-radius: 0 4px 4px 0;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .notification-item {
            padding: 14px 16px;
        }

        .notification-item-icon {
            width: 34px;
            height: 34px;
        }

        .notification-item-icon i {
            font-size: 16px;
        }

        .notification-item-header strong {
            font-size: 13.5px;
        }

        .notification-message {
            font-size: 12.5px;
        }

        .notification-actions .view-btn,
        .notification-actions .delete-notification {
            padding: 5px 10px !important;
            font-size: 11px !important;
        }
    }

    /* Terms Icon Styles */
    .terms-icon {
        position: relative;
        display: inline-block;
        margin-right: 20px;
    }

    .terms-link {
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: inherit;
        padding: 10px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .terms-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: inherit;
        text-decoration: none;
    }

    .terms-icon i {
        font-size: 22px;
        color: #333;
        transition: color 0.3s ease;
    }

    .terms-link:hover i {
        color: #007bff;
    }
</style>

<!-- Site Header -->
<header class="site-header">
    <div class="nav">
        <div class="logo">
            <img src="/public/img/logo.jpg" alt="J.A.A Logo"
                style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover; margin-right: 10px;">
            <a href="<?= app('user') ?>">VetSync</a>
        </div>
        <div class="nav-links">
            <a class="nav-link <?= ($activeLink === 'index' || $activeLink === '') ? 'active' : '' ?>"
                href="<?= app('user') ?>">Home</a>
            <a class="nav-link <?= $activeLink === 'services' ? 'active' : '' ?>"
                href="<?= app('user/services') ?>">Services</a>
            <a class="nav-link <?= $activeLink === 'products' ? 'active' : '' ?>"
                href="<?= app('user/products') ?>">Products</a>
            <a class="nav-link <?= $activeLink === 'appointments' ? 'active' : '' ?>"
                href="<?= app('user/appointments') ?>">Appointments</a>
            <a class="nav-link <?= $activeLink === 'settings' ? 'active' : '' ?>"
                href="<?= app('user/settings') ?>">Settings</a>
        </div>
        <div class="right-section">
            <!-- Enhanced Notification Dropdown -->
            <div class="notification-bell" id="notificationDropdown">
                <i class='bx bx-bell'></i>
                <span class="notification-badge" style="display: none;">0</span>
                <div class="notification-menu">
                    <div class="notification-menu-header">
                        <span>Notifications</span>
                        <div class="notification-header-actions">
                            <button class="ui mini basic button filter-unread-btn" title="Show unread only">
                                <i class='bx bx-filter'></i> Unread
                            </button>
                            <button class="ui mini basic button mark-all-read">Clear all</button>
                        </div>
                    </div>
                    <div class="notifications-list">
                        <!-- Notifications will be loaded here -->
                    </div>
                    <div class="notification-footer">
                        <a href="<?= app('user/notifications') ?>">View all notifications</a>
                    </div>
                </div>
            </div>

            <!-- Terms and Agreement Icon - REMOVED (Available in Settings) -->
            <!-- 
            <div class="terms-icon">
                <a href="javascript:void(0)" onclick="showTermsModal()" class="terms-link" title="Terms & Agreement">
                    <i class="file text outline icon"></i>
                </a>
            </div>
            -->

            <!-- Simple Cart Icon - Only shows cart items now -->
            <div class="cart-icon">
                <a href="<?= app('user/cart') ?>" class="cart-link">
                    <i class='bx bx-cart'></i>
                    <span class="cart-badge" id="cartBadge" style="display: none">0</span>
                </a>
            </div>

            <div class="profile">
                <div class="ui floating compact selection dropdown profile-menu-dropdown">
                    <div class="text">
                        <div class="info">
                            <img class="user-profile-photo" src="<?= userData()['profile'] ?>">
                            <div class="d-flex flex-column">
                                <a href="javascript:void(0)" class="text-capitalize"><?= userData()['name'] ?></a>
                                <p><?= userData()['email'] ?></p>
                            </div>
                            <i class='bx bx-chevron-down'></i>
                        </div>
                    </div>
                    <div class="menu inverted">
                        <a class="item" href="<?= app('user') ?>">Dashboard</a>
                        <a class="item" href="<?= app('user/settings') ?>">Settings</a>
                        <a class="item" href="<?= app('landing') ?>">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
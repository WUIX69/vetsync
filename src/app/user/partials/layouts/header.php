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
        color: #333;
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
        color: #333;
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
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .notification-menu-header span {
        font-weight: 600;
        color: #333;
        font-size: 16px;
    }

    .notifications-list {
        max-height: 380px;
        overflow-y: auto;
    }

    .notification-item {
        padding: 18px 24px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: flex-start;
        gap: 15px;
        transition: background-color 0.2s ease;
    }

    .notification-item:hover {
        background-color: #f8f9fa;
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .notification-item.empty {
        text-align: center;
        color: #666;
        font-style: italic;
        padding: 30px 24px;
    }

    .notification-item-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .notification-item-icon i {
        font-size: 20px;
    }

    .notification-item-icon.green {
        background-color: #d4edda;
        color: #155724;
    }

    .notification-item-icon.red {
        background-color: #f8d7da;
        color: #721c24;
    }

    .notification-content {
        flex: 1;
        min-width: 0;
    }

    .notification-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
    }

    .notification-item-header strong {
        font-size: 15px;
        color: #333;
        font-weight: 600;
    }

    .notification-time {
        font-size: 12px;
        color: #666;
        white-space: nowrap;
    }

    .notification-message {
        font-size: 14px;
        color: #666;
        margin: 0;
        line-height: 1.5;
        margin-bottom: 8px;
    }

    .rejection-reason {
        font-size: 13px;
        color: #dc3545;
        margin: 6px 0 0 0;
        font-style: italic;
        line-height: 1.4;
    }

    .notification-actions {
        flex-shrink: 0;
        margin-top: 4px;
    }

    .notification-footer {
        padding: 15px 24px;
        border-top: 1px solid #eee;
        text-align: center;
    }

    .notification-footer a {
        color: #007bff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
    }

    .notification-footer a:hover {
        text-decoration: underline;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {

        .cart-icon,
        .notification-bell {
            margin-right: 15px;
        }

        .cart-link,
        .notification-bell i {
            padding: 8px;
        }

        .cart-icon i,
        .notification-bell i {
            font-size: 20px;
        }

        .cart-badge,
        .notification-badge {
            top: 0;
            right: 0;
            font-size: 10px;
            min-width: 14px;
            height: 14px;
        }

        .notification-menu {
            width: 320px;
            right: -20px;
        }

        .notification-item {
            padding: 15px 18px;
        }

        .notification-menu-header {
            padding: 15px 18px;
        }

        .notification-footer {
            padding: 12px 18px;
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
            <i class='bx bxl-codepen'></i>
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
                <span class="notification-badge" style="display: none">0</span>
                <div class="notification-menu">
                    <div class="notification-menu-header">
                        <span>Notifications</span>
                        <button class="ui mini basic button mark-all-read">Clear all</button>
                    </div>
                    <div class="notifications-list">
                        <!-- Notifications will be loaded here -->
                    </div>
                    <div class="notification-footer">
                        <a href="<?= app('user/appointments') ?>">View all appointments</a>
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
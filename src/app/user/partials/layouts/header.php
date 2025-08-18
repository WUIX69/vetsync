<?php
global $activeLink;
$activeLink = uriPagePath();
?>
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
            <div class="notification-icon" id="notificationDropdown">
                <i class='bx bx-bell'></i>
                <span class="notification-badge" style="display: none">0</span>
                <div class="notification-menu">
                    <div class="notification-header">
                        <span>Notifications</span>
                        <button class="ui mini basic button mark-all-read">Clear all</button>
                    </div>
                    <div class="notifications-list">
                        <!-- Notifications will be loaded here -->
                    </div>
                    <div class="notification-footer">
                        <a href="<?= app('appointments') ?>">View all appointments</a>
                    </div>
                </div>
            </div>
            <div class="profile">
                <div class="ui floating compact selection dropdown profile-menu-dropdown">
                    <!-- <i class="dropdown icon"></i> -->
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
<?php
// TODO: move all the global logic in /config dir
$urlPath = $_SERVER['REQUEST_URI'] ?? null;

global $urlParts;
$urlParts = explode('/', trim($urlPath, '/')) ?? '';
$pageName = $urlParts[3] ?? '';

global $activeLink;
$activeLink = str_replace('.php', '', $pageName);
?>

<header class="site-header">
    <nav class="navbar">
        <div class="navbar-container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="logo-cont">
                        <a href="<?= app('landing') ?>" class="navbar-logo">
                            <!-- <img src="../../../public/assets/img/logo.png" alt="Logo"> -->
                            <div class="logo-text">
                                <h3 class="logo-text-main">VET<span class="ui teal text">SYNC</span></h3>
                            </div>
                        </a>
                        <button class="mobile-menu-btn"><!-- For mobile view support (Hamburger) -->
                            <span class="material-icons-sharp">menu</span>
                        </button>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="nav-links">
                        <a href="<?= app('landing') ?>"
                            class="nav-link <?= ($activeLink === 'index' || $activeLink === '') ? 'active' : '' ?>">Home</a>
                        <a href="<?= app('landing/services/') ?>"
                            class="nav-link <?= $activeLink === 'services' ? 'active' : '' ?>">Services</a>
                        <a href="<?= app('landing/products/') ?>"
                            class="nav-link <?= $activeLink === 'products' ? 'active' : '' ?>">Products</a>
                        <a href="<?= app('landing/contact') ?>"
                            class="nav-link <?= $activeLink === 'contact' ? 'active' : '' ?>">Contact
                            Us</a>
                        <a href="<?= app('landing/about') ?>"
                            class="nav-link <?= $activeLink === 'about' ? 'active' : '' ?>">About
                            Us</a>
                        <div class="actions d-none"><!-- Only show for mobile view -->
                            <?= featured('landing/shared/components/ui/login-btn') ?>
                            <?= featured('landing/shared/components/ui/booknow-btn') ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="actions">
                        <?= featured('landing/shared/components/ui/login-btn') ?>
                        <?= featured('landing/shared/components/ui/booknow-btn') ?>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
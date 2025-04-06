<?php
$urlPath = $_SERVER['REQUEST_URI'] ?? null;
$pageName = explode('/', trim($urlPath, '/'))[3] ?? '';
$activeLink = str_replace('.php', '', $pageName);
?>

<header class="site-header">
    <nav class="navbar">
        <div class="navbar-container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="logo-cont">
                        <a href="index.php" class="navbar-logo">
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
                        <a href="index.php" class="nav-link <?= $activeLink === 'index' ? 'active' : '' ?>">Home</a>
                        <a href="services/index.php"
                            class="nav-link <?= $activeLink === 'services' ? 'active' : '' ?>">Services</a>
                        <a href="products/index.php"
                            class="nav-link <?= $activeLink === 'products' ? 'active' : '' ?>">Products</a>
                        <a href="contact.php" class="nav-link <?= $activeLink === 'contact' ? 'active' : '' ?>">Contact
                            Us</a>
                        <a href="about.php" class="nav-link <?= $activeLink === 'about' ? 'active' : '' ?>">About
                            Us</a>
                        <div class="actions d-none"><!-- Only show for mobile view -->
                            <a href="<?= app('auth') ?>" class="ui basic teal button login-btn">
                                Log In
                            </a>
                            <button class="ui blue large button book-now-btn" data-open-modal="#bookNowModal">
                                Book Now
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="actions">
                        <a href="<?= app('auth') ?>" class="ui basic teal button login-btn">
                            Log In
                        </a>
                        <button class="ui blue large button book-now-btn" data-open-modal="#bookNowModal">
                            Book Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
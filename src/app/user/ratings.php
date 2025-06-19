<?php include_once __DIR__ . '/../../core/app.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta'); ?> <!-- rcs Meta -->
    <title>Ratings - VetSync</title>
    <?= shared('elements/styles'); ?> <!-- rcs Styles -->
</head>

<body>
    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window'); ?> <!-- Window Spinner -->
        <?= shared('layouts/top-redirect-btn'); ?> <!-- Top Redirect Button -->
    </div>

    <div class="site-cont">
        <!-- Site Header -->
        <?= partial('layouts/header'); ?>

        <main class="site-main" style="display: flex; flex-direction: column; align-items: center; margin-top: 40px;">
            <h2 style="text-align: center; margin-bottom: 30px; text-decoration: underline;">RATINGS</h2>
            <div style="display: flex; justify-content: center; width: 100%;">
                <!-- Service Card -->
                <div
                    style="flex: 1; max-width: 400px; background: #e0e0e0; margin: 0 20px; border-radius: 8px; padding: 20px;">
                    <div style="text-align: center;">
                        <span style="font-weight: bold; text-decoration: underline;">SERVICE</span>
                    </div>
                    <div style="display: flex; align-items: center; margin-top: 20px;">
                        <img src="/public/img/avatars/chris.jpg" alt="Service"
                            style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover; margin-right: 15px;">
                        <div>
                            <div><b>Name:</b> Daniel</div>
                            <div><b>Rate:</b> OOOO</div>
                            <div><b>Service:</b> Groom</div>
                        </div>
                    </div>
                    <div style="background: #ffd6d6; margin-top: 20px; padding: 15px; border-radius: 6px;">
                        <b>COMMENT:</b> from the dashboard ratings
                    </div>
                </div>
                <!-- Divider -->
                <div style="width: 2px; background: #222; margin: 0 20px;"></div>
                <!-- Product Card -->
                <div
                    style="flex: 1; max-width: 400px; background: #e0e0e0; margin: 0 20px; border-radius: 8px; padding: 20px;">
                    <div style="text-align: center;">
                        <span style="font-weight: bold; text-decoration: underline;">PRODUCT</span>
                    </div>
                    <div style="display: flex; align-items: center; margin-top: 20px;">
                        <img src="/public/img/contents/products/pdogfood.jpg" alt="Product"
                            style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover; margin-right: 15px;">
                        <div>
                            <div><b>Name:</b> anti-rabies</div>
                            <div><b>Rate:</b> OOOO</div>
                        </div>
                    </div>
                    <div style="background: #ffd6d6; margin-top: 20px; padding: 15px; border-radius: 6px;">
                        <b>COMMENT:</b> from the dashboard ratings
                    </div>
                </div>
            </div>
        </main>
    </div>

    <?= shared('elements/scripts'); ?> <!-- rcs Scripts -->
</body>

</html>
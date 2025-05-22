<?php include_once __DIR__ . '/../../core/app.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta'); ?> <!-- rcs Meta -->
    <title>Settings - VetSync</title>
    <?= shared('elements/styles'); ?> <!-- rcs Styles -->
    <link rel="stylesheet" href="<?= featured('user/settings/css/settings.css', true); ?>">
</head>

<body>
    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window'); ?>
        <?= shared('layouts/top-redirect-btn'); ?>
    </div>

    <div class="container-body">
        <!-- Site Header -->
        <?= partial('layouts/header'); ?>

        <main class="site-main">
            <!-- Header -->
            <?= featured('user/settings/components/header'); ?>

            <!-- Settings -->
            <section class="settings pb-5">
                <div class="container-xl">
                    <div class="row g-4">
                        <div class="col-md-3">
                            <!-- Settings Nav -->
                            <?= featured('user/settings/components/settings-nav'); ?>
                        </div>
                        <div class="col-md-9">
                            <div class="card">
                                <div class="tab-content card-body">
                                    <!-- Profile Tab Start -->
                                    <?= featured('user/settings/components/tab/profile'); ?>
                                    <!-- Profile Tab End -->

                                    <!-- Account Tab Start -->
                                    <?= featured('user/settings/components/tab/account'); ?>
                                    <!-- Account Tab End -->

                                    <!-- Notification Tab Start -->
                                    <?= featured('user/settings/components/tab/notification'); ?>
                                    <!-- Notification Tab End -->

                                    <!-- Preferences Tab Start -->
                                    <?= featured('user/settings/components/tab/preferences'); ?>
                                    <!-- Preferences Tab End -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <?= shared('elements/scripts'); ?>
</body>

</html>
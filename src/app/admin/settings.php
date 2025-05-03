<?php include_once '../../../src/utils/php/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta') ?>
    <title>Admin Dashboard - Settings</title>
    <?= shared('elements/styles') ?>
    <link rel="stylesheet" href="<?= featured('admin/settings/css/settings.css', true) ?>">
</head>

<body>

    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window') ?> <!-- Window Spinner -->
        <?= shared('components/reminder-modal') ?> <!-- Reminder Modal -->
        <?= shared('components/flyout') ?> <!-- Flyout -->
    </div>

    <div class="container-body pusher">
        <!-- Sidebar -->
        <?= featured('admin/shared/layouts/sidebar') ?>

        <!-- Main Content -->
        <main class="container-main">
            <!-- Header -->
            <?= featured('admin/settings/components/header') ?>

            <div class="row">
                <div class="col-lg-9">
                    <!-- Settings -->
                    <section class="settings">
                        <div class="tab-content container box">
                            <!-- Profile Tab -->
                            <?= featured('admin/settings/components/tab/profile') ?>

                            <!-- Account Tab -->
                            <?= featured('admin/settings/components/tab/account') ?>

                            <!-- Notification Tab -->
                            <?= featured('admin/settings/components/tab/notification') ?>

                            <!-- Preferences Tab -->
                            <?= featured('admin/settings/components/tab/preferences') ?>
                        </div>
                    </section>
                </div>
                <div class="col-lg-3">
                    <!-- Settings Nav -->
                    <?= featured('admin/settings/components/settings-nav') ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts') ?>
</body>

</html>
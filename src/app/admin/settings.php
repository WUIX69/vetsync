<?php include_once __DIR__ . '/../../core/app.php'; ?>
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
        <?= partial('layouts/sidebar') ?>
        <!-- Header -->
        <?= partial('layouts/navbar') ?>

        <!-- Main Content -->
        <main class="container-main">

            <div class="row">
                <div class="col-lg-9">
                    <!-- Settings -->
                    <section class="settings">
                        <div class="tab-content container box">
                            <!-- Profile Tab -->
                            <?= featured('settings/components/tab-admin/profile-admin') ?>

                            <!-- Account Tab -->
                            <?= featured('settings/components/tab-admin/account-admin') ?>

                            <!-- Notification Tab -->
                            <?= featured('settings/components/tab-admin/notification-admin') ?>

                            <!-- Preferences Tab -->
                            <?= featured('settings/components/tab-admin/preferences-admin') ?>
                        </div>
                    </section>
                </div>
                <div class="col-lg-3">
                    <!-- Settings Nav -->
                    <?= featured('settings/components/settings-nav-admin') ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts') ?>
</body>

</html>
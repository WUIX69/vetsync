<?php include_once __DIR__ . '/../../core/app.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta'); ?> <!-- rcs Meta -->
    <title>Settings - VetSync</title>
    <?= shared('elements/styles'); ?> <!-- rcs Styles -->
    <link rel="stylesheet" href="<?= featured('settings/css/settings.css', true); ?>">
    <!-- Filepond -->
    <link rel="stylesheet" href="https://unpkg.com/filepond/dist/filepond.min.css">
    <link rel="stylesheet"
        href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css">
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
            <?= featured('settings/components/header'); ?>

            <!-- Settings -->
            <section class="settings pb-5">
                <div class="container-xl">
                    <div class="row g-4">
                        <div class="col-md-3">
                            <!-- Settings Nav -->
                            <?= featured('settings/components/settings-nav'); ?>
                        </div>
                        <div class="col-md-9">
                            <div class="card">
                                <div class="tab-content card-body">
                                    <!-- Profile Tab Start -->
                                    <?= featured('settings/components/tab/profile'); ?>
                                    <!-- Profile Tab End -->

                                    <!-- Account Tab Start -->
                                    <?= featured('settings/components/tab/account'); ?>
                                    <!-- Account Tab End -->

                                    <!-- Notification Tab Start -->
                                    <?= featured('settings/components/tab/notification'); ?>
                                    <!-- Notification Tab End -->

                                    <!-- Preferences Tab Start -->
                                    <?= featured('settings/components/tab/preferences'); ?>
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
    <!-- Filepond -->
    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
    <script
        src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js"></script>
    <script
        src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.min.js"></script>
    <script
        src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js"></script>
    <!-- Profile -->
    <script src="<?= featured('settings/js/profile.js', true); ?>"></script>
    <script src="<?= featured('settings/js/profile-upload.js', true); ?>"></script>
</body>

</html>
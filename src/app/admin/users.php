<?php include_once '../../../src/utils/php/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta') ?>
    <title>Users Management - Admin</title>
    <?= shared('elements/styles') ?>
    <?= featured('admin/users/styles') ?>
</head>

<body>

    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window') ?> <!-- Window Spinner -->
        <?= featured('admin/users/components/add-edit-usermodal') ?> <!-- add-edit-usermodal -->
    </div>

    <div class="container-body pusher">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-auto">
                <?= featured('admin/shared/layouts/sidebar') ?> <!-- Sidebar -->
            </div>

            <!-- Main Content -->
            <div class="col">
                <main class="container-main">
                    <h1>Users Management</h1>
                    <!-- user-stats -->
                    <?= featured('admin/users/components/user-stats') ?>

                    <!-- new-user-->
                    <?= featured('admin/users/components/new-users') ?>

                    <!-- user-list -->
                    <?= featured('admin/users/components/user-list') ?>
                </main>
            </div>

            <!-- Right Section -->
            <div class="col-lg-3">
                <?= featured('admin/users/components/right-section') ?>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts') ?>
    <?= featured('admin/users/scripts') ?>
</body>

</html>
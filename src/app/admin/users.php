<?php include_once __DIR__ . '/../../core/app.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta') ?>
    <title>Users Management - Admin</title>
    <?= shared('elements/styles') ?>
    <!-- DataTables -->
    <?= shared('elements/dataTables/styles') ?>
</head>

<body>

    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window') ?> <!-- Window Spinner -->
        <?= featured('users/components/user-modal') ?> <!-- User Modal -->
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
                    <!-- User Stats -->
                    <?= featured('users/components/user-stats') ?>

                    <!-- New Users -->
                    <?= featured('users/components/new-users') ?>
                </div>
                <div class="col-lg-3">
                    <!-- System Info -->
                    <?= featured('users/components/sys-info') ?>
                </div>
                <div class="col-lg-12">
                    <!-- Users Table -->
                    <?= featured('users/components/users-table') ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts') ?>
    <!-- DataTables -->
    <?= shared('elements/dataTables/scripts') ?>

    <!-- Utils -->
    <script src="<?= utils('js/formatters.js', true) ?>"></script>
    <script src="<?= utils('js/tableListFilters.js', true) ?>"></script>

    <!-- Page Scripts -->
    <script src="<?= featured('users/js/usersDataTable.js', true) ?>"></script>
    <script src="<?= featured('users/js/validateUserForm.js', true) ?>"></script>
</body>

</html>
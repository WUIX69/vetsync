<?php include_once '../../core/app.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta') ?>
    <title>Users Management - Admin</title>
    <?= shared('elements/styles') ?>

    <!-- Required for DataTables -->
    <link rel="stylesheet" href="<?= asset('lib/DataTables/datatables.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('lib/DataTables/dataTables.semanticui.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('lib/DataTables/responsive.semanticui.min.css') ?>">
</head>

<body>

    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window') ?> <!-- Window Spinner -->
        <?= featured('admin/users/components/user-modal') ?> <!-- User Modal -->
    </div>

    <div class="container-body pusher">
        <!-- Sidebar -->
        <?= featured('admin/shared/layouts/sidebar') ?>

        <!-- Main Content -->
        <main class="container-main">
            <!-- Header -->
            <?= featured('admin/users/components/header') ?>

            <div class="row">
                <div class="col-lg-9">
                    <!-- User Stats -->
                    <?= featured('admin/users/components/user-stats') ?>

                    <!-- New Users -->
                    <?= featured('admin/users/components/new-users') ?>

                    <!-- Users List -->
                    <?= featured('admin/users/components/users-list') ?>
                </div>
                <div class="col-lg-3">
                    <!-- Recent Activity -->
                    <?= featured('admin/users/components/recent-act') ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts') ?>
    <!-- Required for DataTables -->
    <script src="<?= asset('lib/DataTables/datatables.min.js') ?>"></script>
    <script src="<?= asset('lib/DataTables/dataTables.semanticui.min.js') ?>"></script>
    <script src="<?= asset('lib/DataTables/dataTables.responsive.min.js') ?>"></script>
    <script src="<?= asset('lib/DataTables/responsive.semanticui.min.js') ?>"></script>

    <!-- Required for tables -->
    <script src="<?= utils('js/formatters.js', true) ?>"></script>
    <script src="<?= shared('js/tableListFilters.js', true) ?>"></script>

    <!-- Page Scripts -->
    <script src="<?= featured('admin/users/js/usersDataTable.js', true) ?>"></script>
    <script src="<?= featured('admin/users/js/validateUserForm.js', true) ?>"></script>
</body>

</html>
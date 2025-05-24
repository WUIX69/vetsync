<?php include_once __DIR__ . '/../../core/app.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta') ?>
    <title>Services Management - Admin</title>
    <?= shared('elements/styles') ?>
    <?= featured('admin/dashboard/styles') ?>
    <style>
        /* Services Management Styles */
        .services-container {
            margin-bottom: 2rem;
        }

        .services-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .services-header h2 {
            margin: 0;
            font-size: 1.5rem;
        }

        .services-cards {
            margin-bottom: 2rem;
        }

        .service-card {
            background: var(--color-white);
            border-radius: 0.6rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .service-card .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .service-card .card-header .title {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .service-card .card-header .title h3 {
            margin: 0;
            font-size: 1.2rem;
        }

        .service-card .card-header .title .icon {
            background: #f3f4f6;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .service-card .card-header .status {
            font-size: 0.8rem;
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .service-card .card-header .status.available {
            background: #e6f7ed;
            color: #0d9344;
        }

        .service-card .card-header .status.unavailable {
            background: #fbe9e9;
            color: #c53030;
        }

        .service-card .card-body {
            padding: 1.5rem;
        }

        .service-card .card-body .description {
            color: #6b7280;
            font-size: 0.95rem;
            margin-bottom: 1.2rem;
            line-height: 1.6;
        }

        .service-card .card-body .meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .service-card .card-body .meta .item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #6b7280;
            font-size: 0.9rem;
        }

        .service-card .card-footer {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* Service Form Modal */
        .service-form-modal .ui.form .field {
            margin-bottom: 1.2rem;
        }

        .service-form-modal .ui.form label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .service-form-modal .ui.form .field.image-preview {
            margin-top: 1rem;
        }

        .service-form-modal .ui.form .field.image-preview img {
            max-width: 100%;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
        }

        /* Service Stats */
        .service-stats {
            margin-bottom: 2rem;
        }

        .service-stats .box {
            padding: 1.5rem;
            border-radius: 0.5rem;
            background: var(--color-white);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .service-stats .stat-card {
            text-align: center;
            padding: 1.5rem 1rem;
        }

        .service-stats .stat-card .icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--bs-primary);
        }

        .service-stats .stat-card .count {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .service-stats .stat-card .label {
            color: #6b7280;
            font-size: 0.9rem;
        }

        /* Services Filters */
        .services-filters {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .services-filters .ui.input {
            width: 300px;
        }

        @media (max-width: 768px) {
            .services-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .services-filters {
                flex-direction: column;
                align-items: flex-start;
            }

            .services-filters .ui.input {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window') ?> <!-- Window Spinner -->

        <!-- Service Form Modal -->
        <div class="ui tiny modal service-form-modal">
            <i class="close icon"></i>
            <div class="header">
                <i class="plus circle icon"></i> Add New Service
            </div>
            <div class="content">
                <form class="ui form">
                    <div class="field">
                        <label>Service Name</label>
                        <input type="text" name="name" placeholder="Enter service name">
                    </div>
                    <div class="field">
                        <label>Description</label>
                        <textarea name="description" rows="3" placeholder="Enter service description"></textarea>
                    </div>
                    <div class="two fields">
                        <div class="field">
                            <label>Price</label>
                            <div class="ui labeled input">
                                <div class="ui label">$</div>
                                <input type="number" name="price" placeholder="0.00">
                            </div>
                        </div>
                        <div class="field">
                            <label>Duration (minutes)</label>
                            <input type="number" name="duration" placeholder="30">
                        </div>
                    </div>
                    <div class="field">
                        <label>Status</label>
                        <div class="ui selection dropdown">
                            <input type="hidden" name="status">
                            <i class="dropdown icon"></i>
                            <div class="default text">Select Status</div>
                            <div class="menu">
                                <div class="item" data-value="available">
                                    <i class="check circle green icon"></i>Available
                                </div>
                                <div class="item" data-value="unavailable">
                                    <i class="times circle red icon"></i>Unavailable
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label>Category</label>
                        <div class="ui selection dropdown">
                            <input type="hidden" name="category">
                            <i class="dropdown icon"></i>
                            <div class="default text">Select Category</div>
                            <div class="menu">
                                <div class="item" data-value="examination">
                                    <i class="stethoscope icon"></i>Examination
                                </div>
                                <div class="item" data-value="treatment">
                                    <i class="medkit icon"></i>Treatment
                                </div>
                                <div class="item" data-value="surgery">
                                    <i class="cut icon"></i>Surgery
                                </div>
                                <div class="item" data-value="grooming">
                                    <i class="shower icon"></i>Grooming
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label>Upload Image</label>
                        <input type="file" name="image">
                    </div>
                    <div class="field image-preview">
                        <img id="imagePreview" src="<?= asset('img/services/placeholder.jpg') ?>"
                            alt="Service Image Preview">
                    </div>
                </form>
            </div>
            <div class="actions">
                <div class="ui black deny button">
                    Cancel
                </div>
                <div class="ui positive right labeled icon button">
                    Save
                    <i class="checkmark icon"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="container-body pusher">
        <!-- Sidebar -->
        <?= featured('admin/shared/layouts/sidebar') ?> <!-- Sidebar -->

        <!-- Main Content -->
        <main class="container-main">
            <!-- Header -->
            <?= featured('admin/dashboard/components/header') ?>

            <div class="row">
                <div class="col-lg-9">
                    <!-- Services Stats -->
                    <section class="service-stats">
                        <div class="row g-4">
                            <div class="col-md-3">
                                <div class="box stat-card">
                                    <div class="icon">
                                        <i class="check circle icon"></i>
                                    </div>
                                    <div class="count">12</div>
                                    <div class="label">Available Services</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="box stat-card">
                                    <div class="icon">
                                        <i class="times circle icon"></i>
                                    </div>
                                    <div class="count">3</div>
                                    <div class="label">Unavailable Services</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="box stat-card">
                                    <div class="icon">
                                        <i class="calendar check icon"></i>
                                    </div>
                                    <div class="count">254</div>
                                    <div class="label">Bookings This Month</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="box stat-card">
                                    <div class="icon">
                                        <i class="dollar sign icon"></i>
                                    </div>
                                    <div class="count">$5,420</div>
                                    <div class="label">Revenue This Month</div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Services List -->
                    <section class="services-container box">
                        <div class="services-header">
                            <h2>Services Management</h2>
                            <button class="ui primary button" id="addServiceBtn">
                                <i class="plus icon"></i> Add New Service
                            </button>
                        </div>

                        <div class="services-filters">
                            <div class="ui icon input">
                                <input type="text" placeholder="Search services...">
                                <i class="search icon"></i>
                            </div>

                            <div class="ui compact selection dropdown">
                                <input type="hidden" name="status-filter">
                                <i class="filter icon"></i>
                                <div class="default text">All Statuses</div>
                                <div class="menu">
                                    <div class="item" data-value="all">All Statuses</div>
                                    <div class="item" data-value="available">Available</div>
                                    <div class="item" data-value="unavailable">Unavailable</div>
                                </div>
                            </div>

                            <div class="ui compact selection dropdown">
                                <input type="hidden" name="category-filter">
                                <i class="filter icon"></i>
                                <div class="default text">All Categories</div>
                                <div class="menu">
                                    <div class="item" data-value="all">All Categories</div>
                                    <div class="item" data-value="examination">Examination</div>
                                    <div class="item" data-value="treatment">Treatment</div>
                                    <div class="item" data-value="surgery">Surgery</div>
                                    <div class="item" data-value="grooming">Grooming</div>
                                </div>
                            </div>
                        </div>

                        <div class="services-cards">
                            <!-- Service Card -->
                            <div class="service-card">
                                <div class="card-header">
                                    <div class="title">
                                        <div class="icon">
                                            <i class="stethoscope icon"></i>
                                        </div>
                                        <h3>General Examination</h3>
                                    </div>
                                    <div class="status available">
                                        <i class="check circle icon"></i>
                                        Available
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="description">
                                        Comprehensive check-up of your pet's overall health status, including weight,
                                        temperature, heart and lung sounds, and more.
                                    </div>
                                    <div class="meta">
                                        <div class="item">
                                            <i class="dollar sign icon"></i>
                                            <span>$45.00</span>
                                        </div>
                                        <div class="item">
                                            <i class="clock outline icon"></i>
                                            <span>30 minutes</span>
                                        </div>
                                        <div class="item">
                                            <i class="tag icon"></i>
                                            <span>Examination</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button class="ui basic button">
                                        <i class="eye icon"></i>
                                        View
                                    </button>
                                    <button class="ui primary basic button">
                                        <i class="edit icon"></i>
                                        Edit
                                    </button>
                                    <button class="ui red basic button">
                                        <i class="times circle icon"></i>
                                        Set Unavailable
                                    </button>
                                </div>
                            </div>

                            <!-- Service Card 2 -->
                            <div class="service-card">
                                <div class="card-header">
                                    <div class="title">
                                        <div class="icon">
                                            <i class="shower icon"></i>
                                        </div>
                                        <h3>Pet Grooming</h3>
                                    </div>
                                    <div class="status unavailable">
                                        <i class="times circle icon"></i>
                                        Unavailable
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="description">
                                        Professional grooming service including bath, hair trimming, nail clipping, ear
                                        cleaning, and more based on your pet's needs.
                                    </div>
                                    <div class="meta">
                                        <div class="item">
                                            <i class="dollar sign icon"></i>
                                            <span>$65.00</span>
                                        </div>
                                        <div class="item">
                                            <i class="clock outline icon"></i>
                                            <span>60 minutes</span>
                                        </div>
                                        <div class="item">
                                            <i class="tag icon"></i>
                                            <span>Grooming</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button class="ui basic button">
                                        <i class="eye icon"></i>
                                        View
                                    </button>
                                    <button class="ui primary basic button">
                                        <i class="edit icon"></i>
                                        Edit
                                    </button>
                                    <button class="ui green basic button">
                                        <i class="check circle icon"></i>
                                        Set Available
                                    </button>
                                </div>
                            </div>

                            <!-- Service Card 3 -->
                            <div class="service-card">
                                <div class="card-header">
                                    <div class="title">
                                        <div class="icon">
                                            <i class="medkit icon"></i>
                                        </div>
                                        <h3>Vaccination</h3>
                                    </div>
                                    <div class="status available">
                                        <i class="check circle icon"></i>
                                        Available
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="description">
                                        Essential vaccinations to protect your pet against common diseases. Based on
                                        age, lifestyle, and previous vaccination history.
                                    </div>
                                    <div class="meta">
                                        <div class="item">
                                            <i class="dollar sign icon"></i>
                                            <span>$35.00</span>
                                        </div>
                                        <div class="item">
                                            <i class="clock outline icon"></i>
                                            <span>15 minutes</span>
                                        </div>
                                        <div class="item">
                                            <i class="tag icon"></i>
                                            <span>Treatment</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button class="ui basic button">
                                        <i class="eye icon"></i>
                                        View
                                    </button>
                                    <button class="ui primary basic button">
                                        <i class="edit icon"></i>
                                        Edit
                                    </button>
                                    <button class="ui red basic button">
                                        <i class="times circle icon"></i>
                                        Set Unavailable
                                    </button>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Right Section -->
                <div class="col-lg-3">
                    <!-- Service Activity -->
                    <div class="box mb-4">
                        <h4 class="ui header mb-3">Recent Activity</h4>
                        <div class="ui feed">
                            <div class="event">
                                <div class="label">
                                    <i class="pencil icon"></i>
                                </div>
                                <div class="content">
                                    <div class="summary">
                                        Service "Dental Cleaning" updated
                                        <div class="date">5 minutes ago</div>
                                    </div>
                                </div>
                            </div>
                            <div class="event">
                                <div class="label">
                                    <i class="plus icon"></i>
                                </div>
                                <div class="content">
                                    <div class="summary">
                                        New service "Microchipping" added
                                        <div class="date">2 hours ago</div>
                                    </div>
                                </div>
                            </div>
                            <div class="event">
                                <div class="label">
                                    <i class="times icon"></i>
                                </div>
                                <div class="content">
                                    <div class="summary">
                                        "Pet Grooming" marked as unavailable
                                        <div class="date">Yesterday</div>
                                    </div>
                                </div>
                            </div>
                            <div class="event">
                                <div class="label">
                                    <i class="check icon"></i>
                                </div>
                                <div class="content">
                                    <div class="summary">
                                        "Ultrasound" marked as available
                                        <div class="date">3 days ago</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Most Booked Services -->
                    <div class="box">
                        <h4 class="ui header mb-3">Most Booked Services</h4>
                        <div class="ui relaxed divided list">
                            <div class="item">
                                <div class="content">
                                    <div class="header">General Examination</div>
                                    <div class="description">128 bookings this month</div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="content">
                                    <div class="header">Vaccination</div>
                                    <div class="description">86 bookings this month</div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="content">
                                    <div class="header">Pet Grooming</div>
                                    <div class="description">42 bookings this month</div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="content">
                                    <div class="header">Dental Cleaning</div>
                                    <div class="description">29 bookings this month</div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="content">
                                    <div class="header">X-Ray</div>
                                    <div class="description">17 bookings this month</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts') ?>
    <script src="<?= featured('admin/dashboard/js/main.js', true) ?>"></script>
    <script>
        $(document).ready(function () {
            // Initialize dropdowns
            $('.ui.dropdown').dropdown();

            // Add Service Button - Open modal
            $('#addServiceBtn').on('click', function () {
                $('.ui.modal.service-form-modal').modal('show');
            });

            // Image preview
            $('input[name="image"]').on('change', function (e) {
                if (e.target.files && e.target.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#imagePreview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(e.target.files[0]);
                }
            });

            // Status toggle buttons
            $('.ui.red.basic.button').on('click', function () {
                // In real implementation, this would call an API to update the service status
                $(this).closest('.service-card').find('.status')
                    .removeClass('available')
                    .addClass('unavailable')
                    .html('<i class="times circle icon"></i> Unavailable');

                $(this).replaceWith(`
                    <button class="ui green basic button">
                        <i class="check circle icon"></i>
                        Set Available
                    </button>
                `);
            });

            $('.ui.green.basic.button').on('click', function () {
                // In real implementation, this would call an API to update the service status
                $(this).closest('.service-card').find('.status')
                    .removeClass('unavailable')
                    .addClass('available')
                    .html('<i class="check circle icon"></i> Available');

                $(this).replaceWith(`
                    <button class="ui red basic button">
                        <i class="times circle icon"></i>
                        Set Unavailable
                    </button>
                `);
            });
        });
    </script>
</body>

</html>
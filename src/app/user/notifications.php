<?php include_once __DIR__ . '/../../core/app.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta'); ?>
    <title>Notifications - VetSync</title>
    <?= shared('elements/styles'); ?>
    <style>
        main section.notifications-page {
            background: var(--color-background);
            padding: 2rem 0;
            min-height: 70vh;
        }

        .notifications-page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .notifications-page-header h1 {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .notifications-page-filters {
            display: flex;
            gap: 10px;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 0.8rem 1.5rem;
            border-radius: 0.5rem;
            border: 2px solid #e0e0e0;
            background: white;
            color: #555;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .filter-btn:hover {
            border-color: #2185d0;
            background-color: #f0f9ff;
        }

        .filter-btn.active {
            background-color: #2185d0;
            color: white;
            border-color: #2185d0;
        }

        .notifications-page-list {
            min-height: 400px;
        }

        .loading-notifications {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
        }

        .loading-notifications .ui.loader {
            margin-bottom: 1rem;
        }

        .notification-page-item {
            padding: 20px;
            border: 1px solid #e8e8e8;
            border-radius: 12px;
            margin-bottom: 14px;
            display: flex;
            gap: 16px;
            align-items: flex-start;
            transition: all 0.2s ease;
            background: white;
        }

        .notification-page-item:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            border-color: #ccc;
            transform: translateY(-2px);
        }

        .notification-page-item.unread {
            background: linear-gradient(to right, #f0f9ff 0%, #ffffff 100%);
            border-left: 4px solid #2185d0;
        }

        .notification-page-item-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
        }

        .notification-page-item-icon i {
            font-size: 22px;
            margin: 0 !important;
        }

        .notification-page-item-icon.green {
            background: linear-gradient(135deg, #21ba45 0%, #16ab39 100%);
            color: white;
        }

        .notification-page-item-icon.red {
            background: linear-gradient(135deg, #db2828 0%, #ca1010 100%);
            color: white;
        }

        .notification-page-item-icon.blue {
            background: linear-gradient(135deg, #2185d0 0%, #1678c2 100%);
            color: white;
        }

        .notification-page-item-icon.orange {
            background: linear-gradient(135deg, #f2711c 0%, #e8590c 100%);
            color: white;
        }

        .notification-page-item-icon.yellow {
            background: linear-gradient(135deg, #fbbd08 0%, #eaae00 100%);
            color: white;
        }

        .notification-page-item-icon.teal {
            background: linear-gradient(135deg, #00b5ad 0%, #009c95 100%);
            color: white;
        }

        .notification-page-content {
            flex: 1;
            min-width: 0;
        }

        .notification-page-header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
            gap: 15px;
        }

        .notification-page-title {
            font-size: 16px;
            font-weight: 600;
            color: #1a1a1a;
            margin: 0;
            line-height: 1.3;
        }

        .notification-page-time {
            font-size: 12px;
            color: #999;
            white-space: nowrap;
            font-weight: 500;
        }

        .notification-page-message {
            font-size: 14px;
            color: #555;
            line-height: 1.6;
            margin: 0 0 12px 0;
        }

        .notification-page-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 4px;
        }

        .notification-page-actions .ui.button {
            margin: 0 !important;
            padding: 8px 16px !important;
            font-size: 13px !important;
            border-radius: 6px !important;
            font-weight: 500 !important;
            transition: all 0.2s ease !important;
        }

        .notification-page-actions .ui.button i {
            margin: 0 6px 0 0 !important;
        }

        .notification-page-actions .ui.primary.button {
            background: linear-gradient(135deg, #2185d0 0%, #1678c2 100%) !important;
            box-shadow: 0 2px 6px rgba(33, 133, 208, 0.25) !important;
        }

        .notification-page-actions .ui.primary.button:hover {
            background: linear-gradient(135deg, #1678c2 0%, #1a69a4 100%) !important;
            box-shadow: 0 4px 10px rgba(33, 133, 208, 0.35) !important;
            transform: translateY(-2px);
        }

        .notification-page-actions .ui.red.button {
            background: white !important;
            border: 1.5px solid #db2828 !important;
            color: #db2828 !important;
            box-shadow: 0 2px 4px rgba(219, 40, 40, 0.1) !important;
        }

        .notification-page-actions .ui.red.button:hover {
            background: #db2828 !important;
            color: white !important;
            box-shadow: 0 4px 8px rgba(219, 40, 40, 0.3) !important;
            transform: translateY(-2px);
        }

        .empty-notifications {
            text-align: center;
            padding: 5rem 2rem;
            color: #999;
        }

        .empty-notifications i {
            font-size: 80px;
            opacity: 0.3;
            margin-bottom: 1.5rem;
        }

        .empty-notifications h3 {
            color: #666;
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
        }

        .empty-notifications p {
            color: #999;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .notification-page-item {
                padding: 1rem;
                flex-direction: column;
            }

            .notification-page-item-icon {
                width: 40px;
                height: 40px;
            }

            .notification-page-item-icon i {
                font-size: 20px;
            }

            .notifications-page-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window'); ?>
        <?= shared('layouts/top-redirect-btn'); ?>
    </div>

    <div class="site-cont">
        <?= partial('layouts/header'); ?>

        <main class="site-main">
            <section class="header py-5">
                <div class="container-xl">
                    <h1>All Notifications <span class="emoji">🔔</span></h1>
                    <p>View and manage all your notifications.</p>
                </div>
            </section>

            <section class="notifications-page">
                <div class="container-xl">
                    <div class="notifications-page-header">
                        <div class="notifications-page-filters">
                            <button class="filter-btn active" data-filter="all">
                                <i class='bx bx-list-ul'></i> All
                            </button>
                            <button class="filter-btn" data-filter="unread">
                                <i class='bx bx-envelope'></i> Unread
                            </button>
                            <button class="filter-btn" data-filter="appointments">
                                <i class='bx bx-calendar'></i> Appointments
                            </button>
                            <button class="filter-btn" data-filter="reservations">
                                <i class='bx bx-bookmark'></i> Reservations
                            </button>
                        </div>

                        <button class="ui button mark-all-read-page">
                            <i class='bx bx-check-double'></i> Mark All as Read
                        </button>
                    </div>

                    <div class="notifications-page-list" id="notificationsPageList">
                        <div class="loading-notifications">
                            <div class="ui active inline loader"></div>
                            <p>Loading notifications...</p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <?= shared('elements/scripts'); ?>

    <script>
        const NotificationsPage = {
            currentFilter: 'all',
            allNotifications: [],

            init() {
                this.bindEvents();
                this.loadNotifications();
            },

            bindEvents() {
                // Filter buttons
                $('.filter-btn').on('click', (e) => {
                    const $btn = $(e.currentTarget);
                    const filter = $btn.data('filter');

                    $('.filter-btn').removeClass('active');
                    $btn.addClass('active');

                    this.currentFilter = filter;
                    this.renderNotifications();
                });

                // Mark all as read
                $('.mark-all-read-page').on('click', () => {
                    this.markAllAsRead();
                });

                // Individual notification delete
                $(document).on('click', '.delete-notification-page', (e) => {
                    e.preventDefault();
                    const notificationId = $(e.currentTarget).data('id');
                    this.deleteNotification(notificationId);
                });

                // View notification
                $(document).on('click', '.view-notification-page', (e) => {
                    const notificationId = $(e.currentTarget).closest('.notification-page-item').data('id');
                    this.markAsRead(notificationId);
                });
            },

            loadNotifications() {
                const $list = $('#notificationsPageList');
                $list.html(`
                    <div class="loading-notifications">
                        <div class="ui active inline loader"></div>
                        <p>Loading notifications...</p>
                    </div>
                `);

                $.ajax({
                    url: '/src/features/notifications/api/notifications.php',
                    method: 'GET',
                    dataType: 'json',
                    success: (response) => {
                        if (response.success) {
                            this.allNotifications = response.data;
                            this.renderNotifications();
                        } else {
                            $list.html(`
                                <div class="empty-notifications">
                                    <i class='bx bx-error-circle'></i>
                                    <h3>Error Loading Notifications</h3>
                                    <p>Please try refreshing the page</p>
                                </div>
                            `);
                        }
                    },
                    error: (xhr, status, error) => {
                        console.error('Failed to load notifications:', error);
                        $list.html(`
                            <div class="empty-notifications">
                                <i class='bx bx-error-circle'></i>
                                <h3>Error Loading Notifications</h3>
                                <p>Please try refreshing the page</p>
                            </div>
                        `);
                    }
                });
            },

            renderNotifications() {
                const $list = $('#notificationsPageList');
                $list.empty();

                // Filter notifications based on current filter
                let filtered = this.allNotifications;

                if (this.currentFilter === 'unread') {
                    filtered = this.allNotifications.filter(n => n.is_read == 0);
                } else if (this.currentFilter === 'appointments') {
                    filtered = this.allNotifications.filter(n => n.type === 'appointment');
                } else if (this.currentFilter === 'reservations') {
                    filtered = this.allNotifications.filter(n => n.type === 'reservation');
                }

                if (filtered.length === 0) {
                    const emptyMessage = this.currentFilter === 'unread' ? 'No unread notifications' :
                        this.currentFilter === 'all' ? 'No notifications yet' :
                            `No ${this.currentFilter} notifications`;

                    $list.html(`
                        <div class="empty-notifications">
                            <i class='bx bx-bell-off'></i>
                            <h3>${emptyMessage}</h3>
                            <p>You're all caught up!</p>
                        </div>
                    `);
                    return;
                }

                filtered.forEach((notification) => {
                    const $item = this.createNotificationItem(notification);
                    $list.append($item);
                });
            },

            createNotificationItem(notification) {
                const timeAgo = this.getTimeAgo(notification.created_at);
                const isUnread = notification.is_read == 0;

                const iconMap = {
                    bell: "bell",
                    calendar: "calendar alternate outline",
                    check: "check circle",
                    times: "times circle",
                    info: "info circle",
                    bookmark: "bookmark",
                };

                const iconName = iconMap[notification.icon] || "bell";

                return $(`
                    <div class="notification-page-item ${isUnread ? 'unread' : ''}" data-id="${notification.id}">
                        <div class="notification-page-item-icon ${notification.color}">
                            <i class="${iconName} icon"></i>
                        </div>
                        <div class="notification-page-content">
                            <div class="notification-page-header-row">
                                <h3 class="notification-page-title">${notification.title}</h3>
                                <span class="notification-page-time">${timeAgo}</span>
                            </div>
                            <p class="notification-page-message">${notification.message}</p>
                            <div class="notification-page-actions">
                                <a href="${notification.link}" class="ui small primary button view-notification-page">
                                    <i class="eye icon"></i>View Details
                                </a>
                                <button class="ui small basic red button delete-notification-page" data-id="${notification.id}">
                                    <i class="trash alternate outline icon"></i>Delete
                                </button>
                            </div>
                        </div>
                    </div>
                `);
            },

            markAsRead(notificationId) {
                $.ajax({
                    url: '/src/features/notifications/api/notifications.php',
                    method: 'POST',
                    data: {
                        action: 'mark_read',
                        id: notificationId
                    },
                    dataType: 'json',
                    success: (response) => {
                        if (response.success) {
                            const notification = this.allNotifications.find(n => n.id == notificationId);
                            if (notification) {
                                notification.is_read = 1;
                            }
                            $(`.notification-page-item[data-id="${notificationId}"]`).removeClass('unread');
                        }
                    }
                });
            },

            deleteNotification(notificationId) {
                if (!confirm('Are you sure you want to delete this notification?')) {
                    return;
                }

                const self = this;
                $.ajax({
                    url: '/src/features/notifications/api/notifications.php',
                    method: 'POST',
                    data: {
                        action: 'delete',
                        id: notificationId
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            $(`.notification-page-item[data-id="${notificationId}"]`).fadeOut(300, function () {
                                $(this).remove();

                                const index = self.allNotifications.findIndex(n => n.id == notificationId);
                                if (index > -1) {
                                    self.allNotifications.splice(index, 1);
                                }

                                if ($('.notification-page-item').length === 0) {
                                    self.renderNotifications();
                                }
                            });
                        }
                    }
                });
            },

            markAllAsRead() {
                const self = this;
                $.ajax({
                    url: '/src/features/notifications/api/notifications.php',
                    method: 'POST',
                    data: { action: 'mark_all_read' },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            self.allNotifications.forEach(n => n.is_read = 1);
                            $('.notification-page-item').removeClass('unread');
                            alert('All notifications marked as read!');
                        }
                    }
                });
            },

            getTimeAgo(timestamp) {
                const now = new Date();
                const time = new Date(timestamp);
                const diffInSeconds = Math.floor((now - time) / 1000);

                if (diffInSeconds < 60) return 'Just now';
                if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} minutes ago`;
                if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} hours ago`;
                if (diffInSeconds < 604800) return `${Math.floor(diffInSeconds / 86400)} days ago`;

                return time.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            }
        };

        $(document).ready(function () {
            NotificationsPage.init();
        });
    </script>
</body>

</html>
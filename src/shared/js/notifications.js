// Global Notification System - Database-backed
const NotificationSystem = {
    init() {
        this.bindEvents();
        this.loadNotifications();
        this.startPeriodicCheck();
    },

    bindEvents() {
        // Handle notification dropdown click
        $(document).on("click", "#notificationDropdown", (e) => {
            e.stopPropagation();
            const $dropdown = $(e.currentTarget);
            const $menu = $dropdown.find(".notification-menu");

            if ($menu.is(":visible")) {
                $menu.hide();
            } else {
                $(".notification-menu").hide();
                $menu.show();
                this.loadNotifications();
            }
        });

        // Handle clear all notifications
        $(document).on("click", ".mark-all-read", (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.clearAllNotifications();
        });

        // Handle individual notification delete
        $(document).on("click", ".delete-notification", (e) => {
            e.preventDefault();
            e.stopPropagation();
            const notificationId = $(e.currentTarget).data("id");
            this.deleteNotification(notificationId);
        });

        // Handle View button clicks
        $(document).on("click", ".notification-item .view-btn", (e) => {
            const notificationId = $(e.currentTarget)
                .closest(".notification-item")
                .data("notification-id");
            this.markAsRead(notificationId);
        });

        // Close dropdown when clicking outside
        $(document).on("click", (e) => {
            if (!$(e.target).closest("#notificationDropdown").length) {
                $(".notification-menu").hide();
            }
        });
    },

    loadNotifications() {
        $.ajax({
            url: "/src/features/notifications/api/notifications.php",
            method: "GET",
            dataType: "json",
            success: (response) => {
                if (response.success) {
                    this.renderNotifications(response.data);
                    this.updateNotificationBadge(response.unread_count);
                }
            },
            error: (xhr, status, error) => {
                console.error("Failed to load notifications:", error);
            },
        });
    },

    renderNotifications(notifications) {
        const $container = $(".notifications-list");
        $container.empty();

        if (notifications.length === 0) {
            $container.html(`
                <div class="notification-item empty">
                    <div class="notification-content">
                        <p>No notifications</p>
                    </div>
                </div>
            `);
            return;
        }

        notifications.forEach((notification) => {
            const $notification = this.createNotificationElement(notification);
            $container.append($notification);
        });
    },

    createNotificationElement(notification) {
        const timeAgo = this.getTimeAgo(notification.created_at);
        const isUnread = notification.is_read == 0;

        return $(`
            <div class="notification-item ${
                isUnread ? "unread" : ""
            }" data-notification-id="${notification.id}">
                <div class="notification-item-icon ${notification.color}">
                    <i class='bx bx-${notification.icon}'></i>
                </div>
                <div class="notification-content">
                    <div class="notification-item-header">
                        <strong>${notification.title}</strong>
                        <span class="notification-time">${timeAgo}</span>
                    </div>
                    <p class="notification-message">${notification.message}</p>
                </div>
                <div class="notification-actions">
                    <a href="${
                        notification.link
                    }" class="ui mini primary button view-btn">
                        <i class='bx bx-show'></i> View
                    </a>
                    <button class="ui mini basic button delete-notification" data-id="${
                        notification.id
                    }" title="Dismiss">
                        <i class='bx bx-x'></i>
                    </button>
                </div>
            </div>
        `);
    },

    markAsRead(notificationId) {
        $.ajax({
            url: "/src/features/notifications/api/notifications.php",
            method: "POST",
            data: {
                action: "mark_read",
                id: notificationId,
            },
            dataType: "json",
            success: (response) => {
                if (response.success) {
                    $(
                        `.notification-item[data-notification-id="${notificationId}"]`
                    ).removeClass("unread");
                    this.loadNotifications();
                }
            },
        });
    },

    deleteNotification(notificationId) {
        $.ajax({
            url: "/src/features/notifications/api/notifications.php",
            method: "POST",
            data: {
                action: "delete",
                id: notificationId,
            },
            dataType: "json",
            success: (response) => {
                if (response.success) {
                    const $notification = $(
                        `.notification-item[data-notification-id="${notificationId}"]`
                    );
                    $notification.fadeOut(300, function () {
                        $(this).remove();

                        if (
                            $(".notifications-list .notification-item")
                                .length === 0
                        ) {
                            $(".notifications-list").html(`
                                <div class="notification-item empty">
                                    <div class="notification-content">
                                        <p>No notifications</p>
                                    </div>
                                </div>
                            `);
                        }
                    });

                    this.loadNotifications();
                }
            },
        });
    },

    clearAllNotifications() {
        $.ajax({
            url: "/src/features/notifications/api/notifications.php",
            method: "POST",
            data: { action: "mark_all_read" },
            dataType: "json",
            success: (response) => {
                if (response.success) {
                    this.loadNotifications();
                }
            },
        });
    },

    updateNotificationBadge(unreadCount) {
        const $badge = $(".notification-badge");
        if (unreadCount > 0) {
            $badge.text(unreadCount).show();
        } else {
            $badge.hide();
        }
    },

    startPeriodicCheck() {
        setInterval(() => {
            this.loadNotifications();
        }, 30000);
    },

    getTimeAgo(timestamp) {
        const now = new Date();
        const time = new Date(timestamp);
        const diffInSeconds = Math.floor((now - time) / 1000);

        if (diffInSeconds < 60) return "Just now";
        if (diffInSeconds < 3600)
            return `${Math.floor(diffInSeconds / 60)}m ago`;
        if (diffInSeconds < 86400)
            return `${Math.floor(diffInSeconds / 3600)}h ago`;
        return `${Math.floor(diffInSeconds / 86400)}d ago`;
    },
};

$(document).ready(() => {
    NotificationSystem.init();
});

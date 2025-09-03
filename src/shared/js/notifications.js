// Global Notification System
const NotificationSystem = {
    init() {
        // console.log("NotificationSystem: Initializing...");
        this.bindEvents();
        this.loadNotifications();
        this.startPeriodicCheck();
    },

    bindEvents() {
        // console.log("NotificationSystem: Binding events...");

        // Handle notification dropdown click
        $(document).on("click", "#notificationDropdown", (e) => {
            // console.log("NotificationSystem: Dropdown clicked");
            e.stopPropagation();
            const $dropdown = $(e.currentTarget);
            const $menu = $dropdown.find(".notification-menu");

            if ($menu.is(":visible")) {
                // console.log("NotificationSystem: Hiding menu");
                $menu.hide();
            } else {
                // console.log("NotificationSystem: Showing menu");
                // Close other dropdowns
                $(".notification-menu").hide();
                $menu.show();
                this.loadNotifications();
                // Don't automatically mark as viewed when opening dropdown
            }
        });

        // Handle clear all notifications button
        $(document).on("click", ".mark-all-read", (e) => {
            // console.log("NotificationSystem: Clear all clicked");
            e.preventDefault();
            e.stopPropagation();
            this.clearAllNotifications();
        });

        // Handle individual notification clicks (but not the View button)
        $(document).on("click", ".notification-item", (e) => {
            // Only mark as read if clicking the View button, not the entire notification item
            if (
                !$(e.target).hasClass("ui") &&
                !$(e.target).hasClass("button")
            ) {
                e.preventDefault();
                e.stopPropagation();
            }
        });

        // Handle View button clicks specifically
        $(document).on("click", ".notification-item .ui.button", (e) => {
            // Allow the link to work normally, but don't clear notifications immediately
            // The user will see the page they're navigating to
        });

        // Close dropdown when clicking outside
        $(document).on("click", (e) => {
            if (!$(e.target).closest("#notificationDropdown").length) {
                $(".notification-menu").hide();
            }
        });
    },

    loadNotifications() {
        // console.log("NotificationSystem: Loading notifications...");

        Promise.all([
            this.fetchAppointmentNotifications(),
            this.fetchReservationNotifications(),
        ])
            .then(([appointments, reservations]) => {
                // console.log(
                //     "NotificationSystem: Loaded appointments:",
                //     appointments.length
                // );
                // console.log(
                //     "NotificationSystem: Loaded reservations:",
                //     reservations.length
                // );

                const allNotifications = [...appointments, ...reservations];
                this.renderNotifications(allNotifications);
                this.updateNotificationBadge(allNotifications);
            })
            .catch((error) => {
                // console.error(
                //     "NotificationSystem: Failed to load notifications:",
                //     error
                // );
            });
    },

    fetchAppointmentNotifications() {
        return new Promise((resolve) => {
            $.ajax({
                url: "/src/features/appointments/api/user-appointments.php",
                method: "GET",
                success: (response) => {
                    // console.log(
                    //     "NotificationSystem: Appointment API response:",
                    //     response
                    // );

                    if (response.success) {
                        const appointments = response.data || [];
                        const lastViewed =
                            localStorage.getItem("appointmentsLastViewed") || 0;

                        const notifications = appointments
                            .filter((appointment) => {
                                const updateTime = new Date(
                                    appointment.updated_at ||
                                        appointment.created_at
                                ).getTime();
                                return (
                                    updateTime > lastViewed &&
                                    (appointment.status === "accepted" ||
                                        appointment.status === "rejected")
                                );
                            })
                            .map((appointment) => ({
                                id: `appointment_${appointment.uuid}`,
                                type: "appointment",
                                title:
                                    appointment.status === "accepted"
                                        ? "Appointment Accepted"
                                        : "Appointment Rejected",
                                message: `Your appointment for ${appointment.service_name} has been ${appointment.status}`,
                                time:
                                    appointment.updated_at ||
                                    appointment.created_at,
                                icon:
                                    appointment.status === "accepted"
                                        ? "check-circle"
                                        : "x-circle",
                                color:
                                    appointment.status === "accepted"
                                        ? "green"
                                        : "red",
                                link: "/src/app/user/appointments.php",
                            }));

                        resolve(notifications);
                    } else {
                        resolve([]);
                    }
                },
                error: (xhr, status, error) => {
                    // console.error(
                    //     "NotificationSystem: Appointment API error:",
                    //     error
                    // );
                    resolve([]);
                },
            });
        });
    },

    fetchReservationNotifications() {
        return new Promise((resolve) => {
            $.ajax({
                url: "/src/features/reservations/api/user-reservations.php",
                method: "GET",
                success: (response) => {
                    // console.log(
                    //     "NotificationSystem: Reservation API response:",
                    //     response
                    // );

                    if (response.success) {
                        const reservations = response.data || [];
                        const lastViewed =
                            localStorage.getItem("reservationsLastViewed") || 0;

                        const notifications = reservations
                            .filter((reservation) => {
                                const updateTime = new Date(
                                    reservation.updated_at ||
                                        reservation.created_at
                                ).getTime();
                                return (
                                    updateTime > lastViewed &&
                                    (reservation.status === "accepted" ||
                                        reservation.status === "rejected")
                                );
                            })
                            .map((reservation) => {
                                const products = JSON.parse(
                                    reservation.products || "[]"
                                );
                                const productNames = products
                                    .map((p) => p.name)
                                    .join(", ");

                                return {
                                    id: `reservation_${reservation.id}`,
                                    type: "reservation",
                                    title:
                                        reservation.status === "accepted"
                                            ? "Product Reservation Accepted"
                                            : "Product Reservation Rejected",
                                    message: `Your reservation for ${productNames} has been ${reservation.status}`,
                                    time:
                                        reservation.updated_at ||
                                        reservation.created_at,
                                    icon:
                                        reservation.status === "accepted"
                                            ? "shopping-bag"
                                            : "x-circle",
                                    color:
                                        reservation.status === "accepted"
                                            ? "green"
                                            : "red",
                                    link: "/src/app/user/cart.php",
                                    rejectionReason:
                                        reservation.rejection_reason,
                                };
                            });

                        resolve(notifications);
                    } else {
                        resolve([]);
                    }
                },
                error: (xhr, status, error) => {
                    // console.error(
                    //     "NotificationSystem: Reservation API error:",
                    //     error
                    // );
                    resolve([]);
                },
            });
        });
    },

    renderNotifications(notifications) {
        // console.log(
        //     "NotificationSystem: Rendering",
        //     notifications.length,
        //     "notifications"
        // );

        const $container = $(".notifications-list");
        $container.empty();

        if (notifications.length === 0) {
            $container.html(`
                <div class="notification-item empty">
                    <div class="notification-content">
                        <p>No new notifications</p>
                    </div>
                </div>
            `);
            return;
        }

        // Sort by time (newest first)
        notifications.sort((a, b) => new Date(b.time) - new Date(a.time));

        notifications.forEach((notification) => {
            const $notification = this.createNotificationElement(notification);
            $container.append($notification);
        });
    },

    createNotificationElement(notification) {
        const timeAgo = this.getTimeAgo(notification.time);

        return $(`
            <div class="notification-item" data-notification-id="${
                notification.id
            }">
                <div class="notification-item-icon ${notification.color}">
                    <i class='bx bx-${notification.icon}'></i>
                </div>
                <div class="notification-content">
                    <div class="notification-item-header">
                        <strong>${notification.title}</strong>
                        <span class="notification-time">${timeAgo}</span>
                    </div>
                    <p class="notification-message">${notification.message}</p>
                    ${
                        notification.rejectionReason
                            ? `<p class="rejection-reason">Reason: ${notification.rejectionReason}</p>`
                            : ""
                    }
                </div>
                <div class="notification-actions">
                    <a href="${
                        notification.link
                    }" class="ui mini button">View</a>
                </div>
            </div>
        `);
    },

    updateNotificationBadge(notifications) {
        const lastViewed = Math.max(
            localStorage.getItem("appointmentsLastViewed") || 0,
            localStorage.getItem("reservationsLastViewed") || 0
        );

        const unreadCount = notifications.filter((notification) => {
            const notificationTime = new Date(notification.time).getTime();
            return notificationTime > lastViewed;
        }).length;

        // console.log("NotificationSystem: Unread notifications:", unreadCount);

        const $badge = $(".notification-badge");
        if (unreadCount > 0) {
            $badge.text(unreadCount).show();
        } else {
            $badge.hide();
        }
    },

    markNotificationsAsViewed() {
        // console.log("NotificationSystem: Marking notifications as viewed");
        const currentTime = Date.now();
        localStorage.setItem("appointmentsLastViewed", currentTime);
        localStorage.setItem("reservationsLastViewed", currentTime);
        $(".notification-badge").hide();
    },

    clearAllNotifications() {
        // console.log("NotificationSystem: Clearing all notifications");
        this.markNotificationsAsViewed();
        $(".notifications-list").html(`
            <div class="notification-item empty">
                <div class="notification-content">
                    <p>No new notifications</p>
                </div>
            </div>
        `);
    },

    startPeriodicCheck() {
        // console.log(
        //     "NotificationSystem: Starting periodic check (30s interval)"
        // );
        // Check for new notifications every 30 seconds
        setInterval(() => {
            this.loadNotifications();
        }, 30000);
    },

    getTimeAgo(timestamp) {
        const now = new Date();
        const time = new Date(timestamp);
        const diffInSeconds = Math.floor((now - time) / 1000);

        if (diffInSeconds < 60) {
            return "Just now";
        }

        if (diffInSeconds < 3600) {
            return `${Math.floor(diffInSeconds / 60)}m ago`;
        }

        if (diffInSeconds < 86400) {
            return `${Math.floor(diffInSeconds / 3600)}h ago`;
        }

        return `${Math.floor(diffInSeconds / 86400)}d ago`;
    },
};

// Initialize when document is ready
$(document).ready(() => {
    // console.log("Document ready - initializing NotificationSystem");
    NotificationSystem.init();
});

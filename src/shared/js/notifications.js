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
            // Mark notifications as viewed when user clicks View
            this.markNotificationsAsViewed();

            // Allow the link to work normally
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
                        let lastViewed =
                            localStorage.getItem("appointmentsLastViewed") || 0;

                        // Get the last reschedule timestamp for each appointment
                        const lastRescheduleTimes = JSON.parse(
                            localStorage.getItem("lastRescheduleTimes") || "{}"
                        );

                        const notifications = appointments
                            .filter((appointment) => {
                                const updateTime = new Date(
                                    appointment.updated_at ||
                                        appointment.created_at
                                ).getTime();

                                // Check if this is a reschedule notification
                                const hasRescheduleNote =
                                    appointment.note &&
                                    appointment.note.includes(
                                        "[RESCHEDULED BY ADMIN]"
                                    );

                                if (hasRescheduleNote) {
                                    // Extract reschedule timestamp from note (new format with timestamp)
                                    const rescheduleMatch =
                                        appointment.note.match(
                                            /\[RESCHEDULED BY ADMIN\][\s\S]*?(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/
                                        );

                                    if (rescheduleMatch) {
                                        const rescheduleTime = new Date(
                                            rescheduleMatch[1]
                                        ).getTime();

                                        const appointmentKey = `appointment_${appointment.uuid}`;
                                        const lastRescheduleTime =
                                            lastRescheduleTimes[
                                                appointmentKey
                                            ] || 0;

                                        // Show notification if this reschedule is newer than the last known reschedule
                                        // OR if it's newer than lastViewed (for first-time reschedules)
                                        if (
                                            rescheduleTime >
                                                lastRescheduleTime ||
                                            rescheduleTime > lastViewed
                                        ) {
                                            // Only update the stored reschedule time if this is actually newer
                                            if (
                                                rescheduleTime >
                                                lastRescheduleTime
                                            ) {
                                                lastRescheduleTimes[
                                                    appointmentKey
                                                ] = rescheduleTime;
                                                localStorage.setItem(
                                                    "lastRescheduleTimes",
                                                    JSON.stringify(
                                                        lastRescheduleTimes
                                                    )
                                                );
                                            }
                                            return true;
                                        }
                                    } else {
                                        // Fallback: if no timestamp in note, use updated_at
                                        const appointmentKey = `appointment_${appointment.uuid}`;
                                        const lastRescheduleTime =
                                            lastRescheduleTimes[
                                                appointmentKey
                                            ] || 0;

                                        if (
                                            updateTime > lastRescheduleTime ||
                                            updateTime > lastViewed
                                        ) {
                                            if (
                                                updateTime > lastRescheduleTime
                                            ) {
                                                lastRescheduleTimes[
                                                    appointmentKey
                                                ] = updateTime;
                                                localStorage.setItem(
                                                    "lastRescheduleTimes",
                                                    JSON.stringify(
                                                        lastRescheduleTimes
                                                    )
                                                );
                                            }
                                            return true;
                                        }
                                    }

                                    return false;
                                }

                                return (
                                    updateTime > lastViewed &&
                                    (appointment.status === "accepted" ||
                                        appointment.status === "rejected")
                                );
                            })
                            .map((appointment) => {
                                const hasRescheduleNote =
                                    appointment.note &&
                                    appointment.note.includes(
                                        "[RESCHEDULED BY ADMIN]"
                                    );

                                let title, message, icon, color;

                                if (hasRescheduleNote) {
                                    title = "Appointment Rescheduled";
                                    message = `Your appointment for ${appointment.service_name} has been rescheduled by the clinic. Please check your new appointment details.`;
                                    icon = "calendar-edit";
                                    color = "orange";
                                } else if (appointment.status === "accepted") {
                                    title = "Appointment Accepted";
                                    message = `Your appointment for ${appointment.service_name} has been accepted`;
                                    icon = "check-circle";
                                    color = "green";
                                } else {
                                    title = "Appointment Rejected";
                                    message = `Your appointment for ${appointment.service_name} has been rejected`;
                                    icon = "x-circle";
                                    color = "red";
                                }

                                return {
                                    id: `appointment_${appointment.uuid}`,
                                    type: "appointment",
                                    title: title,
                                    message: message,
                                    time:
                                        appointment.updated_at ||
                                        appointment.created_at,
                                    icon: icon,
                                    color: color,
                                    link: "/src/app/user/appointments.php",
                                    rescheduleReason: hasRescheduleNote
                                        ? this.extractRescheduleReason(
                                              appointment.note
                                          )
                                        : null,
                                };
                            });

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

    // Helper function to extract reschedule reason from note
    extractRescheduleReason(note) {
        if (!note || !note.includes("[RESCHEDULED BY ADMIN]")) {
            return null;
        }

        // Split by [RESCHEDULED BY ADMIN] and get the last occurrence
        const parts = note.split("[RESCHEDULED BY ADMIN]");
        if (parts.length > 1) {
            // Get the last reschedule reason (most recent)
            const lastReschedule = parts[parts.length - 1].trim();

            // Extract just the reason part (before the timestamp)
            // Format: "reason - 2024-01-15 14:30:00"
            const reasonMatch = lastReschedule.match(
                /^(.+?)\s*-\s*\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}:\d{2}$/
            );
            if (reasonMatch) {
                return reasonMatch[1].trim();
            }

            // Fallback: return the whole text if no timestamp format found
            return lastReschedule;
        }
        return null;
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
                                        reservation.status === "rejected" ||
                                        reservation.status === "completed")
                                );
                            })
                            .map((reservation) => {
                                const products = JSON.parse(
                                    reservation.products || "[]"
                                );
                                const productNames = products
                                    .map((p) => p.name)
                                    .join(", ");

                                let title, message, icon, color;

                                if (reservation.status === "completed") {
                                    title = "Product Ready for Pickup";
                                    message = `Your products (${productNames}) are ready for pickup at the clinic!`;
                                    icon = "package";
                                    color = "blue";
                                } else if (reservation.status === "accepted") {
                                    title = "Product Reservation Accepted";
                                    message = `Your reservation for ${productNames} has been accepted`;
                                    icon = "shopping-bag";
                                    color = "green";
                                } else {
                                    title = "Product Reservation Rejected";
                                    message = `Your reservation for ${productNames} has been rejected`;
                                    icon = "x-circle";
                                    color = "red";
                                }

                                return {
                                    id: `reservation_${reservation.id}`,
                                    type: "reservation",
                                    title: title,
                                    message: message,
                                    time:
                                        reservation.updated_at ||
                                        reservation.created_at,
                                    icon: icon,
                                    color: color,
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

        // Debug logging for timestamp issues
        console.log("Notification timestamp:", notification.time);
        console.log("Parsed time:", new Date(notification.time));
        console.log("Time ago:", timeAgo);

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
                    ${
                        notification.rescheduleReason
                            ? `<p class="reschedule-reason">Reschedule Reason: ${notification.rescheduleReason}</p>`
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

        // Don't reload notifications here - just hide the badge
        // this.loadNotifications(); // Remove this line
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
        // Don't call loadNotifications() here as it will reload the notifications
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

        // Handle invalid timestamps
        if (isNaN(time.getTime())) {
            return "Unknown time";
        }

        const diffInSeconds = Math.floor((now - time) / 1000);

        if (diffInSeconds < 60) {
            return "Just now";
        }

        if (diffInSeconds < 3600) {
            const minutes = Math.floor(diffInSeconds / 60);
            return `${minutes}m ago`;
        }

        if (diffInSeconds < 86400) {
            const hours = Math.floor(diffInSeconds / 3600);
            return `${hours}h ago`;
        }

        const days = Math.floor(diffInSeconds / 86400);
        return `${days}d ago`;
    },
};

// Initialize when document is ready
$(document).ready(() => {
    // console.log("Document ready - initializing NotificationSystem");
    NotificationSystem.init();
});

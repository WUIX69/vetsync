$(document).ready(function () {
    loadUserStats();
});

function loadUserStats() {
    $.ajax({
        url: "/src/features/users/api/user-stats.php",
        method: "GET",
        dataType: "json",
        success: function (response) {
            if (response.success) {
                const data = response.data;

                // Update counts only
                $("#total-users-count").text(data.total_users.toLocaleString());
                $("#active-users-count").text(
                    data.active_users.toLocaleString()
                );
                $("#new-users-today-count").text(
                    data.new_users_today.toLocaleString()
                );
            } else {
                console.error("Failed to load user stats:", response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error loading user stats:", error);
        },
    });
}

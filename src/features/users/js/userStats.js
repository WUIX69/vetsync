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

                // Update counts
                $("#total-users-count").text(data.total_users.toLocaleString());
                $("#active-users-count").text(
                    data.active_users.toLocaleString()
                );
                $("#new-users-today-count").text(
                    data.new_users_today.toLocaleString()
                );

                // Update progress circles
                updateUserProgressCircle(
                    "total-users-progress",
                    "total-users-percentage",
                    data.total_users_growth
                );
                updateUserProgressCircle(
                    "active-users-progress",
                    "active-users-percentage",
                    data.active_users_growth
                );
                // Special handling for new users today - show "--" when count is 0
                if (data.new_users_today === 0) {
                    // Show "--" instead of percentage when count is 0
                    $("#new-users-today-percentage").text("--");
                    $("#new-users-today-progress").attr(
                        "stroke-dasharray",
                        "0, 100"
                    );
                } else {
                    updateUserProgressCircle(
                        "new-users-today-progress",
                        "new-users-today-percentage",
                        data.new_users_today_growth
                    );
                }
            } else {
                console.error("Failed to load user stats:", response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error loading user stats:", error);
        },
    });
}

function updateUserProgressCircle(progressId, percentageId, value) {
    const absValue = Math.abs(value);
    const cappedValue = Math.min(absValue, 100);

    // Update progress circle
    $(`#${progressId}`).attr("stroke-dasharray", `${cappedValue}, 100`);

    // Update percentage text
    const sign = value > 0 ? "+" : value < 0 ? "" : "+";
    $(`#${percentageId}`).text(`${sign}${value}%`);

    // Change color based on positive/negative/zero
    let color;
    if (value > 0) {
        color = "#20c997"; // Green for positive
    } else if (value < 0) {
        color = "#dc3545"; // Red for negative
    } else {
        color = "#6c757d"; // Gray for zero
    }

    $(`#${progressId}`).attr("stroke", color);
}

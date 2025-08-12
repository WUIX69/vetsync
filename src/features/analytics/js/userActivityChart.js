$(function () {
    // User Activity Chart
    const userActivityCtx = document
        .getElementById("userActivityChart")
        .getContext("2d");
    new Chart(userActivityCtx, {
        type: "bar",
        data: {
            labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
            datasets: [
                {
                    label: "Active Users",
                    data: [1200, 1900, 1500, 2500, 2200, 1800, 1600],
                    backgroundColor: "#6c9bcf",
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    border: {
                        display: false,
                    },
                    grid: {
                        display: false,
                    },
                    ticks: {
                        // color: "#FFFFFF",
                    },
                },
                y: {
                    ticks: {
                        display: false,
                    },
                    border: {
                        display: false,
                    },
                },
            },
            plugins: {
                legend: {
                    display: false,
                },
            },
        },
    });
});

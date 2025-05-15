$(function () {
    const ctx = document.querySelector(".activity-chart");
    const ctx2 = document.querySelector(".prog-chart");

    new Chart(ctx, {
        type: "bar",
        data: {
            labels: ["M", "T", "W", "T", "F", "S", "S"],
            datasets: [
                {
                    label: "Time",
                    data: [8, 6, 7, 6, 10, 8, 4],
                    backgroundColor: "#1e293b",
                    borderWidth: 3,
                    borderRadius: 6,
                    hoverBackgroundColor: "#60a5fa",
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    border: {
                        display: true,
                    },
                    grid: {
                        display: true,
                        color: "#1e293b",
                    },
                },
                y: {
                    ticks: {
                        display: false,
                    },
                },
            },
            plugins: {
                legend: {
                    display: false,
                },
            },
            animation: {
                duration: 1000,
                easing: "easeInOutQuad",
            },
        },
    });

    new Chart(ctx2, {
        type: "bar",
        data: {
            labels: [
                "Vaccination",
                "Checkup",
                "Grooming",
                "Whelping",
                "Accessories",
            ],
            datasets: [
                {
                    label: "Appointments",
                    data: [3, 2, 5, 1, 1],
                    backgroundColor: [
                        "#0891b2",
                        "#ca8a04",
                        "#059669",
                        "#7c3aed",
                        "#db2777",
                    ],
                    borderRadius: 6,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                x: {
                    grid: {
                        display: false,
                    },
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        display: true,
                        stepSize: 1,
                    },
                },
            },
            plugins: {
                legend: {
                    display: false,
                },
            },
            animation: {
                duration: 1000,
                easing: "easeInOutQuad",
            },
        },
    });
});

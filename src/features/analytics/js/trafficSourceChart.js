$(function () {
    // Traffic Sources Chart
    const trafficCtx = document.getElementById("trafficChart").getContext("2d");
    new Chart(trafficCtx, {
        type: "doughnut",
        data: {
            labels: ["Direct", "Social", "Referral", "Organic"],
            datasets: [
                {
                    data: [30, 25, 20, 25],
                    backgroundColor: [
                        "#6c9bcf",
                        "#ff0060",
                        "#00ba88",
                        "#ffb400",
                    ],
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
            },
        },
    });
});

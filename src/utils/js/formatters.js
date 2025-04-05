function dateToMDY(date) {
    const d = new Date(date);
    return `${d.toLocaleString("default", {
        month: "long",
    })} ${d.getDate()}, ${d.getFullYear()}`; // EG: Wed, Dec 12, 2024
}

function timeAgo(date) {
    const providedTime = new Date(date).getTime();
    const now = Date.now();

    const timeDifference = now - providedTime;

    if (timeDifference < 0) {
        return "Time is in the future";
    }

    const seconds = Math.floor(timeDifference / 1000);
    const minutes = Math.floor(seconds / 60);
    const hours = Math.floor(minutes / 60);
    const days = Math.floor(hours / 24);
    const years = Math.floor(days / 365.25);

    // More efficient and readable approach using an object lookup:
    const units = [
        { threshold: 5, text: "Just now" }, // Special case for very recent
        { threshold: 60, unit: "second", value: seconds },
        { threshold: 3600, unit: "minute", value: minutes },
        { threshold: 86400, unit: "hour", value: hours },
        { threshold: 31536000, unit: "day", value: days },
        { threshold: Infinity, unit: "year", value: years }, // Default case
    ];

    for (const unit of units) {
        if (seconds < unit.threshold) {
            return (
                unit.text ||
                `${unit.value} ${unit.unit}${unit.value > 1 ? "s" : ""} ago`
            );
        }
    }

    return null;
}

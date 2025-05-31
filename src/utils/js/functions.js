/**
 * Generates a base URL or a full URL by appending a path.
 *
 * @param {string} path - Optional. The path to append to the base URL.
 * @returns {string} The base URL or the full URL.
 */
function BASE_URL(path = "") {
    // Get the protocol and host from the current window's location
    // We don't need a static variable like in PHP because window.location properties
    // are readily available and efficient to access.
    const protocol = window.location.protocol; // e.g., "http:" or "https:"
    const host = window.location.host; // e.g., "www.example.com" or "localhost:8080"

    // Construct the base URL
    const baseUrl = `${protocol}//${host}/`;

    // Return base URL if no path provided
    if (path === "") {
        return baseUrl;
    }

    // Append the path, ensuring it doesn't start with a leading slash if baseUrl already ends with one
    // and path doesn't start with one. The `new URL()` constructor is a robust way to handle this.
    try {
        const fullUrl = new URL(path, baseUrl);
        return fullUrl.href;
    } catch (error) {
        console.error("Invalid URL path provided:", error);
        return baseUrl + path.replace(/^\//, ""); // Fallback: remove leading slash if present
    }
}

// API URL Helper
function apiUrl(feature = null) {
    return `${BASE_URL()}src/features/${feature}/api/`;
}

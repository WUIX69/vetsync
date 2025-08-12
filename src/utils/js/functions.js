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

function urlFileHelper(dir, file, is_public = false) {
    // Define the directory where your source files are located
    const dir_path = is_public ? dir : "src/" + dir;
    const url = dir_path + "/" + file.replace(/^\//, "");

    // Check if the file exists
    const checkUrlPath = BASE_URL() + url;
    if (checkUrlPath) {
        return BASE_URL(url); // Return the URL of the source file
    }

    return "";
}

function asset(file = "") {
    return urlFileHelper("public", file, true);
}

function app($link = "") {
    // Handle empty link case
    if (link === "") {
        return urlFileHelper("app", "landing");
    }

    // Check if the link ends with a trailing slash (indicating a directory)
    if (link.endsWith("/")) {
        url = link + "index.php";
    }
    // Check if the link has no slashes (just a directory name)
    else if (link.indexOf("/") === -1) {
        url = link + "/index.php";
    }
    // Handle paths with subdirectories
    else {
        // Check if the path contains a file extension
        if (link.match(/\.[a-zA-Z0-9]+$/)) {
            // If it already has an extension, use it as is
            url = link;
        } else {
            // No extension, so add .php
            url = link + ".php";
        }
    }

    // Ensure we have the .php extension
    if (url.endsWith(".php") === false) {
        url += ".php";
    }

    return urlFileHelper("app", url);
}

/**
 * Returns the API base URL for a given feature or for shared APIs.
 *
 * @param {string|null} feature - The feature name (e.g., "products"), or "shared" for shared APIs.
 * @returns {string} The full API base URL for the specified feature.
 */
function apiUrl(feature = null) {
    let url = "";

    if (feature === "shared" || feature === null) {
        url = BASE_URL() + "src/shared/api/";
    } else {
        url = BASE_URL() + "src/features/" + feature + "/api/";
    }

    return url;
}

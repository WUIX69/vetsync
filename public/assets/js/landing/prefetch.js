function shouldPrefetch() {
    if (!navigator.connection) return true;
    const slowConnections = ["slow-2g", "2g"];
    return !slowConnections.includes(navigator.connection.effectiveType);
}

function getCurrentPageUrl() {
    const fullPath = window.location.pathname;
    const page = fullPath.split("/").pop() || "index.html";
    const pageWoutExt = page.replace(".html", "");
    return pageWoutExt;
}

function appendLink(url, asType = "document") {
    $("<link>", {
        rel: "prefetch",
        href: url,
        as: asType,
    }).appendTo("head");
}

function all(currentPage, asType = "document") {
    if (!shouldPrefetch() || !currentPage) return;

    const landingPages = [
        "index",
        "products",
        "services",
        "contact-us",
        "about-us",
    ];

    const pagesToPrefetch = landingPages.filter((page) => page !== currentPage); // Skip current page from prefetching
    pagesToPrefetch.forEach((page) => {
        try {
            const url = `${page}.html`;
            // Method 1: Use fetch with no-store
            fetch(url, { cache: "no-store" })
                .then(() => console.log(`Prefetched ${url}`))
                .catch((err) =>
                    console.warn(`Failed to prefetch ${url}:`, err)
                );
            // Method 2: Still add link element as backup
            appendLink(url, "document");
        } catch (error) {
            console.warn(`Failed to prefetch ${page}:`, error);
        }
    });
}

function single(currentPageUrl, asType = "document") {
    if (!shouldPrefetch() || !currentPageUrl) return;

    try {
        appendLink(currentPageUrl, asType);
    } catch (error) {
        console.warn(`Failed to prefetch ${currentPageUrl}:`, error);
    }
}

$(function () {
    const currentPage = getCurrentPageUrl();
    all(currentPage);
});

//Example of prefetching a resource based on a click.
// function prefetchOnclick(button, url, asType) {
//     button.addEventListener("click", () => {
//         single(url, asType);
//     });
// }

//Example usage.
// const product1Button = document.getElementById("product1Button");
// if (product1Button) {
//     prefetchOnclick(product1Button, "/product1.html", "document");
// }

// const navigationLinks = document.querySelectorAll("nav a"); // Assuming your navigation is in a <nav>

// navigationLinks.forEach((link) => {
//     link.addEventListener("mouseover", () => {
//         const href = link.getAttribute("href");
//         if (href) {
//             dynamicPrefetch(href);
//         }
//     });
// });

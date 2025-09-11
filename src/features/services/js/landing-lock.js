// Force unauthenticated users on the landing page to login when clicking service actions
$(document).on("click", ".book-now-btn, .service-view-btn", function (e) {
    e.preventDefault();
    window.location.href = "/src/app/auth/index.php";
});

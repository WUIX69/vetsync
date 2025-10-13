// Force unauthenticated users on the landing page to login when clicking product actions
$(document).on("click", ".learnmore-btn, .add-to-cart-btn", function (e) {
    e.preventDefault();
    window.location.href = "/src/app/auth/index.php";
});

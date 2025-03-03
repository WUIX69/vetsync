const $body = $("html, body");

$(function () {
    // Handle body scroll effect
    $(window).on("scroll", function () {
        if ($(window).scrollTop() > 50) {
            $body.addClass("scrolled");
        } else {
            $body.removeClass("scrolled");
        }
    });

    // Handle top redirect button
    const $topRedirectButton = $(".top-redirect-button button");
    $topRedirectButton.on("click", function () {
        $body.animate({ scrollTop: 0 }, "slow");
    });

    // Handle mobile menu
    const $mobileMenuBtn = $(".mobile-menu-btn");
    const $navLinks = $body.find("header nav .nav-links");
    $mobileMenuBtn.on("click", function () {
        $navLinks.toggleClass("active");
    });
});

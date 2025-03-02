$(function () {
    // Handle navbar scroll effect
    $(window).on("scroll", function () {
        const $navbar = $(".navbar");
        if ($(window).scrollTop() > 50) {
            $navbar.addClass("scrolled");
        } else {
            $navbar.removeClass("scrolled");
        }
    });

    // Handle mobile menu
    const $mobileMenuBtn = $(".mobile-menu-btn");
    const $navLinks = $(".nav-links");

    $mobileMenuBtn.on("click", function () {
        $navLinks.toggleClass("active");
    });
});

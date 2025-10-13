const $body = $("html body");
const $header = $("site-header");

$(function () {
    // Handle mobile menu
    const $mobileMenuBtn = $header.find(".mobile-menu-btn");
    const $navLinks = $header.find("nav .nav-links");
    $mobileMenuBtn.on("click", function () {
        $navLinks.toggleClass("active");
    });
});

// Mobile hamburger menu functionality
document.addEventListener("DOMContentLoaded", function () {
    // Only add mobile menu on screens < 768px
    if (window.innerWidth <= 768) {
        addMobileMenuToggle();
    }

    window.addEventListener("resize", function () {
        if (
            window.innerWidth <= 768 &&
            !document.querySelector(".mobile-menu-toggle")
        ) {
            addMobileMenuToggle();
        } else if (window.innerWidth > 768) {
            const toggle = document.querySelector(".mobile-menu-toggle");
            if (toggle) toggle.remove();
        }
    });
});

function addMobileMenuToggle() {
    const nav = document.querySelector("header.site-header .nav");
    if (!nav || document.querySelector(".mobile-menu-toggle")) return;

    const toggle = document.createElement("button");
    toggle.className = "mobile-menu-toggle";
    toggle.innerHTML = '<i class="bx bx-menu"></i>';
    toggle.style.cssText = `
        display: none;
        position: absolute;
        top: 15px;
        left: 15px;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        font-size: 24px;
        padding: 8px 12px;
        border-radius: 5px;
        cursor: pointer;
        z-index: 1000;
    `;

    const navLinks = document.querySelector(".nav-links");
    if (navLinks) {
        nav.insertBefore(toggle, nav.firstChild);

        toggle.addEventListener("click", function () {
            navLinks.classList.toggle("mobile-menu-open");
            this.innerHTML = navLinks.classList.contains("mobile-menu-open")
                ? '<i class="bx bx-x"></i>'
                : '<i class="bx bx-menu"></i>';
        });

        // Add CSS for mobile menu
        const style = document.createElement("style");
        style.textContent = `
            @media screen and (max-width: 768px) {
                .mobile-menu-toggle {
                    display: block !important;
                }

                .nav-links {
                    position: fixed;
                    top: 60px;
                    left: -100%;
                    width: 250px;
                    background: #006a71;
                    flex-direction: column;
                    padding: 20px;
                    transition: left 0.3s ease;
                    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
                    height: calc(100vh - 60px);
                    z-index: 999;
                }

                .nav-links.mobile-menu-open {
                    left: 0;
                }

                .nav-links a {
                    padding: 10px 15px;
                    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                }
            }
        `;
        document.head.appendChild(style);
    }
}

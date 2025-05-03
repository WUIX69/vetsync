function initDropdowns() {
    $(".ui.dropdown").dropdown({
        allowAdditions: false,
        forceSelection: true,
        autofocus: false,
        // clearable: false,
    });
}

function initModal(options = {}) {
    const defaults = {
        modal_id: ".ui.modal",
        transition: "fly down",
        duration: 700,
    };

    const config = { ...defaults, ...options };
    const $modal = $(config.modal_id);
    const $form = $($modal.find("form")) ?? null;
    const $submitBtn = $modal.find("button[type='submit']") ?? null;

    if (!$modal || !$modal.length) return false;
    // if ($modal) $modal.modal("destroy");

    $modal.modal({
        blurring: false,
        closable: true,
        transition: config.transition,
        duration: config.duration,
        autofocus: false,
        onShow: () => {
            $modal.find(".ui.dropdown").dropdown();
        },
        onHide: () => {
            // $form[0].reset();
            $form.form("clear");
            $submitBtn.removeClass("loading");
        },
        onApprove: () => {
            if (!$form.form("is valid")) return false; //: Optional
            return false;
        },
    });
}

function initAccordion() {
    $(".ui.accordion").accordion();
}

$(function () {
    // Initialize core components
    if ($(".ui.dropdown").length) initDropdowns();
    if ($(".ui.modal").length) initModal();
    if ($(".ui.accordion").length) initAccordion();

    // Modal opening via data attribute
    $("body").on("click", "[data-open-modal]", function () {
        const modalId = $(this).data("open-modal");
        if (modalId) $(modalId).modal("show");
    });

    // Handle body scroll effect
    $(window).on("scroll", function () {
        if ($(window).scrollTop() > 50) {
            $("body").addClass("scrolled");
        } else {
            $("body").removeClass("scrolled");
        }
    });

    // Handle top redirect button
    $("body").on("click", ".top-redirect-button button", function () {
        $("html, body").animate({ scrollTop: 0 }, "slow");
    });
});

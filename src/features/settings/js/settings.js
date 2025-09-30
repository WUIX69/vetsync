// Add this function to show terms in a modal
function showTermsModal() {
    // Load terms content via AJAX
    $.get("/src/features/settings/components/terms-agreement.php")
        .done(function (termsContent) {
            const modalHtml = `
                <div class="ui large modal" id="termsModal">
                    <div class="header">
                        <i class="file text icon"></i>
                        Terms and Agreement
                    </div>
                    <div class="content">
                        <div style="max-height: 60vh; overflow-y: auto;">
                            ${termsContent}
                        </div>
                    </div>
                    <div class="actions">
                        <div class="ui approve button">
                            <i class="checkmark icon"></i>
                            I Agree
                        </div>
                        <div class="ui cancel button">
                            Close
                        </div>
                    </div>
                </div>
            `;

            // Add modal to body
            $("body").append(modalHtml);

            // Show modal
            $("#termsModal")
                .modal({
                    onApprove: function () {
                        console.log("User agreed to terms");
                        return true;
                    },
                    onHidden: function () {
                        $("#termsModal").remove();
                    },
                })
                .modal("show");
        })
        .fail(function () {
            alert("Error loading terms and agreement");
        });
}

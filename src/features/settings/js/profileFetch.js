function getProfile() {
    $.ajax({
        url: apiUrl("settings") + "profileGet.php",
        method: "GET",
        data: {},
        dataType: "json",
        timeout: 5000,
        beforeSend: function () {
            // Code here...
        },
        success: function (response) {
            // console.log(response);
            // alert(response.message);
            // return false;

            $.each(response.data, function (name, value) {
                if (name == "urls") {
                    $.each(value, function (index, url) {
                        $($('input[name="urls[]"]')[index]).val(url);
                    });
                } else {
                    // Populate normal input fields
                    $(`[name="${name}"]`).val(value);
                }
            });
        },
        complete: function () {
            // Code here...
        },
        error: ajaxErrorHandler,
    });
}
$(function () {
    getProfile();
});

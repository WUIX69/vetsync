$(function () {
    const profilePond = FilePond.create(
        document.querySelector(".profile-pond"),
        {
            maxFiles: 1,
            maxFileSize: "2MB",
            instantUpload: false,
            allowMultiple: false,
            allowFileTypes: ["image/*"],

            labelIdle: `Drag & Drop your picture or <span class="filepond--label-action">Browse</span>`,

            imagePreviewHeight: 170,
            imageCropAspectRatio: "1:1",
            imageResizeTargetWidth: 200,
            imageResizeTargetHeight: 200,

            stylePanelLayout: "compact circle",
            styleLoadIndicatorPosition: "center bottom",
            styleProgressIndicatorPosition: "right bottom",
            styleButtonRemoveItemPosition: "left bottom",
            styleButtonProcessItemPosition: "right bottom",
        }
    );

    // Set server configuration
    profilePond.setOptions({
        server: {
            url: apiUrl("settings") + "profilePost.php",
            headers: {},
            timeout: 7000,
            withCredentials: false,
            credit: false,
            process: {
                url: "/",
                method: "POST",
                ondata: function (formData) {
                    formData.append("action", "profile-upload");
                    return formData;
                },
                onload: (jsonResponse) => {
                    const response = JSON.parse(jsonResponse);
                    alert(response.message);
                    // return false;

                    profilePond.removeFiles();

                    // Update all visible img src that uses profile photo with fade animation
                    $("img.user-profile-photo").transition(
                        "fade out",
                        300,
                        function () {
                            $(this)
                                .attr("src", response.data.profile_url)
                                .transition("fade in", 300);
                        }
                    );
                },
            },
        },
    });
});

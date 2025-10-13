function getProfile() {
    console.log("Fetching profile data...");

    $.ajax({
        url: apiUrl("settings") + "profileGet.php?t=" + Date.now(),
        method: "GET",
        dataType: "json",
        success: function (response) {
            console.log("Profile fetch response:", response);

            if (response && response.success && response.data) {
                // Handle profile image first
                if (response.data.profile && response.data.profile.url) {
                    console.log(
                        "Loading profile image:",
                        response.data.profile.url
                    );

                    // Add cache busting to image URL
                    const imageUrl =
                        response.data.profile.url + "?t=" + Date.now();

                    // Update profile image in settings with fade effect
                    $("img.user-profile-photo").each(function () {
                        const $img = $(this);
                        $img.fadeOut(200, function () {
                            $img.attr("src", imageUrl).fadeIn(200);
                        });
                    });

                    // Update any other profile images
                    $("img[src*='profiles/']").each(function () {
                        const $img = $(this);
                        if (!$img.hasClass("user-profile-photo")) {
                            $img.attr("src", imageUrl);
                        }
                    });

                    // Handle FilePond loading - IMPROVED VERSION with better error handling
                    if (window.profilePond && response.data.profile.folder) {
                        // Clear existing files first
                        profilePond.removeFiles();

                        // Create a proper URL for FilePond to load
                        const filepond_url = response.data.profile.url; // Use the full URL instead

                        // Test if the image actually exists before trying to load it
                        const testImg = new Image();
                        testImg.onload = function () {
                            // Image exists, try to load it into FilePond
                            profilePond
                                .addFile(filepond_url)
                                .then(function (file) {
                                    console.log(
                                        "Profile image loaded into FilePond:",
                                        file
                                    );
                                })
                                .catch(function (error) {
                                    console.log(
                                        "Could not load existing image into FilePond:",
                                        error
                                    );
                                    // If loading fails, just clear FilePond
                                    profilePond.removeFiles();
                                });
                        };
                        testImg.onerror = function () {
                            console.log(
                                "Image doesn't exist, keeping FilePond empty"
                            );
                            profilePond.removeFiles();
                        };
                        testImg.src = filepond_url;
                    }
                } else {
                    console.log("No profile image found, using placeholder");

                    // Clear FilePond if no profile image
                    if (window.profilePond) {
                        profilePond.removeFiles();
                    }

                    // Use default avatar
                    $("img.user-profile-photo").attr(
                        "src",
                        "/public/img/placeholders/image.png"
                    );
                }

                // Handle text fields
                $.each(response.data, function (name, value) {
                    if (name === "profile") return; // Skip profile object

                    console.log(`Setting field ${name} to:`, value);
                    $(`[name="${name}"]`).val(value || "");
                });
            } else {
                console.error("Profile fetch failed:", response);

                // Show error notification
                $("body").toast({
                    title: "Error!",
                    message: "Failed to load profile data",
                    class: "error",
                    displayTime: 3000,
                    position: "top right",
                });
            }

            console.log("Profile fetch completed");
        },
        error: function (xhr, status, error) {
            console.error("Profile fetch error:", {
                status: status,
                error: error,
                responseText: xhr.responseText,
            });

            // Show error notification
            $("body").toast({
                title: "Error!",
                message: "Failed to load profile data",
                class: "error",
                displayTime: 3000,
                position: "top right",
            });
        },
    });
}

$(document).ready(function () {
    getProfile();
});

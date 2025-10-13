const profilePond = FilePond.create(document.querySelector(".profile-pond"), {
    maxFiles: 1,
    maxFileSize: "4MB", // Changed from "2MB" to "4MB"
    instantUpload: false,
    allowMultiple: false,
    allowFileTypes: ["image/*"],

    labelIdle: `Drag & Drop your picture (max 4MB) or <span class="filepond--label-action">Browse</span>`,

    imagePreviewHeight: 170,
    imageCropAspectRatio: "1:1",
    imageResizeTargetWidth: 200,
    imageResizeTargetHeight: 200,

    stylePanelLayout: "compact circle",
    styleLoadIndicatorPosition: "center bottom",
    styleProgressIndicatorPosition: "right bottom",
    styleButtonRemoveItemPosition: "left bottom",
    styleButtonProcessItemPosition: "right bottom",

    onremovefile: function (error, file) {
        if (file.origin === 3) {
            console.log("Removing local file");
            $.ajax({
                url: apiUrl("shared") + "filepond.php",
                headers: {
                    "X-Reference-Model": "profiles",
                },
                method: "DELETE",
                data: file.serverId,
                processData: false,
                contentType: false,
                success: function (response) {
                    console.log("File removed successfully");
                },
                error: function (xhr, status, error) {
                    console.error("Error removing file:", error);
                },
            });
        }
    },

    onaddfile: function (error, file) {
        if (error) {
            console.error("Error adding file:", error);
        } else {
            console.log("File added to FilePond:", file);
        }
    },

    onprocessfile: function (error, file) {
        if (error) {
            console.error("Error processing file:", error);
        } else {
            console.log("File processed successfully:", file);

            // Auto-refresh profile data after successful upload
            setTimeout(function () {
                if (typeof getProfile === "function") {
                    getProfile();
                }
            }, 1500);
        }
    },
});

// Set server configuration
profilePond.setOptions({
    server: {
        headers: {
            "X-Reference-Model": "profiles",
        },
        timeout: 10000, // Increased timeout
        withCredentials: false,
        process: {
            url: apiUrl("settings") + "profilePost.php",
            method: "POST",
            ondata: function (formData) {
                formData.append("action", "profile-upload");
                console.log(
                    "Sending upload request with action: profile-upload"
                );
                console.log(
                    "FormData contents:",
                    Array.from(formData.entries())
                );
                return formData;
            },
            onload: (response) => {
                console.log("Raw upload response:", response);

                // Check if response indicates success (folder name returned)
                if (
                    response &&
                    response.trim() !== "" &&
                    !response.toLowerCase().includes("failed") &&
                    !response.toLowerCase().includes("error") &&
                    !response.toLowerCase().includes("invalid")
                ) {
                    console.log("Upload successful, folder:", response.trim());

                    // Show success notification
                    $("body").toast({
                        title: "Success!",
                        message: "Profile image uploaded successfully!",
                        class: "success",
                        displayTime: 3000,
                        position: "top right",
                    });

                    return response.trim(); // Return folder name for FilePond
                } else {
                    console.error("Upload failed:", response);
                    // Show error notification
                    $("body").toast({
                        title: "Upload Failed!",
                        message: "Failed to upload image: " + response,
                        class: "error",
                        displayTime: 5000,
                        position: "top right",
                    });
                    return false;
                }
            },
            onerror: (response) => {
                console.error("Upload error:", response);
                $("body").toast({
                    title: "Upload Failed!",
                    message: "Network error occurred. Please try again.",
                    class: "error",
                    displayTime: 5000,
                    position: "top right",
                });
            },
        },
        revert: {
            url: apiUrl("shared") + "filepond.php",
        },
        load: {
            url: apiUrl("shared") + "filepond.php?folder=",
        },
    },
});

// Export profilePond to window for debugging
window.profilePond = profilePond;

console.log("ProfileUpload.js loaded, FilePond initialized");

$(function () {
    // Use filepond for instance and uploading the image
    FilePond.registerPlugin(
        FilePondPluginFileEncode,
        FilePondPluginFileValidateType,
        FilePondPluginFileValidateSize,
        FilePondPluginImageExifOrientation,
        FilePondPluginImagePreview
    );

    const profilePond = FilePond.create(
        document.querySelector(".profile-pond"),
        {
            maxFiles: 1,
            maxFileSize: "2MB",
            // instantUpload: false,
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
});
// Filepond

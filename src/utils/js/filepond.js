function filePondPlugin() {
    // Use filepond for instance and uploading the image
    FilePond.registerPlugin(
        FilePondPluginFileEncode,
        FilePondPluginFileValidateType,
        FilePondPluginFileValidateSize,
        FilePondPluginImageExifOrientation,
        FilePondPluginImagePreview
    );
}

$(function () {
    const isFilePond = $("input.filepond").length;
    if (isFilePond) {
        console.log("Filepond found");
        filePondPlugin();
    }
});

Dropzone.autoDiscover = false;
$(function () {
    $(document.body).dropzone({
        url: window.api_upload_url,
        previewsContainer: "#previews",
        clickable: "#upload-button",
        maxFilesize: window.max_file_size,
        params: {'key': window.api_key},
        init: function () {
            this.on("success", function (file, responseText) {
                console.log(responseText);
                $(file.previewTemplate).append($('<a>', {
                    'href': responseText.url,
                    html: responseText.url
                }))
            }).on("addedfile", function (file) {
                if (!file.type.match(/image.*/)) {
                    this.emit("thumbnail", file, window.dropzone_thumbnail);
                }
            })
        }
    })
});

Dropzone.autoDiscover = false;
$(function () {
    $(document.body).dropzone({
        url: "/api/upload",
        previewsContainer: "#previews",
        clickable: ".upload-button",
        params: {'key': window.api_key},
        init: function () {
            this.on("success", function (file, responseText) {
                console.log(responseText);
                $(file.previewTemplate).append($('<a>', {
                    'href': responseText.url,
                    html: responseText.url
                }));
            })
        }
    });
});

/*
 * Image preview script
 * powered by jQuery (http://www.jquery.com)
 *
 * written by Alen Grakalic (http://cssglobe.com)
 *
 * for more info visit http://cssglobe.com/post/1695/easiest-tooltip-and-image-preview-using-jquery
 *
 */

// https://stackoverflow.com/questions/16289159/how-to-show-image-preview-on-thumbnail-hover

this.imagePreview = function () {

    var xOffset = 15;
    var yOffset = 30;

    var Mx = $(document).width();
    var My = $(document).height();

    var callback = function (event) {
        var $img = $("#preview");

        var trc_x = xOffset + $img.width();
        var trc_y = yOffset + $img.height();

        trc_x = Math.min(trc_x + event.pageX, Mx);
        trc_y = Math.min(trc_y + event.pageY, My);

        $img.css("top", (trc_y - $img.height()) + "px")
            .css("left", (trc_x - $img.width()) + "px")
            .css("z-index", 1000);
    };

    $("a.preview").hover(function (e) {
        Mx = $(document).width();
        My = $(document).height();

        this.t = this.title;
        this.title = "";
        var c = (this.t != "") ? "<br/>" + this.t : "";
        $("body").append("<p id='preview'><img src='" + this.href + "' alt='Preview' />" + c + "</p>");
        callback(e);
        $("#preview").fadeIn("fast");
    },
    function () {
        this.title = this.t;
        $("#preview").remove();
    }).mousemove(callback);
};

$(document).ready(function () {
    imagePreview();
});
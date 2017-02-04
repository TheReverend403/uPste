$(function () {
    $('#flash-overlay-modal').modal();
    $('div.alert').not('.alert-important').delay(3000).slideUp();
    $('form[confirm]').on('submit', function(){
        return window.confirm(this.getAttribute('confirm'));
    });
});

var $ = require('jquery');

require('bootstrap-sass');

$(document).on("click", ".open-shareCookie", function () {
    var cookieName = $(this).data('name');
    var cookieId = $(this).data('id');
    $(".modal-body #cookieName").html(cookieName);
    $(".modal-body #cookieId").val(cookieId);
    $('#myModal').modal();
});
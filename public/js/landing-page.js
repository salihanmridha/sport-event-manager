$(document).ready(function () {
    $("#session-link-app").mouseenter(function () {
        console.log("1");
        $("#full-landing-page").addClass("black-all");
    });
    $("#session-link-app").mouseleave(function () {
        $("#full-landing-page").removeClass("black-all");
    });
    $(".session-header").mouseenter(function () {
        console.log("1");
        $("#full-landing-page").addClass("black-all");
    });
    $(".session-header").mouseleave(function () {
        $("#full-landing-page").removeClass("black-all");
    });
});

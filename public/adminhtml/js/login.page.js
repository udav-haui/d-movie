function loginWithSocial(provider) {
    window.location.replace('redirect/' + provider);
}
/* ===== Login and Recover Password ===== */

$(document).on("click", "#to-login", function () {
    $("#loginform").slideDown();
    $("#recoverform").fadeOut();
});

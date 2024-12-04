$(document).ready(function() {

    // --------------------------------
    // ------------------------CHAT.PHP
    // --------------------------------
    var refreshBTN = $('#refreshBTN');

    $(refreshBTN).on('click', function(e) {
        e.preventDefault();
        window.location.reload()
    });

    // ----------------------------------
    // ------------------------MY_ACCOUNT
    // ----------------------------------
    $('#password-checkbox').on('click', function() {
        $('#password-confirm').slideToggle();
    });

    // -------------------------------------------------
    // ------------------------Registration & MY_ACCOUNT
    // -------------------------------------------------
    $('#no-verif-password').on('click', function() {
        if ($(this).is(':checked')) {
            $('#password1').removeAttr('pattern');
            $('#password1').removeAttr('title');
            $('#password2').removeAttr('pattern');
            $('#password2').removeAttr('title');
        } else {
            $('#password1').attr('pattern', '(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}');
            $('#password1').attr('title', 'Au moins 8 caractères dont au moins un chiffre, une minuscule et une majuscule');
            $('#password2').attr('pattern', '(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}');
            $('#password2').attr('title', 'Au moins 8 caractères dont au moins un chiffre, une minuscule et une majuscule');
        }
    });

});
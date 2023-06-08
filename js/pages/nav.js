import {
    getCookie
} from "../others/cookie.js";

const token = getCookie('token');
if (token != '' && token != null) {
    $.ajax({
        type: "POST",
        url: "php/auth.php",
        dataType: "json",
        data: {
            token: token
        },
        success: function(response) {
            if (response.auth == true) {
                console.log('logado');
                //usuario logado
                $('#userInfoBox').show();
                $('#loginBtn').hide();
            } else {
                //usuario nao logado
            }
        }
    });
}

/* ------------------------------- */

$('#switchTheme').click(function() {
    if ($('html').attr('data-bs-theme') == 'dark') {
        $('html').attr('data-bs-theme', 'light');
        $('#switchThemeIcon').removeClass('fa-sun').addClass('fa-moon');
    } else {
        $('html').attr('data-bs-theme', 'dark');
        $('#switchThemeIcon').removeClass('fa-moon').addClass('fa-sun');
    }
});

$('#loginForm').submit(function() {
    var user = $('input[name="user"]');
    var pass = $('input[name="pass"]');

    /* COMUNICAÇÃO COM BACKEND */
    $.ajax({
        type: "POST",
        url: "php/login.php",
        dataType: "json",
        data: {
            user: user.val(),
            pass: pass.val()
        },
        success: function(response) {
            if (response.auth == true) {
                document.cookie = "token="+response.token;
                user.addClass('is-valid').removeClass('is-invalid');
                pass.addClass('is-valid').removeClass('is-invalid');
            } else {
                user.addClass('is-invalid').removeClass('is-valid');
                pass.addClass('is-invalid').removeClass('is-valid');
            }
        }
    });
    return false;
});
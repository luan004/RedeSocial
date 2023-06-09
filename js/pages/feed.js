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
                $('#loginBox').hide();
                $('#userBox').show();

                $.ajax({
                    type: "POST",
                    url: "php/getUserInfo.php",
                    dataType: "json",
                    data: {
                        id: response.id
                    },
                    success: function(response) {
                        $('#userBoxAvatar').attr('src', response.avatar);
                        $('#userBoxName').html(response.name);
                        $('#userBoxUsername').html('@'+response.user);

                        /* Caixa de postagem */
                        $('#postBoxUser').html('@'+response.user);
                        $('#postBoxAvatar').attr('src', response.avatar);
                    }
                });
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
                window.location.reload();
            } else {
                user.addClass('is-invalid').removeClass('is-valid');
                pass.addClass('is-invalid').removeClass('is-valid');
            }
        }
    });
    return false;
});

$('#userBoxLogout').click(function(e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "php/logout.php",
        dataType: "json",
        data: {
            token: token
        },
        success: function(response) {
            document.cookie = "token=; expires=Thu, 01 Jan 1970 00:00:00 UTC;";
            window.location.reload();
        }
    });
});
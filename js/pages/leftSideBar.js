import {
    getCookie,
    logout
} from "../others/functions.js";

const token = getCookie('token');
if (token != '' && token != null) {
    $('#loginBox').hide();
    $('#userBox').show();

    $.ajax({
        type: "POST",
        url: "php/getUserInfo.php",
        dataType: "json",
        data: {
            token: token
        },
        success: function(response) {
            if (response.auth == true) {
                console.log(response.name);
                $('#userBoxAvatar').attr('src', response.avatar);
                $('#userBoxName').html(response.name);
                $('#userBoxUsername').html('@'+response.user);
    
                /* Caixa de postagem */
                $('#postBoxUser').html('@'+response.user);
                $('#postBoxAvatar').attr('src', response.avatar);
            } else {
                logout(token);
            }
        }
    });
}
const theme = getCookie('theme');
if (theme == 'light') {
    $('html').attr('data-bs-theme', 'light');
    $('#switchThemeIcon').addClass('fa-moon');
    $('#switchThemeText').html('Tema Escuro');
} else {
    $('html').attr('data-bs-theme', 'dark');
    $('#switchThemeIcon').addClass('fa-sun');
    $('#switchThemeText').html('Tema Claro');
}

/* ------------------------------------------------ */
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
$('#switchTheme').click(function() {
    if ($('html').attr('data-bs-theme') == 'dark') {
        $('html').attr('data-bs-theme', 'light');
        $('#switchThemeIcon').removeClass('fa-sun').addClass('fa-moon');
        $('#switchThemeText').html('Tema Escuro');
        document.cookie = "theme=light";
    } else {
        $('html').attr('data-bs-theme', 'dark');
        $('#switchThemeIcon').removeClass('fa-moon').addClass('fa-sun');
        $('#switchThemeText').html('Tema Claro');
        document.cookie = "theme=dark";
    }
});
$('#userBoxLogout').click(function(e) {
    e.preventDefault();
    logout(token);
});
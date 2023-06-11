import {
    getCookie,
    logout
} from "../utils.js";

var page = window.location.pathname;
var tab;
switch (true) {
  case page.includes('/explore'):
    tab = 'explore';
    break;
  case page.includes('/feed'):
    tab = 'feed';
    break;
  case page.includes('/settings'):
    tab = 'settings';
    break;
}

if (tab) {
  document.getElementById(tab).classList.add('active');
}

/* ------------------------------------------------ */

const token = getCookie('token');
if (token != null) {
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
                /* Caixa do usuário sidebar esquerda */
                $('#userBoxAvatar').attr('src', response.avatar);
                $('#userBoxName').html(response.name);
                $('#userBoxUsername').html('@'+response.user);

                /* Abas disponiveis apenas a usuarios logados */
                $('#settings').show();	
                $('#feed').show();
    
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
/* Data de Expiração do Cookie */
var dataAtual = new Date();
dataAtual.setFullYear(dataAtual.getFullYear() + 1);
var dataExpiracao = dataAtual.toUTCString();

$('#loginForm').submit(function() {
    const user = $('#loginUser');
    const pass = $('#loginPass');

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
                document.cookie = "token="+response.token + ";expires=" + dataExpiracao + ";path=/";
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
$('#registerForm').submit(function() {
    const name = $('#registerName');
    const user = $('#registerUser');
    const pass1 = $('#registerPass1');
    const pass2 = $('#registerPass2');

    /* COMUNICAÇÃO COM BACKEND */
    $.ajax({
        type: "POST",
        url: "php/register.php",
        dataType: "json",
        data: {
            name: name.val(),
            user: user.val(),
            pass1: pass1.val(),
            pass2: pass2.val()
        },
        success: function(response) {
            if (response.register == true) {
                $.ajax({
                    type: "POST",
                    url: "php/login.php",
                    dataType: "json",
                    data: {
                        user: user.val(),
                        pass: pass1.val()
                    },
                    success: function(response) {
                        if (response.auth == true) {
                            document.cookie = "token="+response.token + ";expires=" + dataExpiracao + ";path=/";
                            window.location.reload();
                        }
                    }
                });
            } else {
                $('#nameInvalid').hide();
                $('#userInvalid').hide();
                $('#passInvalid').hide();
                $('#passIsNotTheSame').hide();
                $('#userAlreadyExists').hide();

                $('#'+response.error).show();
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
        document.cookie = "theme=light"+ ";expires=" + dataExpiracao + ";path=/";
    } else {
        $('html').attr('data-bs-theme', 'dark');
        $('#switchThemeIcon').removeClass('fa-moon').addClass('fa-sun');
        $('#switchThemeText').html('Tema Claro');
        document.cookie = "theme=dark"+ ";expires=" + dataExpiracao + ";path=/";
    }
});
$('#userBoxLogout').click(function(e) {
    e.preventDefault();
    logout(token);
});
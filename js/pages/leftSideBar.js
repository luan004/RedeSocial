import {
    getCookie,
    sidebarTabs,
    loadTheme,
    cookieExpire
} from "../utils.js";

import {
    auth,
    logout
} from "../data.js";

loadTheme();
sidebarTabs();

/* AUTH */
const token = getCookie('token');
auth(function(id) {
    $('#loginBox').hide();
    $('#userBox').show();

    console.log(id);
    $.ajax({
        type: "POST",
        url: "php/api/getUser.php",
        dataType: "json",
        data: {
            opt: 'id',
            val: id
        },
        success: function(response) {
            $('#userBoxAvatar').attr('src', response.avatar);
            $('#userBoxName').html(response.name);
            $('#userBoxUsername').html('@'+response.user);
            $('#userBoxAvatarLink').attr('href', 'profile?u='+response.user);
            $('#userBoxLink').attr('href', 'profile?u='+response.user);
    
            $('#settings').show();	
            $('#feed').show();
    
            $('#postBoxUser').html('@'+response.user);
            $('#postBoxAvatar').attr('src', response.avatar);
        }
    });
}, function() {
    logout();
}, token);

/* LOGIN ACCOUNT FORM */
$('#loginForm').submit(function() {
    const user = $('#loginUser');
    const pass = $('#loginPass');

    $.ajax({
        type: "POST",
        url: "php/api/login.php",
        dataType: "json",
        data: {
            user: user.val(),
            pass: pass.val()
        },
        success: function(response) {
            if (response.auth == true) {
                document.cookie = "token="+response.token + ";expires=" + cookieExpire() + ";path=/";
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
/* REGISTER ACCOUNT FORM */
$('#registerForm').submit(function() {
    const name = $('#registerName');
    const user = $('#registerUser');
    const pass1 = $('#registerPass1');
    const pass2 = $('#registerPass2');

    $.ajax({
        type: "POST",
        url: "php/api/register.php",
        dataType: "json",
        data: {
            name: name.val(),
            user: user.val(),
            pass: pass1.val()
        },
        success: function(response) {
            if (response.register == true) {
                $.ajax({
                    type: "POST",
                    url: ".php/api/login.php",
                    dataType: "json",
                    data: {
                        user: user.val(),
                        pass: pass1.val()
                    },
                    success: function(response) {
                        if (response.auth == true) {
                            document.cookie = "token="+response.token + ";expires=" + cookieExpire() + ";path=/";
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

/* CHANGE THEME BUTTON */
$('#switchTheme').click(function() { 
    if ($('html').attr('data-bs-theme') == 'dark') {
        $('html').attr('data-bs-theme', 'light');
        $('#switchThemeIcon').removeClass('fa-sun').addClass('fa-moon');
        $('#switchThemeText').html('Tema Escuro');
        document.cookie = "theme=light"+ ";expires=" + cookieExpire() + ";path=/";
    } else {
        $('html').attr('data-bs-theme', 'dark');
        $('#switchThemeIcon').removeClass('fa-moon').addClass('fa-sun');
        $('#switchThemeText').html('Tema Claro');
        document.cookie = "theme=dark"+ ";expires=" + cookieExpire() + ";path=/";
    }
});
/* LOGOUT ACCOUNT BUTTON */
$('#userBoxLogout').click(function(e) {
    e.preventDefault();
    logout(token);
});
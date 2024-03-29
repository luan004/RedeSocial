import {
    getCookie,
    setCookie,
    deleteCookie,
    sidebarTabs,
    loadTheme,
    b64ImageToUrl
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

    $.ajax({
        type: "POST",
        url: "php/api/getUser.php",
        dataType: "json",
        data: {
            opt: 'id',
            val: id
        },
        success: function(response) {
            var avatar = null;
            if (response.avatar != null) {
                avatar = b64ImageToUrl(response.avatar);
            } else {
                avatar = 'https://ui-avatars.com/api/background=0D8ABC&color=fff?name=' + response.user;
            }
            $('#userBoxAvatar').attr('src', avatar);
            $('#userBoxName').html(response.name);
            $('#userBoxUsername').html('@'+response.user);
            $('#userBoxAvatarLink').attr('href', 'profile?u='+response.user);
            $('#userBoxLink').attr('href', 'profile?u='+response.user);
    
            $('#settings').show();	
            $('#feed').show();
    
            $('#postBoxUser').html('@'+response.user);
            $('#postBoxAvatar').attr('src', avatar);
        }
    });
}, function() {
    deleteCookie('token');
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
                setCookie('token', response.token);
                //document.cookie = "token="+response.token + ";expires=" + cookieExpire() + ";path=/";
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

    $('#nameInvalid').hide();
    $('#userInvalid').hide();
    $('#passInvalid').hide();
    $('#passIsNotTheSame').hide();
    $('#userAlreadyExists').hide();
    
    if (pass1.val() == pass2.val()) {
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
                console.log(response);
                if (response.register == true) {
                    $.ajax({
                        type: "POST",
                        url: "php/api/login.php",
                        dataType: "json",
                        data: {
                            user: user.val(),
                            pass: pass1.val()
                        },
                        success: function(response) {
                            if (response.auth == true) {
                                setCookie('token', response.token);
                                window.location.href = 'explore';
                            }
                        }
                    });
                } else {
                    $('#'+response.error).show();
                }
            }
        });
    } else {
        $('#passIsNotTheSame').show();
    }
    return false;
});

/* CHANGE THEME BUTTON */
$('#switchTheme').click(function() { 
    if ($('html').attr('data-bs-theme') == 'dark') {
        $('html').attr('data-bs-theme', 'light');
        $('#switchThemeIcon').removeClass('fa-sun').addClass('fa-moon');
        $('#switchThemeText').html('Tema Escuro');
        setCookie('theme', 'light');
        //document.cookie = "theme=light"+ ";expires=" + cookieExpire() + ";path=/";
    } else {
        $('html').attr('data-bs-theme', 'dark');
        $('#switchThemeIcon').removeClass('fa-moon').addClass('fa-sun');
        $('#switchThemeText').html('Tema Claro');
        setCookie('theme', 'dark');
        //document.cookie = "theme=dark"+ ";expires=" + cookieExpire() + ";path=/";
    }
});
/* LOGOUT ACCOUNT BUTTON */
$('#userBoxLogout').click(function(e) {
    e.preventDefault();
    logout(token);
});

// Client side validations
$('#registerUser').keyup(function() {
    this.value = this.value.replace(/[^a-zA-Z0-9_]/g, ''); ///^[a-zA-Z0-9_]+$/
});
$('#loginUser').keyup(function() {
    this.value = this.value.replace(/[^a-zA-Z0-9_]/g, ''); ///^[a-zA-Z0-9_]+$/
});
import {
    getCookie,
    deleteCookie,
    b64ImageToUrl
} from "../utils.js";

import {
    auth,
    logout
} from "../data.js";

/* AUTH */
const token = getCookie('token');
if (!token) {
    window.location.href = "explore";
}

/* LOAD USER INFO */
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

            var banner = null;
            if (response.banner != null) {
                banner = b64ImageToUrl(response.banner);
            } else {
                banner = 'resources/images/banner.jpg';
            }

            $('#editAvatar').attr('src', avatar);
            $('#editBanner').attr('src', banner);
            $('#editAboutMe').val(response.aboutme);
            
            if (response.color != null && response.color != 'default') {
                $("#editProfileCard").addClass('bg-' + response.color + '-subtle');
                $("#editProfileCard").val(response.color);
            }

            $('#editName').val(response.name);
            $('#editUser').val(response.user);
        }
    });
}, function() {
    //
}, token);

/* CHANGE PASS */
$('#changePassForm').submit(function(e) {
    e.preventDefault();

    $('#oldPass').removeClass('is-invalid');
    $('#newPass').removeClass('is-invalid');
    $('#newPass2').removeClass('is-invalid');

    $('#inv1').hide();
    $('#inv2').hide();
    $('#inv3').hide();

    const oldpass = $('#oldPass').val();
    const newpass = $('#newPass').val();
    const newpass2 = $('#newPass2').val();

    if (newpass == newpass2) {
        if (newpass.length >= 8 && newpass.length <= 32 && /^[a-zA-Z0-9_]+$/.test(newpass)) {
            $.ajax({
                type: "POST",
                url: "php/api/changePass.php",
                dataType: "json",
                data: {
                    oldPass: oldpass,
                    newPass: newpass,
                    token: token
                },
                success: function(response) {
                    if (response.success == true) {
                        $('#oldPass').val('');
                        $('#newPass').val('');
                        $('#newPass2').val('');

                        $('#oldPass').addClass('is-valid');
                        $('#newPass').addClass('is-valid');
                        $('#newPass2').addClass('is-valid');

                        logout(token);
                        
                    } else {
                        switch (response.error) {
                            case 1: // incorrect password
                                $('#oldPass').addClass('is-invalid');
                                $('#inv1').show();
                                break;
                            case 2: // invalid new password
                                $('#newPass').addClass('is-invalid');
                                $('#newPass2').addClass('is-invalid');
                                $('#inv3').show();
                                break;
                       }
                    }
                }
            });
        } else {
            $('#newPass').addClass('is-invalid');

            $('#newPass').val('');
            $('#newPass2').val('');

            $('#inv3').show();
        }
    } else {
        $('#newPass').addClass('is-invalid');
        $('#newPass2').addClass('is-invalid');

        $('#newPass').val('');
        $('#newPass2').val('');

        $('#inv2').show();
    }
});

// CHANGE AVATAR
$('#avatarSelector').click(function() {
    $("#avatarSelectorInput").click();
});
$('#avatarSelectorInput').change(function() {
    const file = $(this)[0].files[0];
    if (file.type.includes('image') && file.size <= 2097152) {
        $('#editAvatar').attr('src', URL.createObjectURL(file));
        $('#avatarSelector').val(1);
        $('#avatarDelete').hide();
        changes();
    } else {
        $("#toast").toast("show");
    }
});

// DELETE AVATAR
$('#avatarDelete').click(function() {
    $('#avatarDelete').val(1);
    $('#editAvatar').attr('src', 'https://ui-avatars.com/api/background=0D8ABC&color=fff?name=' + $('#editUser').val());
    changes();
});

// CHANGE BANNER
$('#bannerSelector').click(function() {
    $("#bannerSelectorInput").click();
});
$('#bannerSelectorInput').change(function() {
    const file = $(this)[0].files[0];
    if (file.type.includes('image') && file.size <= 2097152) {
        $('#editBanner').attr('src', URL.createObjectURL(file));
        $('#bannerSelector').val(1);
        $('#bannerDelete').hide();
        changes();
    } else {
        $("#toast").toast("show");
    }
});

// DELETE BANNER
$('#bannerDelete').click(function() {
    $('#bannerDelete').val(1);
    $('#editBanner').attr('src', 'resources/images/banner.jpg');
    changes();
});

// Edit about me
$('#editAboutMe').keyup(function() {
    const text = $(this).val();
    const count = $('#charCount');
    const parent = $('#charCountParent')

    count.html(text.length);


    if (text.length > 150) {
        parent.removeClass('text-bg-secondary');
        parent.addClass('text-bg-danger');
        $('#postText').addClass('is-invalid');
    } else {
        parent.addClass('text-bg-secondary');
        parent.removeClass('text-bg-danger');
        $('#postText').removeClass('is-invalid');
    }

    changes();
});

// Edit profile color
$(document).on('click', '.cor', function() {
    const cor = $(this).attr('value');

    $("#editProfileCard").removeClass('bg-primary-subtle');
    $("#editProfileCard").removeClass('bg-success-subtle');
    $("#editProfileCard").removeClass('bg-danger-subtle');
    $("#editProfileCard").removeClass('bg-warning-subtle');
    $("#editProfileCard").removeClass('bg-info-subtle');
    
    if (cor != 'null') {
        $("#editProfileCard").addClass('bg-' + cor + '-subtle');
        $("#editProfileCard").val(cor);
    }

    changes();
});

// Edit profile name
$('#editName').keyup(function() {
    changes();
});

function changes() {
    $('#btnSaveProfile').slideDown("fast");
}

/* SAVE CHANGES */
$('#btnSaveProfile').click(function() {
    const name = $('#editName').val();
    const aboutme = $('#editAboutMe').val();
    var color = $('#editProfileCard').val();

    console.log(aboutme);

    if ($('#avatarSelector').val() == 1) {
        var avatar = $('#avatarSelectorInput')[0].files[0];
        var reader = new FileReader();
        reader.readAsDataURL(avatar);
        reader.onload = function () {
            avatar = reader.result;          
            // Enviar requisição AJAX somente após a leitura completa da imagem
            $.ajax({
                type: "POST",
                url: "php/api/updateAvatarOrBanner.php",
                dataType: "json",
                data: {
                    type: 'avatar',
                    file: avatar,
                    token: token
                },
                success: function(response) {
                    console.log(response);
                }
            });
        }
    } else {
        if ($('#avatarDelete').val() == 1) {
            $.ajax({
                type: "POST",
                url: "php/api/deleteAvatarOrBanner.php",
                dataType: "json",
                data: {
                    type: 'avatar',
                    token: token
                },
                success: function(response) {
                    console.log(response);
                }
            });
        }
    }
    

    if ($('#bannerSelector').val() == 1) {
        var banner = $('#bannerSelectorInput')[0].files[0];
        var reader = new FileReader();
        reader.readAsDataURL(banner);
        reader.onload = function () {
            banner = reader.result;          
            // Enviar requisição AJAX somente após a leitura completa da imagem
            $.ajax({
                type: "POST",
                url: "php/api/updateAvatarOrBanner.php",
                dataType: "json",
                data: {
                    type: 'banner',
                    file: banner,
                    token: token
                },
                success: function(response) {
                    console.log(response);
                }
            });
        }
    } else {
        if ($('#bannerDelete').val() == 1) {
            $.ajax({
                type: "POST",
                url: "php/api/deleteAvatarOrBanner.php",
                dataType: "json",
                data: {
                    type: 'banner',
                    token: token
                },
                success: function(response) {
                    console.log(response);
                }
            });
        }
    }

    $('#editName').removeClass('is-invalid');

    if (name.length < 1 || name.length > 64) {
        //invalid name
        $('#editName').addClass('is-invalid');
        $('#nameinv').show();
    }

    // ALTERAÇÃO DE NOME, USER E COR
    if (name.length >= 1 && name.length <= 64 && aboutme.length <= 150) {
        $.ajax({
            type: "POST",
            url: "php/api/updateUser.php",
            dataType: "json",
            data: {
                token: token,
                name: name,
                aboutme: aboutme,
                color: color
            },
            success: function(response) {
                if (response.success == true) {
                    window.location.reload();
                }
            }
        });
    }
});

$('#formDeleteMyAccount').submit(function(e) {
    e.preventDefault();
    const pass = $('#deleteAccountPass').val();

    $('#deleteAccountPass').removeClass('is-invalid');
    $('#inv4').hide();
    
    $.ajax({
        type: "POST",
        url: "php/api/deleteAccount.php",
        dataType: "json",
        data: {
            token: token,
            pass: pass
        },
        success: function(response) {
            console.log(response);
            if (response.success == true) {
                $('#formDeleteMyAccount').hide();
                $('#deleteAccountSuccess').show();
                deleteCookie('token');

                // redirecionar em 5 segundos
                setTimeout(function() {
                    window.location.href = "explore";
                }, 5000);
            } else {
                $('#inv4').show();
                $('#deleteAccountPass').addClass('is-invalid');
                $('#deleteAccountPass').val('');
            }
        }
    });
});
import {
    getCookie
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
            $('#editAvatar').attr('src', response.avatar);
            $('#editBanner').attr('src', response.banner);
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

// Edit profile avatar
$('#avatarSelector').click(function() {
    $("#avatarSelectorInput").click();
});
$('#avatarSelectorInput').change(function() {
    const file = $(this)[0].files[0];
    if (file.type.includes('image')) {
        $('#editAvatar').attr('src', URL.createObjectURL(file));
        changes();
    }
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

$('#btnSaveProfile').click(function() {
    const name = $('#editName').val();
    const aboutme = $('#editAboutMe').val();
    var color = $('#editProfileCard').val();

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

    // ALTERAÇÃO DE AVATAR
    
});
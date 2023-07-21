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

// Edit profile user
$('#editUser').keyup(function() {
    changes();
});

function changes() {
    $('#btnSaveProfile').slideDown("fast");
}

$('#btnSaveProfile').click(function() {
    const name = $('#editName').val();
    const user = $('#editUser').val();
    var color = $('#editProfileCard').val();

    $('#editName').removeClass('is-invalid');
    $('#editUser').removeClass('is-invalid');

    if (name.length < 1 || name.length > 64) {
        //invalid name
        $('#editName').addClass('is-invalid');
        $('#nameinv').show();
    }

    if (user.length < 4 || user.length > 32) {
        //invalid user
        $('#editUser').addClass('is-invalid');
        $('#userinv').show();
    }


    // ALTERAÇÃO DE NOME, USER E COR
    if (user.length >= 4 && user.length <= 32 && name.length >= 1 && name.length <= 64) {
        switch (color) {
            case 'primary':
                break;
            case 'success':
                break;
            case 'danger':
                break;
            case 'warning':
                break;
            case 'info':
                break;
            case 'null':
                color = null;
            default:
                color = null;
                break;
        }

        console.log('teste');
        $.ajax({
            type: "POST",
            url: "php/api/updateUser.php",
            dataType: "json",
            data: {
                token: token,
                name: name,
                user: user,
                color: color
            },
            success: function(response) {
                console.log(response);
            }
        });
    }

    // ALTERAÇÃO DE AVATAR
    
});
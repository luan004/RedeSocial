import {
    getCookie
} from "../utils.js";

import {
    auth
} from "../data.js";

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
                    oldpass: oldpass,
                    newpass: newpass,
                    token: token
                },
                success: function(response) {
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
                            } else {
                                switch (response.error) {
                                    case 1: // incorrect password
                                        $('#oldPass').addClass('is-invalid');
                                        $('#inv1').show();
                                        break;
                                    case 2: // invalid new password
                                        $('#newPass').addClass('is-invalid');
                                        $('#newPass2').addClass('is-invalid');
                                        $('#inv2').show();
                                        break;
                               }
                            }
                        }
                    });
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
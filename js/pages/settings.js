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
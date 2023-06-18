import {
    getCookie
} from "../utils.js";

const params = new URLSearchParams(window.location.search);
const user = params.get('u');

if (user == null || user == '') {
    window.location.href = 'feed.html';
}

if (user != null) {
    $("title").text('RedeSocial | @' + user);
}

const token = getCookie('token');
/* COMUNICAÇÃO COM BACKEND */
$.ajax({
    type: "POST",
    url: "php/getProfile.php",
    dataType: "json",
    data: {
        user: user,
        token: token
    },
    success: function(response) {
        if (response.exists == true) {
            $("#username").html('@'+user);
            $("#name").html(response.name);
            $("#avatar").attr("src", response.avatar);
            $("#banner").attr("src", response.banner);

            if (response.isSelf == true) {
                $("#follow").hide();
            } else {
                $('#editProfile').hide();
            }
        } else {
            $("#username").html();
            $("#name").html('Essa conta não existe');
            $("#avatar").attr("src", "https://ui-avatars.com/api/background=0D8ABC&color=fff?name=@");
            $("#banner").attr("src", "./resources/images/banner.jpg");
        }
    }
});
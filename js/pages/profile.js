import {
    getCookie,
    calcularTempoDecorrido,
    realcarHashtags,
    genPostHTML,
    deletePost
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
    url: "php/api/getProfile.php",
    dataType: "json",
    data: {
        user: user,
        token: token
    },
    success: function(response) {
        if (response.success == true) {
            /* Usuário encontrado */
            $("#username").html('@'+user);
            $("#name").html(response.name);
            $("#avatar").attr("src", response.avatar);
            $("#banner").attr("src", response.banner);

            if (response.isme == true) {
                /* Perfil do usuário autenticado */
                $("#follow").hide();
            } else {
                /* Outro perfil */
                $('#editProfile').hide();
            }
        } else {
            /* Usuário não encontrado */
            $("#username").html();
            $("#name").html('x');
            $("#avatar").attr("src", "https://ui-avatars.com/api/background=0D8ABC&color=fff?name=@");
            $("#banner").attr("src", "./resources/images/banner.jpg");
            $('#editProfile').hide();
            $("#follow").hide();
        }
    }
});

$.ajax({
    type: "POST",
    url: "php/api/getPosts.php",
    dataType: "json",
    data: {
        type: 'user',
        user: user,
        token: token
    },
    success: function(response) {
        for (var i = 0; i < response.count; i++) {
            const post = response.posts[i];

            $("#profilePosts").append(genPostHTML(post));
        }
    }
});

$(document).on('click', '.btnPostDelete', function() {
    const postId = $(this).parent().attr('value');
    deletePost(postId, token);
});

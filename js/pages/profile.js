import {
    getCookie,
    genPostHTML,
    deletePost,
    toggleLikePost,
    toggleFollow
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
                $('#editProfile').show();
            } else {
                /* Outro perfil */
                if (response.ifollow == true) {
                    $("#follow").html('<i class="fa fa-user-plus"></i> Seguido');
                    $("#follow").removeClass('btn-outline-primary');
                    $("#follow").addClass('btn-primary')
                }
                $("#follow").show();
            }
        } else {
            /* Usuário não encontrado */
            $("#username").html();
            $("#name").html('Esse usuário não existe ou foi excluído.');
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
        for (var i = 0; i < response.posts.length; i++) {
            const post = response.posts[i];

            $("#profilePosts").append(genPostHTML(post));
        }
    }
});

$(document).on('click', '.btnPostDelete', function() {
    const postId = $(this).parent().attr('value');
    deletePost(postId, token);
});

$(document).on('click', '.btnPostLike', function() {
    const btn = $(this);
    const postId = btn.parent().attr('value');
    const likeNum = btn.children('span').text();

    toggleLikePost(postId, token, btn, likeNum);
});

$(document).on('click', '#follow', function() {
    toggleFollow(user, token);
});
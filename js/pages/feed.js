import {
    getCookie,
    genPostHTML,
    deletePost,
    toggleLikePost
} from "../utils.js";

const token = getCookie('token');
if (!token) {
    window.location.href = "explore";
}
$.ajax({
    type: "POST",
    url: "php/api/auth.php",
    dataType: "json",
    data: {
        token: token
    },
    success: function(response) {
        if (response.auth == false) {
            window.location.href = "explore";
        }
    }
});

$('#sendPostForm').submit(function(e) {
    e.preventDefault();
    const text = $('#postText').val();
    const image = null;
    if (text != "") {
        $.ajax({
            type: "POST",
            url: "php/api/createPost.php",
            dataType: "json",
            data: {
                text: text,
                image: image,
                token: token
            },
            success: function(response) {
                if (response) {
                    window.location.reload();
                }
            }
        });
    } else {
        $('#postText').addClass('is-invalid');
        setTimeout(() => {
            $('#postText').removeClass('is-invalid');
        }
        , 2000);
    }
});

$.ajax({
    type: "POST",
    url: "php/api/getPosts.php",
    dataType: "json",
    data: {
        type: 'feed',
        token: token
    },
    success: function(response) {
        if (response.posts.length > 0) {
            for (var i = 0; i < response.posts.length; i++) {
                const post = response.posts[i];
                console.log(post);
                $("#postsFeed").append(genPostHTML(post));
            }
        } else {
            $("#postsFeed").append(`
                <h3 class="text-center">:( Nenhum post encontrado</h3>
                <p class="text-center">Siga pessoas e você verá o que elas publicam aqui, ou navegue pelo <a href="explore">explorar</a> e veja o que desconhecidos estão publicando!</p>
            `);
        }
    }
});

$(document).on('click', '.btnPostDelete', function() {
    const postId = $(this).parent().attr('value');
    deletePost(postId, token);
});

$(document).on('click', '.btnPostLike', function() {
    toggleLikePost(token, $(this));
});
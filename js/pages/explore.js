import {
    calcularTempoDecorrido,
    realcarHashtags,
    getCookie,
    genPostHTML,
    deletePost
} from "../utils.js";

const token = getCookie('token');

$.ajax({
    type: "POST",
    url: "php/api/getPosts.php",
    dataType: "json",
    data: {
        type: 'all',
        token: token
    },
    success: function(response) {
        console.log(response);
        for (var i = 0; i < response.posts.length; i++) {
            const post = response.posts[i];

            $("#lastPosts").append(genPostHTML(post));
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

    $.ajax({
        type: "POST",
        url: "php/api/toggleLike.php",
        dataType: "json",
        data: {
            postId: postId,
            token: token
        },
        success: function(response) {
            if (response.success == true && response.liked == true) {
                btn.children('span').text(parseInt(likeNum)+1);
                btn.removeClass('btn-outline-primary');
                btn.addClass('btn-primary');
            } else if (response.success == true && response.liked == false) {
                btn.children('span').text(parseInt(likeNum)-1);
                btn.removeClass('btn-primary');
                btn.addClass('btn-outline-primary');
            }
        }
    });
});
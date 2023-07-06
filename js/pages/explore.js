import {
    calcularTempoDecorrido,
    realcarHashtags,
    getCookie,
    genPostHTML
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
        for (var i = 0; i < response.count; i++) {
            const post = response.posts[i];

            $("#lastPosts").append(genPostHTML(post));
        }
    }
});

$(document).on('click', '.btnPostDelete', function() {
    const postId = $(this).parent().attr('value');
    $.ajax({
        type: "POST",
        url: "php/api/deletePost.php",
        dataType: "json",
        data: {
            postId: postId,
            token: token
        },
        success: function(response) {
            console.log('response')
            if (response) {
                window.location.reload();
            }
        }
    });
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
                btn.addClass('btn-primary');
                btn.removeClass('btn-outline-primary');
            } else if (response.success == true && response.liked == false) {
                btn.children('span').text(parseInt(likeNum)-1);
                btn.addClass('btn-outline-primary');
                btn.removeClass('btn-primary');
            }
        }
    });
});
import {
    getCookie,
    genPostHTML,
    deletePost,
    toggleLikePost
} from "../utils.js";

const token = getCookie('token');

$("#hashtags").hide();

$(document).on('click', '.hashtagOpt', function() {
    const opt = $(this).val();
    if (opt == 'today') {
        $('hashtagsToday').show();
        $('hashtagsAll').hide();
    } else if (opt == 'all') {
        $('hashtagsAll').show();
        $('hashtagsToday').hide();
    }
});

$.ajax({
    type: "POST",
    url: "php/api/getPosts.php",
    dataType: "json",
    data: {
        type: 'all',
        token: token
    },
    success: function(response) {
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
    toggleLikePost(token, $(this));
});

import {
    getCookie,
    genPostHTML,
    deletePost,
    toggleLikePost
} from "../utils.js";

const token = getCookie('token');

$("#hashtags").hide();

// ACTION FROM HASHTAGS SELECT MENU
$("#hashtagsOpt").change(function() {
    if (this.value == "today") {
        $("#todayHashtagsDiv").show();
        $("#allHashtagsDiv").hide();
    } else {
        $("#todayHashtagsDiv").hide();
        $("#allHashtagsDiv").show();
    }
});

/* LOAD HASHTAGS */
$.ajax({
    type: "POST",
    url: "php/api/getHashtags.php",
    dataType: "json",
    data: {
        maxNumberOfHashtags: 15,
        opt: "today"
    },
    success: function(response) {
        response.forEach(hashtag => {
            $("#todayHashtags").append(`
                <li class="list-group-item">
                    <span>${hashtag.word}</span>
                    <small class="float-end">${hashtag.count}</small>
                </li>
            `);
        });
    }
});
$.ajax({
    type: "POST",
    url: "php/api/getHashtags.php",
    dataType: "json",
    data: {
        maxNumberOfHashtags: 15,
        opt: "all"
    },
    success: function(response) {
        response.forEach(hashtag => {
            $("#allHashtags").append(`
                <li class="list-group-item">
                    <span>${hashtag.word}</span>
                    <small class="float-end">${hashtag.count}</small>
                </li>
            `);
        });
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

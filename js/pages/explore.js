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
        maxNumberOfHashtags: 20,
        opt: "today"
    },
    success: function(response) {
        if (response.length == 0) {
            $("#todayHashtags").append(`
                <li class="list-group-item text-center">
                    <span>Nenhuma hashtag encontrada :(</span>
                </li>
            `);
            return;
        }
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
        maxNumberOfHashtags: 20,
        opt: "all"
    },
    success: function(response) {
        if (response.length == 0) {
            $("#allHashtags").append(`
                <li class="list-group-item text-center">
                    <span>Nenhuma hashtag encontrada :(</span>
                </li>
            `);
            return;
        }
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

var page = 0;
$.ajax({
    type: "POST",
    url: "php/api/getPosts.php",
    dataType: "json",
    data: {
        type: 'all',
        token: token,
        page: page
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

//user scrolled to bottom of the page, load more posts
$(window).scroll(function() {
    if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
        page++;
        $(window).scrollTop($(window).scrollTop() - 20);
        $.ajax({
            type: "POST",
            url: "php/api/getPosts.php",
            dataType: "json",
            data: {
                type: 'all',
                token: token,
                page: page
            },
            beforeSend: function() {
                $("#lastPosts").append(`
                    <div class="text-center loadingSpin">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `);
            },
            success: function(response) {
                $(".loadingSpin").remove();
                for (var i = 0; i < response.posts.length; i++) {
                    const post = response.posts[i];
                    $("#lastPosts").append(genPostHTML(post));
                }
            }
        });
    }
});

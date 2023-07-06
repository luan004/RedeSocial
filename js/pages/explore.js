import {
    calcularTempoDecorrido,
    realcarHashtags,
    getCookie
} from "../utils.js";

const token = getCookie('token');

$.ajax({
    type: "POST",
    url: "php/api/getPosts.php",
    dataType: "json",
    data: {
        type: 'all'
    },
    success: function(response) {
        for (var i = 0; i < response.count; i++) {
            const post = response.posts[i];
            
            var postStr = `
            <div class="card mb-4 shadow">
                <div class="card-header">
                    <img src="${post.user.avatar}" width="32" height="32" class="rounded-circle me-2" alt="...">
                    <span class="align-middle h6">${post.user.name}</span>
                    <small class="ms-auto align-middle">@${post.user.user}</small>
                </div>`;
            if (post.image != "" && post.image != null) {
                postStr += `<img src="${post.image}" alt="...">`;
            }
            postStr += `
                <div class="card-text p-3">
                    <p class="card-text">
                        ${realcarHashtags(post.text)}
                    </p>
                </div>
                <div class="card-footer d-flex" value="${post.id}">`;
                if (post.iliked == true) {
                    postStr += `
                    <button class="btnPostLike btn btn-sm btn-primary" actived>
                        <i class="fa fa-thumbs-up"></i>
                        <span>${post.likes}</span>
                    </button>`;
                } else {
                    postStr += `
                    <button class="btnPostLike btn btn-sm btn-outline-primary" actived>
                        <i class="fa fa-thumbs-up"></i>
                        <span>${post.likes}</span>
                    </button>`;
                }
                postStr += `
                    <a href="post?p=${post.id}" class="btn btn-sm btn-outline-secondary ms-2">
                            <i class="fa fa-comment"></i>
                            ${post.comments}
                    </a>`;
                if (post.ismy == true) {
                    postStr += `
                        <button class="btnPostDelete btn btn-sm btn-outline-danger ms-2">
                            <i class="fa fa-trash"></i>
                        </button>`; 
                }
                postStr += `
                    <small class="text-body-secondary ms-auto">
                        ${calcularTempoDecorrido(post.dt)}
                    </small>
                </div>
            </div>`;
            $("#lastPosts").append(postStr);
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
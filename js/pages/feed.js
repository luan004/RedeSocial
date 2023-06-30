import {
    getCookie,
    calcularTempoDecorrido,
    realcarHashtags
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
    url: "php/getFeed.php",
    dataType: "json",
    data: {
        token: token
    },
    success: function(response) {
        if (response.auth == true && response.count > 0) {
            var num = 1;
            while (num < response.count+1) {
                const post = response['p'+num];
                
                var postStr = `
                <div class="card mb-4 shadow">
                    <div class="card-header">
                        <img src="${post.avatar}" width="32" height="32" class="rounded-circle me-2" alt="...">
                        <span class="align-middle h6">${post.name}</span>
                        <small class="ms-auto align-middle">@${post.user}</small>
                    </div>`;

                if (post.image != "" && post.image != null) {
                    postStr += `<img src="${post.image}" alt="...">`;
                }

                postStr += `
                    <div class="card-text p-3">
                        <a href="post?p=${post.id}" style="text-decoration: none;color:inherit" class="card-text">
                            ${realcarHashtags(post.text)}
                        </a>
                    </div>
                    <div class="card-footer d-flex">
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-thumbs-up"></i>
                            ${post.likes}
                        </button>
                        <a href="post?p=${post.id}" class="btn btn-sm btn-outline-secondary ms-2">
                            <i class="fa fa-comment"></i>
                            ${post.comments}
                        </a>`;

                if (post.ismy == true) {
                    postStr += `
                        <button value="${post.id}" class="btnPostDelete btn btn-sm btn-outline-danger ms-2">
                            <i class="fa fa-trash"></i>
                            Apagar
                        </button>`;
                }

                postStr += `
                        <small class="text-body-secondary ms-auto">
                            ${calcularTempoDecorrido(post.dt)}
                        </small>
                    </div>
                </div>`;
                $("#postsFeed").append(postStr);
                num++;
            }
        }
    }
}); 

$(document).on('click', '.btnPostDelete', function() {
    const postId = $(this).val();
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
    const postId = $(this).val();
    $.ajax({
        type: "POST",
        url: "php/api/likePost.php",
        dataType: "json",
        data: {
            postId: postId,
            token: token
        },
        success: function(response) {
            if (response) {
                window.location.reload();
            }
        }
    });
});
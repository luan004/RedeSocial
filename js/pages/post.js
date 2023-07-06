import {
    calcularTempoDecorrido,
    getCookie,
    realcarHashtags
} from "../utils.js";

const params = new URLSearchParams(window.location.search);
const postId = params.get('p');

if (postId == null || postId == '') {
    window.location.href = 'feed.html';
}

const token = getCookie('token');
/* $.ajax({
    type: "POST",
    url: "php/getUserInfo.php",
    dataType: "json",
    data: {
        token: token
    },
    success: function(response) {
        if (response.auth == true) {
            $('#userComment').show();
            $('#userUser').html('@'+response.user);
            $('#userAvatar').attr('src', response.avatar);

            $('#sendPostForm').submit(function(e) {
                e.preventDefault();
                const text = $('#userCommentText').val();
                if (text == '') {
                    return;
                }
                $.ajax({
                    type: "POST",
                    url: "php/sendComment.php",
                    dataType: "json",
                    data: {
                        postId: postId,
                        token: token,
                        text: text
                    },
                    success: function(response) {
                        if (response.success == true) {
                            window.location.reload();
                        } else {
                            alert(response.error);
                        }
                    }
                });
            });
        }
    }
}); */

$.ajax({
    type: "POST",
    url: "php/api/getPost.php",
    dataType: "json",
    data: {
        id: postId
    },
    success: function(response) {
        if (response.success == true) {
            $('#postName').html(response.user.name);
            $('#postAvatar').attr('src', response.user.avatar);
            $('#postUser').html('@'+response.user.user);
            $('#postDt').html(calcularTempoDecorrido(response.dt));
            $('#postText').html(realcarHashtags(response.text));
            //$('#postLikes').html(response.likes);
            //$('#postCommentsNum').html(response.commentsNum);

            for (let i = 0; i < response.comments.length; i++) {
                const comment = response.comments[i];
                $('#postComments').append(
                    `
                    <div class="card mb-3">
                        <div class="card-header d-flex">
                            <img src="${comment.user.avatar}" width="32" height="32" class="rounded-circle me-2" alt="...">
                            <span class="align-middle h6">${comment.user.name}</span>
                            <small class="align-middle ms-2">@${comment.user.user}</small>
                            <small class="text-body-secondary ms-auto">
                                ${calcularTempoDecorrido(comment.dt)}
                            </small>
                        </div>
                        <div class="card-text p-3">
                            <p class="card-text">
                                ${realcarHashtags(comment.text)}
                            </p>
                        </div>
                    </div>
                    `
                );
            }
        } else {
            window.location.href = 'feed.html';
        }
    }
});
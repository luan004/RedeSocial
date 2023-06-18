import {
    getCookie,
    calcularTempoDecorrido
} from "../utils.js";

const token = getCookie('token');
$.ajax({
    type: "POST",
    url: "php/isAuth.php",
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
            url: "php/sendPost.php",
            dataType: "json",
            data: {
                token: token,
                text: text,
                image: image
            },
            success: function(response) {
                if (response) {
                    window.location.reload();
                }
            }
        });
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
                        <p class="card-text">
                            ${post.text}
                        </p>
                    </div>
                    <div class="card-footer d-flex">
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-thumbs-up"></i>
                            ${post.likes}
                        </button>
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

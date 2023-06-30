import {
    calcularTempoDecorrido,
    realcarHashtags,
    getCookie
} from "../utils.js";

const token = getCookie('token');

/* CARREGAR O FEED */
$.ajax({
    type: "POST",
    url: "php/api/getPosts.php",
    dataType: "json",
    data: {
        feed: false,
        token: token
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
                <div class="card-footer d-flex">
                    <button class="btn btn-sm btn-outline-primary">
                        <i class="fa fa-thumbs-up"></i>
                        ${post.likes}
                    </button>
                    <a href="post?p=${post.id}" class="btn btn-sm btn-outline-secondary ms-2">
                            <i class="fa fa-comment"></i>
                            //
                    </a>`;

                if (post.ismy == true) {
                    postStr += `
                        <button value="${post.id}" class="btnPostEdit btn btn-sm btn-outline-secondary ms-2">
                            <i class="fa fa-pencil"></i>
                        </button>
                        <button value="${post.id}" class="btnPostDelete btn btn-sm btn-outline-danger ms-2">
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

/* $.ajax({
    type: "POST",
    url: "php/getLastPosts.php",
    dataType: "json",
    success: function(response) {
        if (response.count > 0) {
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
                            ${realcarHashtags(post.text)}
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
                $("#lastPosts").append(postStr);
                num++;
            }
        }
    }
});
 */
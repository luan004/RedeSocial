import {
    getCookie,
    calcularTempoDecorrido
} from "../utils.js";

const params = new URLSearchParams(window.location.search);
const user = params.get('u');

if (user == null || user == '') {
    window.location.href = 'feed.html';
}

if (user != null) {
    $("title").text('RedeSocial | @' + user);
}

const token = getCookie('token');
/* COMUNICAÇÃO COM BACKEND */
$.ajax({
    type: "POST",
    url: "php/getProfile.php",
    dataType: "json",
    data: {
        user: user,
        token: token
    },
    success: function(response) {
        if (response.exists == true) {
            $("#username").html('@'+user);
            $("#name").html(response.name);
            $("#avatar").attr("src", response.avatar);
            $("#banner").attr("src", response.banner);

            if (response.isSelf == true) {
                $("#follow").hide();
            } else {
                $('#editProfile').hide();
            }
        } else {
            $("#username").html();
            $("#name").html('Essa conta não existe');
            $("#avatar").attr("src", "https://ui-avatars.com/api/background=0D8ABC&color=fff?name=@");
            $("#banner").attr("src", "./resources/images/banner.jpg");
            $('#editProfile').hide();
            $("#follow").hide();
        }
    }
});

$.ajax({
    type: "POST",
    url: "php/getPostsFromUser.php",
    dataType: "json",
    data: {
        user: user
    },
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
                $("#profilePosts").append(postStr);
                num++;
            }
        }
    }
});
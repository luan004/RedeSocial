import {
    getCookie,
    genPostHTML,
    deletePost,
    toggleLikePost,
    toggleFollow,
    calcularTempoDecorrido,
    b64ImageToUrl
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
    url: "php/api/getProfile.php",
    dataType: "json",
    data: {
        user: user,
        token: token
    },
    success: function(response) {
        if (response.success == true) {
            /* Usuário encontrado */
            var avatar = null;
            if (response.avatar != null) {
                avatar = b64ImageToUrl(response.avatar);
            } else {
                avatar = 'https://ui-avatars.com/api/background=0D8ABC&color=fff?name=' + response.name;
            }

            var banner = null;
            if (response.banner != null) {
                banner = b64ImageToUrl(response.banner);
            } else {
                banner = 'https://placehold.it/512x128';
            }
            $("#username").html(user);
            $("#name").html(response.name);
            $("#avatar").attr("src", avatar);
            $("#banner").attr("src", banner);
            if (response.dt != null) {
                $('#createdAt').html('Conta criada há ' + calcularTempoDecorrido(response.dt));
            }
            if (response.aboutme != null && response.aboutme != '') {
                $('#aboutMe').show();
                $('#aboutMeText').html(response.aboutme);
            }

            $('#followersCount').html(response.followersCount);
            $('#followingCount').html(response.followingCount);

            const c = response.color;
            if 
            (
                c == 'primary' ||
                c == 'success' ||
                c == 'danger' ||
                c == 'warning' ||
                c == 'info'
            ){
                $('#profileCard').addClass('bg-'+c+'-subtle');
            }

            if (response.isme == true) {
                /* Perfil do usuário autenticado */
                $('#editProfile').show();
            } else {
                /* Outro perfil */
                if (response.ifollow == true) {
                    $("#follow").html('<i class="fa fa-user-plus"></i> Seguido');
                    $("#follow").removeClass('btn-outline-primary');
                    $("#follow").addClass('btn-primary')
                }
                $("#follow").show();
            }

            /* Carregar posts */
            $.ajax({
                type: "POST",
                url: "php/api/getPosts.php",
                dataType: "json",
                data: {
                    type: 'user',
                    user: user,
                    token: token
                },
                success: function(response) {
                    for (var i = 0; i < response.posts.length; i++) {
                        const post = response.posts[i];
                        $("#profilePosts").append(genPostHTML(post));
                    }
                    $('#postsCount').html(response.posts.length);
                    if (response.posts.length == 0) {
                        $("#profilePosts").append(`
                            <span class="text-center d-block">
                                <span class="text-muted d-block mb-4">Nenhuma publicação encontrada...</span>
                                <i style="font-size: 5rem; opacity:0.1" class="fa fa-search"></i>
                            </span>
                        `);
                    }
                }
            });
        } else {
            /* Usuário não encontrado */
            $("#avatar").attr("src", "https://ui-avatars.com/api/background=0D8ABC&color=fff?name=@");
            $("#banner").attr("src", "./resources/images/banner.jpg");
            $('#userInfoBoxProfile').hide();
            $("#unknowUser").show();
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

$(document).on('click', '#follow', function() {
    toggleFollow(user, token);
});

$.ajax({
    type: "POST",
    url: "php/api/getFollowers.php",
    dataType: "json",
    data: {
        user: user
    },
    success: function(response) {
        if (response.success == true) {
            if (response.followers.length == 0) {
                $('#followersList').append(`
                    <small class="text-truncate p-5 text-center d-block">Nenhum seguidor encontrado</small>    
                `);
            }
            response.followers.forEach(follower => {
                var avatar = null;
                if (follower.avatar != null) {
                    avatar = b64ImageToUrl(follower.avatar);
                } else {
                    avatar = 'https://ui-avatars.com/api/background=0D8ABC&color=fff?name=' + follower.user;
                }
                $("#followersList").append(`
                    <li class="list-group-item px-2">
                        <a href="profile?u=${follower.user}" class="d-flex align-items-center" style="text-decoration: none;">
                            <div class="d-inline-block position-relative me-2">
                                <img src="${avatar}" width="40" height="40" class="rounded-circle" alt="">
                            </div>
                            ${follower.name}
                            <small class="ms-2 text-muted">@${follower.user}</small>
                        </a>
                    </li>
                `);
            });
        }
    }
}); 
$("#inputSearchFollowers").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#followersList li").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
});



$.ajax({
    type: "POST",
    url: "php/api/getFollowings.php",
    dataType: "json",
    data: {
        user: user
    },
    success: function(response) {
        if (response.success == true) {
            if (response.followings.length == 0) {
                $('#followingList').append(`
                    <small class="text-truncate p-5 text-center d-block">Nenhum seguidor encontrado</small>    
                `);
            }
            response.followings.forEach(following => {
                var avatar = null;
                if (following.avatar != null) {
                    avatar = b64ImageToUrl(following.avatar);
                } else {
                    avatar = 'https://ui-avatars.com/api/background=0D8ABC&color=fff?name=' + following.user;
                }
                $("#followingList").append(`
                    <li class="list-group-item px-2">
                        <a href="profile?u=${following.user}" class="d-flex align-items-center" style="text-decoration: none;">
                            <div class="d-inline-block position-relative me-2">
                                <img src="${avatar}" width="40" height="40" class="rounded-circle" alt="">
                            </div>
                            ${following.name}
                            <small class="ms-2 text-muted">@${following.user}</small>
                        </a>
                    </li>
                `);
            });
        }
    }
});
$("#inputSearchFollowing").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#followingList li").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
});
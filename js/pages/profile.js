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
            $("#username").html(user);
            $("#name").html(response.name);
            $("#avatar").attr("src", avatar);
            $("#banner").attr("src", response.banner);
            if (response.dt != null) {
                $('#createdAt').html('Conta criada há ' + calcularTempoDecorrido(response.dt));
            }

            console.log(response.avatar);

            console.log(response.aboutme);
            if (response.aboutme != null && response.aboutme != '') {
                $('#aboutMe').show();
                $('#aboutMeText').html(response.aboutme);
            }

            const c = response.color;
            console.log(c);
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
            $("#username").html();
            $("#name").html('Esse usuário não existe ou foi excluído.');
            $("#avatar").attr("src", "https://ui-avatars.com/api/background=0D8ABC&color=fff?name=@");
            $("#banner").attr("src", "./resources/images/banner.jpg");
            $('#editProfile').hide();
            $("#follow").hide();
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
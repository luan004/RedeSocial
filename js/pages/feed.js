import {
    getCookie,
    genPostHTML,
    deletePost,
    toggleLikePost
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

$('#postImageSelector').click(function() {
    console.log('click');
    $('#postImageSelectorInput').click();
});

$('#postImageSelectorInput').change(function() {
    const file = $(this)[0].files[0];
    if (file.type.includes('image')) {
        console.log(file.type + '-  arquivo válido');
        $('#postImageSelector').hide();
        $('#postImg').show();
        $('#postImg').attr('src', URL.createObjectURL(file));
        $('#postImageDelete').show();
    } else {
        // arquivo invalido
        console.log('arquivo invalido');
    }
});

$('#postImageDelete').click(function() {
    $('#postImageSelectorInput').val('');
    $('#postImageSelector').show();
    $('#postImg').hide();
    $('#postImageDelete').hide();
});

$('#sendPostForm').submit(function(e) {
    e.preventDefault();
    const text = $('#postText').val();
    
    var img = $('#postImageSelectorInput')[0].files[0];
    if (img) {
        var reader = new FileReader();
        reader.readAsDataURL(img);
        reader.onload = function () {
            img = reader.result;          
            // Enviar requisição AJAX somente após a leitura completa da imagem
            if (text != "") {
                $.ajax({
                    type: "POST",
                    url: "php/api/createPost.php",
                    dataType: "json",
                    data: {
                        text: text,
                        image: img,
                        token: token
                    },
                    success: function(response) {
                        if (response) {
                            window.location.reload();
                        }
                    }
                });
            }
        }
    } else {
        img = null;
        // Enviar requisição AJAX quando não há imagem selecionada
        if (text != "") {
            $.ajax({
                type: "POST",
                url: "php/api/createPost.php",
                dataType: "json",
                data: {
                    text: text,
                    image: img,
                    token: token
                },
                success: function(response) {
                    if (response) {
                        window.location.reload();
                    }
                }
            });
        }
    }
    
    if (text == "") {
        $('#postText').addClass('is-invalid');
        setTimeout(() => {
            $('#postText').removeClass('is-invalid');
        }, 2000);
    }
});


$.ajax({
    type: "POST",
    url: "php/api/getPosts.php",
    dataType: "json",
    data: {
        type: 'feed',
        token: token
    },
    success: function(response) {
        if (response.posts.length > 0) {
            for (var i = 0; i < response.posts.length; i++) {
                const post = response.posts[i];
                $("#postsFeed").append(genPostHTML(post));
            }
        } else {
            $("#postsFeed").append(`
                <h3 class="text-center">:( Nenhum post encontrado</h3>
                <p class="text-center">Siga pessoas e você verá o que elas publicam aqui, ou navegue pelo <a href="explore">explorar</a> e veja o que desconhecidos estão publicando!</p>
            `);
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
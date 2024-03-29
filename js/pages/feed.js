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

$('#postText').keyup(function() {
    const text = $(this).val();
    const count = $('#charCount');
    const parent = $('#charCountParent')

    count.html(text.length);


    if (text.length > 280) {
        parent.removeClass('text-bg-secondary');
        parent.addClass('text-bg-danger');
        $('#postText').addClass('is-invalid');
        $('#submitButton').hide();
    } else {
        parent.addClass('text-bg-secondary');
        parent.removeClass('text-bg-danger');
        $('#postText').removeClass('is-invalid');
        $('#submitButton').show();
    }
});

$('#postImageSelector').click(function() {
    $('#postImageSelectorInput').click();
});

$('#postImageSelectorInput').change(function() {
    const file = $(this)[0].files[0];
    if (file.type.includes('image')) {
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

var page = 0;
$.ajax({
    type: "POST",
    url: "php/api/getPosts.php",
    dataType: "json",
    data: {
        type: 'feed',
        token: token,
        page: page
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
                type: 'feed',
                token: token,
                page: page
            },
            beforeSend: function() {
                $("#postsFeed").append(`
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
                    $("#postsFeed").append(genPostHTML(post));
                }
            }
        });
    }
});

$(document).on('click', '.btnPostDelete', function() {
    const postId = $(this).parent().attr('value');
    deletePost(postId, token);
});

$(document).on('click', '.btnPostLike', function() {
    toggleLikePost(token, $(this));
});
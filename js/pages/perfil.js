const params = new URLSearchParams(window.location.search);
const user = params.get('u');

if (user == null || user == '') {
    window.location.href = 'feed.html';
}

if (user != null) {
    $("title").text('RedeSocial | @' + user);
}

/* COMUNICAÇÃO COM BACKEND */
$.ajax({
    type: "POST",
    url: "php/getPerfil.php",
    dataType: "json",
    data: {
        user: user,
        pass: 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3'
    },
    success: function(response) {
        if (response.exists == true) {
            $("#username").html('@'+user);
            $("#name").html(response.name);
            $("#avatar").attr("src", response.avatar);
            $("#banner").attr("src", response.banner);
        } else {
            $("#username").html();
            $("#name").html('Essa conta não existe');
            $("#avatar").attr("src", "./resources/images/avatar.webp");
            $("#banner").attr("src", "./resources/images/banner.jpg");
        }
    }
});
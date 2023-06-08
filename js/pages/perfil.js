const params = new URLSearchParams(window.location.search);
const user = params.get('u');

if (user != null) {
    $("title").text('RedeSocial | @' + user);
}

/* COMUNICAÇÃO COM BACKEND */
$.ajax({
    type: "POST",
    url: "php/getPerfil.php",
    dataType: "json",
    data: {
        user: user
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
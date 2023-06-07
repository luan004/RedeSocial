const params = new URLSearchParams(window.location.search);
const user = params.get('u');
$("#username").html('@'+user);

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
        $("#name").html(response.name);
        $("#user").html(user);
        $("#avatar").attr("src", response.avatar);
        $("#banner").attr("src", response.banner);

        console.log('a'+response.name)
    }
});
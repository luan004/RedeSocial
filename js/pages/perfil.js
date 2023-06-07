const params = new URLSearchParams(window.location.search);
const user = params.get('u');
$("#username").html('@'+user);

/* COMUNICAÇÃO COM BACKEND */
$.ajax({
    type: "POST",
    url: "php/loadUser.php",
    dataType: "json",
    data: {
        user: user
    },
    success: function(response) {
        $("#name").html(response.name);
        $("#avatar").attr("src", response.avatar);
        $("#banner").attr("src", response.banner);
    }
});
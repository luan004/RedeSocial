$("#name").html('Save');

$.ajax({
    type: "POST",
    url: "php/loadUser.php",
    data: {categoria:query},
    success: function(dados){
        console.log('dados' + dados);
    }
});
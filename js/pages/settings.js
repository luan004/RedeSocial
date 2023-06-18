import {
    getCookie
} from "../utils.js";

const token = getCookie("token");
$.ajax({
    type: "POST",
    url: "php/getProfileToEdit.php",
    dataType: "json",
    data: {
        token: token
    },
    success: function(response) {
        $("#user").html('@'+response.user);
        $("#name").val(response.name);
        $("#avatar").attr("src", response.avatar);
        $("#banner").attr("src", response.banner);
    }
});
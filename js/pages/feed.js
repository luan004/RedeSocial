import {
    getCookie
} from "../others/functions.js";

const token = getCookie('token');
$.ajax({
    type: "POST",
    url: "php/isAuth.php",
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
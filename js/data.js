export function auth(ifTrue, ifFalse, token) {
    $.ajax({
        type: "POST",
        url: "php/api/auth.php",
        dataType: "json",
        data: {
            token: token
        },
        success: function(response) {
            if (response.auth == true) {
                ifTrue(response.userId);
            } else {
                ifFalse();
            }
        }
    });
}

export function logout(token) {
    /* Apaga o token do cookie */
    document.cookie = "token=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/";
    $.ajax({
        type: "POST",
        url: "php/api/logout.php",
        dataType: "json",
        data: {
            token: token
        }
    });
    document.location.reload();
}
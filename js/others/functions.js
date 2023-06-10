/* Pegar o valor de um cookie */
export function getCookie(name) {
    const cookies = document.cookie.split(';');
        for(let i = 0; i < cookies.length; i++) {
            const cookie = cookies[i].trim();
            if (cookie.startsWith(name + '=')) {
            return cookie.substring(name.length + 1);
            }
        }
    return null;
}

/* Realizar logout */
export function logout(token) {
    /* Apaga o token do banco */
    $.ajax({
        type: "POST",
        url: "php/logout.php",
        dataType: "json",
        data: {
            token: token
        }
    });
    /* Apaga o token do cookie */
    document.cookie = "token=; expires=Thu, 01 Jan 1970 00:00:00 UTC;";
    window.location.reload();
}
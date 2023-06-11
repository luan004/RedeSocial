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
    /* Apaga o token do cookie */
    document.cookie = "token=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/";
    $.ajax({
        type: "POST",
        url: "php/logout.php",
        dataType: "json",
        data: {
            token: token
        }
    });
    document.location.reload();
}

export function calcularTempoDecorrido(dataPostagem) {
    const dataAtual = new Date();
    const dataPost = new Date(dataPostagem);

    const diffEmMilissegundos = Math.abs(dataAtual - dataPost);
    const segundos = Math.floor(diffEmMilissegundos / 1000);
    const minutos = Math.floor(segundos / 60);
    const horas = Math.floor(minutos / 60);
    const dias = Math.floor(horas / 24);
    const meses = Math.floor(dias / 30);
    const anos = Math.floor(meses / 12);

    if (segundos < 60) {
        return `há ${segundos} segundos`;
    } else if (minutos < 60) {
        return `há ${minutos} minutos`;
    } else if (horas < 24) {
        return `há ${horas} horas`;
    } else if (dias < 30) {
        return `há ${dias} dias`;
    } else if (meses < 12) {
        return `há ${meses} meses`;
    } else {
        return `há ${anos} anos`;
    }
}
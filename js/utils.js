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
export function realcarHashtags(texto) {
    // Expressão regular para encontrar hashtags
    var regex = /#[^\s#]+/g;
  
    // Substituir as hashtags pela tag <span> com estilo de cor azul
    var textoFormatado = texto.replace(regex, function(match) {
      return '<b style="color: #0d6efd;">' + match + '</b>';
    });
  
    return textoFormatado;
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

export function sidebarTabs() {
    var page = window.location.pathname;
    var tab;
    switch (true) {
    case page.includes('/explore'):
        tab = 'explore';
        break;
    case page.includes('/feed'):
        tab = 'feed';
        break;
    case page.includes('/settings'):
        tab = 'settings';
        break;
    }
    if (tab) {
    document.getElementById(tab).classList.add('active');
    }
}

export function loadTheme() {
    const theme = getCookie('theme');
    if (theme == 'light') {
        $('html').attr('data-bs-theme', 'light');
        $('#switchThemeIcon').addClass('fa-moon');
        $('#switchThemeText').html('Tema Escuro');
    } else {
        $('html').attr('data-bs-theme', 'dark');
        $('#switchThemeIcon').addClass('fa-sun');
        $('#switchThemeText').html('Tema Claro');
    }
}

export function cookieExpire() {
    return new Date().setFullYear(new Date().getFullYear() + 1);
}
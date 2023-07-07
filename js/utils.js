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

export function setCookie(name, value) {
    var expirationDate = new Date();
    expirationDate.setFullYear(expirationDate.getFullYear() + 1); // Adiciona 1 ano à data atual
    var expires = "expires=" + expirationDate.toUTCString();

    document.cookie = name + "=" + value + ";" + expires + ";path=/";
}

export function genPostHTML(post) {
    var postStr = `
    <div class="card mb-4 shadow">
        <div class="card-header">
            <img src="${post.user.avatar}" width="32" height="32" class="rounded-circle me-2" alt="...">
            <span class="align-middle h6">${post.user.name}</span>
            <small class="ms-auto align-middle">@${post.user.user}</small>
        </div>`;
    if (post.image != "" && post.image != null) {
        postStr += `<img src="${post.image}" alt="...">`;
    }
    postStr += `
        <div class="card-text p-3">
            <p class="card-text">
                ${realcarHashtags(post.text)}
            </p>
        </div>
        <div class="card-footer d-flex" value="${post.id}">`;
        if (post.liked == true) {
            postStr += `
            <button class="btnPostLike btn btn-sm btn-primary" actived>`;
        } else {
            postStr += `
            <button class="btnPostLike btn btn-sm btn-outline-primary" actived>`;
        }
        postStr += `
        <i class="fa fa-thumbs-up"></i>
                <span>${post.likes}</span>
            </button>
            <a href="post?p=${post.id}" class="btn btn-sm btn-outline-secondary ms-2">
                    <i class="fa fa-comment"></i>
                    ${post.comments}
            </a>`;
        if (post.ismy == true) {
            postStr += `
                <button class="btnPostDelete btn btn-sm btn-outline-danger ms-2">
                    <i class="fa fa-trash"></i>
                </button>`; 
        }
        postStr += `
            <small class="text-body-secondary ms-auto">
                ${calcularTempoDecorrido(post.dt)}
            </small>
        </div>
    </div>`;

    return postStr;
}

export function deletePost(postId, token) {
    console.log(postId);
    $.ajax({
        type: "POST",
        url: "php/api/deletePost.php",
        dataType: "json",
        data: {
            postId: postId,
            token: token
        },
        success: function(response) {
            if (response) {
                window.location.reload();
            }
        }
    });
}
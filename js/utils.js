export function setCookie(name, value) {
    var expirationDate = new Date();
    expirationDate.setFullYear(expirationDate.getFullYear() + 1); // Adiciona 1 ano à data atual
    var expires = "expires=" + expirationDate.toUTCString();

    document.cookie = name + "=" + value + ";" + expires + ";path=/";
}
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
export function deleteCookie(name) {
    document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/';
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

export function brDate(dt) {
    //2023-08-27 16:00:00 para 27 de agosto de 2023 às 16:00
    
    var data = dt.split(' ')[0].split('-');
    var hor = dt.split(' ')[1].split(':');

    var dia = data[2];
    var mes = data[1];
    var ano = data[0];

    var hora = hor[0];
    var minuto = hor[1];

    var meses = [
        'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho','julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'
    ];

    return dia + ' de ' + meses[mes - 1] + ' de ' + ano + ' às ' + hora + ':' + minuto;
}

export function calcularTempoDecorrido(dataPostagem) {
    const dataAtual = new Date();
    const dataPost = new Date(dataPostagem);

    //convert to UTC -3
    dataAtual.setHours(dataAtual.getHours() - 3);

    const diffEmMilissegundos = Math.abs(dataAtual - dataPost);
    const segundos = Math.floor(diffEmMilissegundos / 1000);
    const minutos = Math.floor(segundos / 60);
    const horas = Math.floor(minutos / 60);
    const dias = Math.floor(horas / 24);
    const meses = Math.floor(dias / 30);
    const anos = Math.floor(meses / 12);

    if (segundos < 5) {
        return (`agora mesmo`);
    } else if (segundos < 60) {
        return `há ${segundos} segundos`;
    } else if (minutos == 1) {
        return `há ${minutos} minuto`;
    } else if (minutos < 60) {
        return `há ${minutos} minutos`;
    } else if (horas == 1) {
        return `há ${horas} hora`;
    } else if (horas < 24) {
        return `há ${horas} horas`;
    } else if (dias == 1) {
        return `há ${dias} dia`;
    } else if (dias < 30) {
        return `há ${dias} dias`;
    } else if (meses == 1) {
        return `há ${meses} mês`;
    } else if (meses < 12) {
        return `há ${meses} meses`;
    } else if (anos == 1) {
        return `há ${anos} ano`;
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
export function b64ImageToUrl(b64Image) {
    return 'data:image/png;base64,' + b64Image;
}

export function genPostHTML(post) {
    var avatar = null;
    if (post.user.avatar != null) {
        avatar = b64ImageToUrl(post.user.avatar);
    } else {
        avatar = 'https://ui-avatars.com/api/background=0D8ABC&color=fff?name=' + post.user.user;
    }
    var postStr = `
        <div class="card mb-4 shadow">
            <div class="card-header">
                <a class="d-inline-flex align-items-center" style="text-decoration: none; color: inherit" href="profile?u=${post.user.user}">
                    <img src="${avatar}" width="32" height="32" class="rounded-circle me-2" alt="...">
                    <span class="align-middle h6 mb-1 me-2">${post.user.name}</span>
                    <small class="align-middle">@${post.user.user}</small>
                </a>
            </div>`;
        if (post.image != "" && post.image != null) {
            postStr += `<img src="${
                b64ImageToUrl(post.image)
            }" alt="...">`;
        }
        postStr += `
            <a href="post?p=${post.id}" style="text-decoration:none; color: inherit" class="card-text p-3">
                <p class="card-text">
                    ${realcarHashtags(post.text)}
                </p>
            </a>
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
                        ${post.commentsNum}
                </a>`;
            if (post.ismy == true) {
                postStr += `
                    <button class="btnPostDelete btn btn-sm btn-outline-danger ms-2">
                        <i class="fa fa-trash"></i>
                    </button>`; 
            }
            postStr += `
                <small class="text-body-secondary ms-auto" data-toggle="tooltip" data-placement="top" title="${brDate(post.dt)}">
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

export function toggleLikePost(token, btn) {
    const likeNum = btn.children('span').text();
    const postId = btn.parent().attr('value');
    $.ajax({
        type: "POST",
        url: "php/api/toggleLike.php",
        dataType: "json",
        data: {
            postId: postId,
            token: token
        },
        success: function(response) {
            if (response.success == true && response.liked == true) {
                btn.children('span').text(parseInt(likeNum)+1);
                btn.addClass('btn-primary');
                btn.removeClass('btn-outline-primary');
            } else if (response.success == true && response.liked == false) {
                btn.children('span').text(parseInt(likeNum)-1);
                btn.addClass('btn-outline-primary');
                btn.removeClass('btn-primary');
            }
        }
    });
}

export function toggleFollow(followedUser, token) {
    $.ajax({
        type: "POST",
        url: "php/api/toggleFollow.php",
        dataType: "json",
        data: {
            followedUser: followedUser,
            token: token
        },
        success: function(response) {
            console.log(response);
            if (response.success == true) {
                if (response.followed == true) {
                    $("#follow").html('<i class="fa fa-user-plus"></i> Seguido');
                    $("#follow").removeClass('btn-outline-primary');
                    $("#follow").addClass('btn-primary')
                    console.log('Seguindo');
                } else {
                    $("#follow").html('<i class="fa fa-user-plus"></i> Seguir');
                    $("#follow").removeClass('btn-primary');
                    $("#follow").addClass('btn-outline-primary')
                }
            }
        }
    });
}
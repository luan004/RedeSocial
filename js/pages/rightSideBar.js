import {
    b64ImageToUrl,
    getCookie,
    calcularTempoDecorrido
} from "../utils.js";

var page = window.location.pathname;
if (!page.includes("explore")) {
    $.ajax({
        type: "POST",
        url: "php/api/getHashtags.php",
        dataType: "json",
        data: {
            maxNumberOfHashtags: 5,
            opt: "today"
        },
        success: function(response) {
    
            // checar se o array é vazio
            if (response.length == 0) {
                $("#hashtags").hide();
                return;
            }
    
            response.forEach(hashtag => {
                $("#rightHashtags").append(`
                    <li class="list-group-item">
                        <span>${hashtag.word}</span>
                        <small class="float-end">${hashtag.count}</small>
                    </li>
                `);
            });
        }
    });
} else {
    $('#hashtags').hide();
}

$.ajax({
    type: "POST",
    url: "php/api/getRandomUsers.php",
    dataType: "json",
    success: function(response) {
        if (response.success == true) {

            for (let i = 0; i < response.users.length; i++) {
                const user = response.users[i];

                var avatar = null;
                if (user.avatar != null) {
                    avatar = b64ImageToUrl(user.avatar);
                } else {
                    avatar = 'https://ui-avatars.com/api/background=0D8ABC&color=fff?name=' + user.user;
                }

                $("#whoFollow").append(`
                    <a href="profile?u=${user.user}" class="list-group-item px-2">
                        <div class="d-inline-block position-relative">
                            <img src="${avatar}" width="40" height="40" class="rounded-circle" alt="">
                        </div>
                        ${user.name} <small class="text-muted">@${user.user}</small>
                    </a>
                `);
            }
        }
    }
});

const token = getCookie('token');

if (!page.includes('notifications')) {
    $.ajax({
        type: "POST",
        url: "php/api/getNotifications.php",
        dataType: "json",
        data: {
            token: token,
            limit: 5
        },
        success: function(response) {
            if (response.success == true) {
                if (response.notifications.length == 0) {
                    $("#lastNotificationsList").append(`
                        <small class="text-truncate text-center d-block">Nenhuma notificação</small>
                    `);
                }
                response.notifications.forEach(notification => {
                    var notificationStr = '';
    
                    var typeStr = '';
                    switch (notification.type) {
                        case 1: //follow
                            typeStr = ' começou a te seguir';
                            break;
                        case 2: //like
                            typeStr = ` curtiu seu <a href="post?p=${notification.post}" style="text-decoration:none">post</a>`;
                            break;
                        case 3: //comment
                            typeStr = ` comentou seu <a href="post?p=${notification.post}" style="text-decoration:none">post</a>`;
                            break;
                    }
    
                    var avatar = null;
                    if (notification.author.avatar != null) {
                        avatar = b64ImageToUrl(notification.author.avatar);
                    } else {
                        avatar = 'https://ui-avatars.com/api/background=0D8ABC&color=fff?name=' + notification.author.user;
                    }
    
                    $("#lastNotificationsList").append(`
                        <div class="card p-2 mb-2">
                            <div class="d-flex">
                                <a id="userBoxAvatarLink" href="">
                                    <img id="userBoxAvatar" width="42" height="42" src="${avatar}" class="rounded-circle me-2" alt="...">
                                </a>
                                <span class="d-grid">
                                    <span class="text-truncate"><a style="text-decoration:none" href="profile?u=${notification.author.user}">@${notification.author.user}</a>${typeStr}</span>
                                    <small id="userBoxUsername" class="text-body-secondary text-truncate">${calcularTempoDecorrido(notification.dt)}</small>
                                </span>
                            </div>
                        </div>
                    `);
                });
            }
        }
    });
} else {
    $('#rightSideNotifications').hide();
}
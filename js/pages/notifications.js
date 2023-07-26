import {
    b64ImageToUrl,
    getCookie,
    calcularTempoDecorrido
} from "../utils.js";

const token = getCookie('token');
$.ajax({
    type: "POST",
    url: "php/api/getNotifications.php",
    dataType: "json",
    data: {
        token: token,
        limit: 100
    },
    success: function(response) {
        if (response.success == true) {
            $('#notificationsNum').html(response.notifications.length);
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

                $("#notificationsList").append(`
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
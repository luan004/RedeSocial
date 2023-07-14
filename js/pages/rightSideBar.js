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
                $("#whoFollow").append(`
                    <a href="profile?u=${user.user}" class="list-group-item px-2">
                        <div class="d-inline-block position-relative">
                            <img src="${user.avatar}" width="40" height="40" class="rounded-circle" alt="">
                        </div>
                        ${user.name} <small class="text-muted">@${user.user}</small>
                    </a>
                `);
            }
        }
    }
});
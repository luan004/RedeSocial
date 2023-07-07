var page = window.location.pathname;
$.ajax({
    type: "POST",
    url: "php/api/getHashtags.php",
    dataType: "json",
    success: function(response) {
        if (response.success == true) {

            if (response.data[0] != false) {
                response.data.forEach(element => {
                    $("#rightHashtags").append(`
                        <li class="list-group-item">
                            <span>${element['word']}</span>
                            <small class="float-end">${element['count']}</small>
                        </li>
                    `);
                });
            } else {
                $("#hashtags").hide();
            }
        }
    }
});

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
                            <img src="${user.avatar}" width="40" height="40" class="rounded" alt="">
                        </div>
                        ${user.name}
                    </a>
                `);
            }
        }
    }
});
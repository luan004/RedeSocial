var page = window.location.pathname;
$.ajax({
    type: "POST",
    url: "php/getHashtags.php",
    dataType: "json",
    success: function(response) {
        if (response.success == true) {
            response.data.forEach(element => {
                $("#rightHashtags").append(`
                    <li class="list-group-item">
                        <span>${element['word']}</span>
                        <small class="float-end">${element['count']}</small>
                    </li>
                `);
            });
        }
    }
});

$.ajax({
    type: "POST",
    url: "php/getSomeUsers.php",
    dataType: "json",
    success: function(response) {
        if (response.count > 0) {
            var num = 1;
            while (num < response.count+1) {
                const user = response['u'+num];
                $("#whoFollow").append(`
                    <a href="profile?u=${user.user}" class="list-group-item px-2">
                        <div class="d-inline-block position-relative">
                            <img src="${user.avatar}" width="40" height="40" class="rounded" alt="">
                        </div>
                        ${user.name}
                    </a>
                `);
                num++;
            }
        }
    }
});
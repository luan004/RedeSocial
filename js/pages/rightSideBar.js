var page = window.location.pathname;
if (page.includes('/explore')) {
    $('#hashtags').hide();
} else {
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
}
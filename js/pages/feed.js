import {
    getCookie
} from "../others/functions.js";

const token = getCookie('token');
$.ajax({
    type: "POST",
    url: "php/isAuth.php",
    dataType: "json",
    data: {
        token: token
    },
    success: function(response) {
        if (response.auth == false) {
            window.location.href = "explore";
        }
    }
});

$.ajax({
    type: "POST",
    url: "php/getFeed.php",
    dataType: "json",
    data: {
        token: token
    },
    success: function(response) {
        if (response.auth == true) {
            var num = 1;
            var promises = []; // Array para armazenar as promessas das requisições Ajax

            while (num < response.count+1) {
                const post = response['p'+num];
                const promise = $.ajax({
                    type: "POST",
                    url: "php/getUserInfoById.php",
                    dataType: "json",
                    data: {
                        id: post.user_id
                    }
                });

                promises.push(promise); // Adiciona a promessa ao array
                num++;
            }

            Promise.all(promises)
                .then(function(responses) {
                    responses.forEach(function(response2, index) {
                        const post = response['p'+(index+1)];
                        var postStr = `
                            <div class="card mb-4 shadow">
                                <div class="card-header">
                                    <img src="${response2.avatar}" width="32" height="32" class="rounded-circle me-2" alt="...">
                                    <span class="align-middle h6">${response2.name}</span>
                                    <small class="ms-auto align-middle">@${response2.user}</small>
                                </div>`;

                            if (post.image != "" && post.image != null) {
                                postStr += `<img src="${post.image}" alt="...">`;
                            }

                            postStr += `
                                <div class="card-text p-3">
                                    <p class="card-text">
                                        ${post.text}
                                    </p>
                                </div>
                                <div class="card-footer d-flex">
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-thumbs-up"></i>
                                        ${post.likes}
                                    </button>
                                    <small class="text-body-secondary ms-auto">
                                        ${post.dt}
                                    </small>
                                </div>
                            </div>`;
                        $('#postsFeed').append(postStr);
                    });
                })
                .catch(function(error) {
                    console.error(error);
                });
        }
    }
});

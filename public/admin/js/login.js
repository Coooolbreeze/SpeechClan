var base = window.base;
base.getLocalStorage('token') && (window.location.href = 'home.html');

layui.use(['layer'], function () {
    var data = {
        username: '',
        password: ''
    }

    $('#login').on('click', function () {
        data.username = $('#username').val();
        data.password = $('#password').val();

        login(function (data) {
            base.setLocalStorage('token', data.access_token, data.expire_in * 1000);
            base.setLocalStorage('refresh_token', data.refresh_token);
            window.location.href = 'home.html';
        });
    });

    function login(callback) {
        var params = {
            url: '/login',
            type: 'POST',
            data: data,
            success: function (res) {
                callback && callback(res.data);
            }
        }
        base.getData(params);
    }
});
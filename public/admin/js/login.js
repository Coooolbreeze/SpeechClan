var base = window.base;
base.getLocalStorage('token') && (window.location.href = 'home.html');

layui.use(['layer'], function () {
    $(document).on('click', '#login', function () {
        var $userName = $('#username'),
            $pwd = $('#password');
        if (!$userName.val()) {
            $userName.next().show().find('div').text('请输入用户名');
            return;
        }
        if (!$pwd.val()) {
            $pwd.next().show().find('div').text('请输入密码');
            return;
        }
        var params = {
            url: '/login',
            type: 'POST',
            data: { username: $userName.val(), password: $pwd.val() },
            success: function (res) {
                base.setLocalStorage('token', res.data.access_token, res.data.expire_in * 1000);
                base.setLocalStorage('refresh_token', res.data.refresh_token);
                window.location.href = 'home.html';
            },
            fail: function (e) {
                $('.error-tips').text('帐号或密码错误').show().delay(2000).hide(0);
            }
        };
        base.getData(params);
    });
});
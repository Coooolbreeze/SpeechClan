var base = window.base;
base.getLocalStorage('token') || (window.location.href = 'login.html');

base.getData({
    url: '/users/self',
    tokenFlag: true,
    success: function (res) {
        console.log(res);
    }
});

layui.use(['element', 'layer'], function () {
    var element = layui.element;

    base.loadLocalHtml('order.html', '.layui-body');

    $('#homeBanner').on('click', function () {
        base.bannerType = 1;
        base.loadLocalHtml('banner.html', '.layui-body');
    });

    $('#aboutBanner').on('click', function () {
        base.bannerType = 2;
        base.loadLocalHtml('banner.html', '.layui-body');
    });

    $('#coruseBanner').on('click', function () {
        base.bannerType = 3;
        base.loadLocalHtml('banner.html', '.layui-body');
    });

    $('#teachBanner').on('click', function () {
        base.bannerType = 4;
        base.loadLocalHtml('banner.html', '.layui-body');
    });

    $('#studentBanner').on('click', function () {
        base.bannerType = 5;
        base.loadLocalHtml('banner.html', '.layui-body');
    });

    $('#clubBanner').on('click', function () {
        base.bannerType = 6;
        base.loadLocalHtml('banner.html', '.layui-body');
    });

    $('#aboutMe').on('click', function () {
        base.loadLocalHtml('about-me.html', '.layui-body');
    });

    $('#course').on('click', function () {
        base.loadLocalHtml('course.html', '.layui-body');
    });

    $('#club').on('click', function () {
        base.loadLocalHtml('club.html', '.layui-body');
    });

    $('#student').on('click', function () {
        base.loadLocalHtml('student.html', '.layui-body');
    });

    $('#teach').on('click', function () {
        base.loadLocalHtml('teach.html', '.layui-body');
    });

    $('#order').on('click', function () {
        base.loadLocalHtml('order.html', '.layui-body');
    });

    $('#logout').on('click', function () {
        base.deleteLocalStorage('token');
        base.deleteLocalStorage('refresh_token');
        window.location.href = 'login.html';
    });
});
var base = window.base;
base.getLocalStorage('token') || (window.location.href = 'login.html');

layui.use(['element', 'layer'], function () {
    var element = layui.element;

    base.loadLocalHtml('about-me.html', '.layui-body');

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
});
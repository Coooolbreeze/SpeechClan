window.base = {
    g_restUrl: 'http://speech.test/api',

    getData: function (params) {
        params.type || (params.type = 'get');
        var that = this;
        $.ajax({
            type: params.type,
            url: that.g_restUrl + params.url,
            data: params.data,
            beforeSend: function (XMLHttpRequest) {
                if (params.tokenFlag) {
                    var token = (params.tokenFlag === 'refresh' ? that.getLocalStorage('refresh_token') : that.getLocalStorage('token'));
                    XMLHttpRequest.setRequestHeader('token', token);
                }
            },
            success: function (res) {
                params.success && params.success(res);
            },
            error: function (err) {
                if (err.status == 401) {
                    this.deleteLocalStorage('token');
                    window.location.href = 'login.html';
                } else {
                    console.log(err);
                    params.fail && params.fail(err);
                }
            }
        });
    },

    setLocalStorage: function (key, val, exp) {
        exp || (exp = 30 * 24 * 60 * 60 * 1000);  //令牌过期时间
        var obj = {
            val: val,
            exp: new Date().getTime() + exp
        };
        localStorage.setItem(key, JSON.stringify(obj));
    },

    getLocalStorage: function (key) {
        var info = localStorage.getItem(key);
        if (info) {
            info = JSON.parse(info);
            if (info.exp > new Date().getTime()) {
                return info.val;
            }
            else {
                this.deleteLocalStorage('token');
            }
        }
        return '';
    },

    deleteLocalStorage: function (key) {
        return localStorage.removeItem(key);
    },

    getQueryString: function (name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]); return null;
    },

    loadLocalHtml: function (url, element) {
        $.ajax({
            url: url,
            type: 'get',
            success: function (res) {
                $(element).html(res);
            },
            error: function (err) {
                console.log(err);
            }
        });
    },

    strToTimestamp: function (str) {
        str = str.replace(/-/g, '/');
        var date = new Date(str);
        var time = date.getTime();
        return time;
    }
};
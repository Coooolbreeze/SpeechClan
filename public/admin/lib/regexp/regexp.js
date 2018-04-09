var regexp = {
    regular: {
        // 数字
        number: /^[0-9]*$/,
        // 最多两位小数的数字
        price: /(^([1-9]\d*)+(\.\d{1,2})?$)|(^0(\.\d{1,2})?$)/,
        // 字母
        alpha: /^[A-Za-z]+$/,
        // 字母和数字
        alphaNum: /^[A-Za-z0-9]+$/,
        // 字母、数字和下划线
        alphaDash: /^\w+$/,
        // 汉字
        chs: /^[\u4e00-\u9fa5]{0,}$/,
        // 汉字、字母和数字
        chsAlphaNum: /^[\u4e00-\u9fa5A-Za-z0-9]+$/,
        // 汉字、字母、数字和下划线
        chsDash: /^[\u4e00-\u9fa5A-Za-z0-9_]+$/,
        // 姓名
        name: /(^[\u4e00-\u9fa5]{1}[\u4e00-\u9fa5\.·。]{0,8}[\u4e00-\u9fa5]{0}$)|(^[a-zA-Z]{1}[a-zA-Z\s]{0,8}[a-zA-Z]{0}$)/,
        // 手机号
        phone: /^1[3-9]\d{9}$/,
        // 身份证号
        ID: /^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/,
        // 邮箱
        email: /^(\w)+(\.\w+)*@(\w)+((\.\w{2,3}){1,3})$/,
        // QQ号
        qq: /^[1-9][0-9]{4,}$/,
        // 账号/微信号 6~20个字母、数字、下划线或减号，以字母开头
        account: /^[a-zA-Z][-_a-zA-Z0-9]{5,19}$/,
        // 简单密码 6~18位字母、数字或下划线
        simPass: /^\w{6,18}$/,
        // 复杂密码 8~18位字母、数字或下划线，以字母开头，必须包含字母和数字
        // (?=.*\d)断言，该位置后肯定有数字
        // (?![0-9]+$)断言，该位置后不全是数字
        comPass: /^[a-zA-Z](?=.*\d)[_0-9a-zA-Z]{7,17}$/,
        // 域名
        activeUrl: null,
        // url
        url: /(https?|ftp|file):\/\/[-A-Za-z0-9+&@#/%?=~_|!:,\.;]+[-A-Za-z0-9+&@#/%=~_|]/,
        // ip
        ip: null
    },

    __construct: function (rule) {
        this.__rule = rule;
        this.__begin = null;
        this.__end = null;
        this.__name = null;
        this.__min = null;
        this.__max = null;

        this.__checkRule();

        if (this.__begin != 1) {
            this.__getName();
        }

        if (this.__begin >= 1) {
            this.__getLength();
        }
    },

    check: function (rule, string) {
        this.__construct(rule);

        if (this.__min && string.length < this.__min) {
            return false;
        }

        if (this.__max && string.length > this.__max) {
            return false;
        }

        if (this.__name && !this.regular[this.__name].test(string)) {
            return false;
        }

        return true;
    },

    set: function (regexp) {
        for (var key in regexp) {
            this.regular[key] = regexp[key];
        }
    },

    __checkRule: function () {
        var rule = this.__rule;
        var begin = rule.indexOf('[') + 1;
        var end = rule.indexOf(']');

        if (!rule) {
            throw new Error('请输入要验证的字段名或长度');
        }

        if (begin > 0) {
            if (end - begin < 1) {
                throw new Error('请将长度参数放在[]中');
            }
            this.__begin = begin;
            this.__end = end;
        }
    },

    __getName: function () {
        var end = this.__begin - 1;
        if (!this.__begin) {
            end = this.__rule.length;
        }
        var name = this.__rule.substring(0, end);
        if (!this.regular[name]) {
            throw new Error('"' + name + '"' + '规则未定义');
        }
        this.__name = name;
    },

    __getLength: function () {
        var number = this.__rule.substring(this.__begin, this.__end);
        var cut = number.indexOf(',');
        if (cut == number.length - 1) {
            throw new Error('长度参数错误');
        }
        if (cut < 0) {
            this.__min = number;
        } else if (cut == 0) {
            this.__max = number.substring(1);
        } else {
            this.__min = number.substring(0, cut);
            this.__max = number.substring(cut + 1);
        }
    }
}
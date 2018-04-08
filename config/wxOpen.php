<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/4/1
 * Time: 19:43
 */

return [
    /**
     * 微信开放平台的app_id
     */
    'app_id' => env('WX_OPEN_APP_ID'),

    /**
     * 微信开放平台的app_secret
     */
    'app_secret' => env('WX_OPEN_APP_SECRET'),

    /**
     * 微信登录的url
     */
    'login_url' => 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code',

    /**
     * 获取用户信息的url
     */
    'user_info_url' => 'https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s&lang=zh_CN',
];
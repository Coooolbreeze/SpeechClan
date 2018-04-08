<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/3/22
 * Time: 0:35
 */

return [
    /**
     * 访问令牌过期时间
     */
    'access_token_expire_in' => env('ACCESS_TOKEN_EXPIRE_IN'),

    /**
     * 刷新令牌过期时间
     */
    'refresh_token_expire_in' => env('REFRESH_TOKEN_EXPIRE_IN'),
];
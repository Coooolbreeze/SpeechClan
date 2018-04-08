<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/4/1
 * Time: 14:19
 */

namespace App\Exceptions;


class BindingLoginModeException extends BaseException
{
    public $message = '绑定登录方式失败';
    public $error_code = 20004;
    public $code = 400;
}
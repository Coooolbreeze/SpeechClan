<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/3/18
 * Time: 17:09
 */

namespace App\Exceptions;


class PasswordErrorException extends BaseException
{
    public $message = '密码错误';
    public $code = 401;
    public $error_code = 20001;
}
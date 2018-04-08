<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/3/18
 * Time: 16:35
 */

namespace App\Exceptions;


class TokenException extends BaseException
{
    public $message = 'Token不存在或已过期';
    public $error_code = 10001;
    public $code = 401;
}
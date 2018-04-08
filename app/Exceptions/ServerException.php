<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/3/22
 * Time: 1:26
 */

namespace App\Exceptions;


class ServerException extends BaseException
{
    public $message = '服务器内部错误';
    public $error_code = 10005;
    public $code = 500;
}
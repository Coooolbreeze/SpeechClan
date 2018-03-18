<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/3/18
 * Time: 17:12
 */

namespace App\Exceptions;


class ForbiddenException extends BaseException
{
    public $message = '权限不足';
    public $code = 403;
    public $error_code = 10003;
}
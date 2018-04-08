<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/4/1
 * Time: 18:00
 */

namespace App\Exceptions;


class RePasswordException extends BaseException
{
    public $message = '修改密码失败';
    public $error_code = 20005;
    public $code = 400;
}
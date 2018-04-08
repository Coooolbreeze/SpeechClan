<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/4/1
 * Time: 1:00
 */

namespace App\Exceptions;


class AccountIsExistException extends BaseException
{
    public $message = '该账号已被注册';
    public $error_code = 20002;
    public $code = 409;
}
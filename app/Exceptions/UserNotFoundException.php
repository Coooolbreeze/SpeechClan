<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/3/18
 * Time: 17:00
 */

namespace App\Exceptions;


class UserNotFoundException extends BaseException
{
    public $message = '该用户不存在';
    public $code = 404;
    public $error_code = 20000;
}
<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/4/1
 * Time: 2:19
 */

namespace App\Exceptions;


class RegisterException extends BaseException
{
    public $message = '注册失败';
    public $error_code = 20003;
    public $code = 400;
}
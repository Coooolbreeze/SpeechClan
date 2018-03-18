<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/3/18
 * Time: 17:05
 */

namespace App\Exceptions;


class ResourceNotFoundException extends BaseException
{
    public $message = '请求的资源不存在';
    public $code = 404;
    public $error_code = 30000;
}
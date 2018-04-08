<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/3/31
 * Time: 0:13
 */

namespace App\Exceptions;


class WeChatException extends BaseException
{
    public $message = '微信服务器接口调用失败';
    public $error_code = 999;
    public $code = 400;
}
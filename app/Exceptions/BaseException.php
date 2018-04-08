<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/3/18
 * Time: 15:58
 */

namespace App\Exceptions;

use Exception;

class BaseException extends Exception
{
    protected $message = '请求出错';
    protected $code = 400;
    protected $error_code = 999;

    public function __construct(string $message = "", int $error_code = 0, int $code = 0)
    {
        $message && $this->message = $message;
        $error_code && $this->error_code = $error_code;
        $code && $this->code = $code;
    }

    public final function getErrorCode()
    {
        return $this->error_code;
    }
}
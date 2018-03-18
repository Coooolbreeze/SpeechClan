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

    public function __construct(string $message = "", int $code = 0, int $error_code = 0)
    {
        $message && $this->message = $message;
        $code && $this->code = $code;
        $error_code && $this->error_code = $error_code;
    }

    public final function getErrorCode()
    {
        return $this->error_code;
    }
}
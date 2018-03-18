<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/3/18
 * Time: 15:41
 */

namespace App\Api\Helpers\Api;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ExceptionReport
{
    use ApiResponse;

    /**
     * @var Exception
     */
    public $exception;
    /**
     * @var Request
     */
    public $request;

    /**
     * @var
     */
    protected $report;

    /**
     * ExceptionReport constructor.
     * @param Request $request
     * @param Exception $exception
     */
    function __construct(Request $request, Exception $exception)
    {
        $this->request = $request;
        $this->exception = $exception;
    }

    /**
     * @return array
     */
    public function doReport()
    {
        return [
            AuthenticationException::class => ['未授权', 401],
            ModelNotFoundException::class => ['该模型未找到', 404],
            ValidationException::class => [],
        ];
    }

    /**
     * @return bool
     */
    public function shouldReturn()
    {
        if (!($this->request->wantsJson() || $this->request->ajax())) {
            return false;
        }

        foreach (array_keys($this->doReport()) as $report) {
            if ($this->exception instanceof $report) {
                $this->report = $report;
                return true;
            }
        }

        return false;
    }

    /**
     * @param Exception $e
     * @return static
     */
    public static function make(Exception $e)
    {
        return new static(\request(), $e);
    }

    /**
     * @return mixed
     */
    public function report()
    {
        if ($this->exception instanceof ValidationException) {
            return $this->failed($this->exception->errors());
        }

        $message = $this->doReport()[$this->report];

        return $this->failed($message[0], $message[1]);
    }
}
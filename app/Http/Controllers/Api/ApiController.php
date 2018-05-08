<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/3/18
 * Time: 15:39
 */

namespace App\Http\Controllers\Api;


use App\Api\Helpers\Api\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\Tokens\TokenFactory;
use App\Exceptions\BaseException;

class ApiController extends Controller
{
    use ApiResponse;

    public function isAdmin()
    {
        try {
            return TokenFactory::getCurrentGuard() === 'super';
        } catch (BaseException $e) {
            return false;
        }
    }
}
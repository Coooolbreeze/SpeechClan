<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/4/8
 * Time: 21:26
 */

namespace App\Http\Controllers\Api;


use App\Exceptions\RegisterException;
use App\Exceptions\UserNotFoundException;
use Illuminate\Http\Request;
use App\Services\Tokens\TokenFactory;

class TokenController extends ApiController
{
    /**
     * 用户登录
     *
     * @param Request $request
     * @return mixed
     * @throws UserNotFoundException
     * @throws RegisterException
     * @throws \App\Exceptions\ServerException
     */
    public function login(Request $request)
    {
        $username = $request->post('username');
        $password = $request->post('password');
        $verificationCode = $request->post('verification_code');
        $code = $request->post('code');

        // 账号+密码登录
        if ($username && $password) {
            if (!preg_match('/^\w{6,18}$/', $password)) {
                throw new RegisterException('密码为6~18位字母、数字或下划线');
            }

            if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
                // 邮箱登录
                $token = TokenFactory::email()->get();
            } elseif (preg_match('/^1[3-9]\d{9}$/', $username)) {
                // 手机号登录
                $token = TokenFactory::phone()->get();
            } elseif (preg_match('/^[a-zA-Z][-_a-zA-Z0-9]{5,19}$/', $username)) {
                // 账号登录
                $token = TokenFactory::account()->get();
            }
        } // 账号+验证码登录
        elseif ($username && $verificationCode) {
            // TODO 检测验证码正确性


            if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
                // 邮箱登录
                $token = TokenFactory::verificationCode('email')->get();
            } elseif (preg_match('/^1[3-9]\d{9}$/', $username)) {
                // 手机号登录
                $token = TokenFactory::verificationCode('phone')->get();
            }
        } // 微信登录
        elseif ($code) {
            $token = TokenFactory::weChat()->get();
        }

        if (!isset($token)) {
            throw new UserNotFoundException();
        }

        return $this->success($token);
    }

    /**
     * 用户注册
     *
     * @param Request $request
     * @return mixed
     * @throws RegisterException
     * @throws \App\Exceptions\AccountIsExistException
     */
    public function register(Request $request)
    {
        $username = $request->post('username');
        $password = $request->post('password');
        $verificationCode = $request->post('verification_code');

        if (!preg_match('/^\w{6,18}$/', $password)) {
            throw new RegisterException('密码为6~18位字母、数字或下划线');
        }

        // TODO 检测验证码正确性


        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            // 邮箱注册
            $token = TokenFactory::email()->create();
        } elseif (preg_match('/^1[3-9]\d{9}$/', $username)) {
            // 手机号注册
            $token = TokenFactory::phone()->create();
        }

        if (!isset($token)) {
            throw new RegisterException('请输入正确的邮箱或手机号');
        }

        return $this->success($token);
    }

    /**
     * 刷新token令牌
     *
     * @return mixed
     * @throws \App\Exceptions\ServerException
     */
    public function refresh()
    {
        $token = TokenFactory::refresh()->get();

        return $this->success($token);
    }
}
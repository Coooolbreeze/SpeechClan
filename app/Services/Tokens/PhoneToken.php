<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/3/29
 * Time: 10:41
 */

namespace App\Services\Tokens;


use App\Exceptions\AccountIsExistException;
use App\Exceptions\BindingLoginModeException;
use App\Exceptions\PasswordErrorException;
use App\Exceptions\RegisterException;
use App\Exceptions\UserNotFoundException;
use App\Models\User;
use App\Models\UserAuth;
use Closure;
use DB;
use Exception;
use Hash;

class PhoneToken extends BaseToken
{
    private $phone;
    private $password;

    public function __construct($phone, $password)
    {
        $this->phone = $phone;
        $this->password = $password;
    }

    /**
     * 获取用户身份
     *
     * @return mixed
     */
    private function getIdentity()
    {
        $identity = (new $this->model)->where('platform', 'local')
            ->where('identity_type', 'phone')
            ->where('identifier', $this->phone)
            ->first();

        return $identity;
    }

    /**
     * 实现父类方法，返回用户身份实例
     *
     * @return mixed|static
     * @throws PasswordErrorException
     * @throws UserNotFoundException
     */
    public function identity()
    {
        $identity = $this->getIdentity();
        if (!$identity) {
            throw new UserNotFoundException();
        }

        if (!Hash::check($this->password, $identity->credential)) {
            throw new PasswordErrorException();
        }

        return $identity;
    }

    /**
     * 创建用户账号
     *
     * @return mixed
     * @throws AccountIsExistException
     * @throws Exception
     * @throws RegisterException
     */
    public function create()
    {
        $identity = $this->getIdentity();

        if ($identity) {
            throw new AccountIsExistException('该手机号已被使用');
        }

        DB::beginTransaction();
        try {
            // 创建用户信息
            $user = User::create([
                'nickname' => '小萌新',
                'avatar' => 'https://lwx-images.oss-cn-beijing.aliyuncs.com/avatar.jpg',
                'phone' => $this->phone,
                'is_bind_phone' => 1
            ]);

            // 创建用户登录信息
            (new $this->model)->create([
                'user_id' => $user->id,
                'platform' => 'local',
                'identity_type' => 'phone',
                'identifier' => $this->phone,
                'credential' => Hash::make($this->password),
                'verified' => 1
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new RegisterException();
        }

        return $this->get();
    }

    /**
     * 绑定手机号
     *
     * @param $phone
     * @param Closure $sendPassword
     * @throws AccountIsExistException
     * @throws BindingLoginModeException
     * @throws Exception
     * @throws \App\Exceptions\TokenException
     */
    public static function bind($phone, Closure $sendPassword)
    {
        // 获取用户id
        $uid = TokenFactory::getCurrentUID();

        // 查看用户是否已绑定过手机号
        $user = User::find($uid);
        if ($user->is_bind_phone == 1) {
            throw new BindingLoginModeException('该账号已绑定手机号，无法继续绑定', 0, 409);
        }

        // 查看该手机号是否已被使用
        $identity = UserAuth::where('platform', 'local')
            ->where('identity_type', 'phone')
            ->where('identifier', $phone)
            ->first();
        if ($identity) {
            throw new AccountIsExistException('该手机号已被使用');
        }

        // 如果用户已有站内账号，使用站内密码，否则生成随机密码
        $localMode = UserAuth::where('user_id', $uid)
            ->where('platform', 'local')
            ->first();
        if ($localMode) {
            $password = $localMode->credential;
        } else {
            $randChar = getRandChar(8);
            $password = Hash::make($randChar);
        }

        DB::beginTransaction();
        try {
            // 为用户创建手机号登录方式
            UserAuth::create([
                'user_id' => $uid,
                'platform' => 'local',
                'identity_type' => 'phone',
                'identifier' => $phone,
                'credential' => $password,
                'verified' => 1
            ]);

            // 更新用户信息手机号为已绑定
            $user->phone = $phone;
            $user->is_bind_phone = 1;
            $user->save();

            DB::commit();

            isset($randChar) && $sendPassword && $sendPassword($randChar);
        } catch (Exception $e) {
            DB::rollBack();
            throw new BindingLoginModeException('绑定手机号失败');
        }
    }
}
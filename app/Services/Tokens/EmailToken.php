<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/4/2
 * Time: 23:48
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

class EmailToken extends BaseToken
{
    private $email;
    private $password;

    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    private function getIdentity()
    {
        $identity = (new $this->model)->where('platform', 'local')
            ->where('identity_type', 'email')
            ->where('identifier', $this->email)
            ->first();

        return $identity;
    }

    /**
     * 实现父类方法，返回用户实例
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
            throw new AccountIsExistException('该邮箱已被使用');
        }

        DB::beginTransaction();
        try {
            // 创建用户信息
            $user = User::create([
                'nickname' => '小萌新',
                'avatar' => 'https://lwx-images.oss-cn-beijing.aliyuncs.com/avatar.jpg',
                'email' => $this->email,
                'is_bind_email' => 1
            ]);

            // 创建用户登录信息
            (new $this->model)->create([
                'user_id' => $user->id,
                'platform' => 'local',
                'identity_type' => 'email',
                'identifier' => $this->email,
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
     * 绑定邮箱
     *
     * @param $email
     * @param Closure $sendPassword
     * @throws AccountIsExistException
     * @throws BindingLoginModeException
     * @throws Exception
     * @throws \App\Exceptions\TokenException
     */
    public static function bind($email, Closure $sendPassword)
    {
        // 获取用户id
        $uid = TokenFactory::getCurrentUID();

        // 查看用户是否已绑定过邮箱
        $user = User::find($uid);
        if ($user->is_bind_email == 1) {
            throw new BindingLoginModeException('该账号已绑定邮箱，无法继续绑定', 0, 409);
        }

        // 查看该邮箱是否已被使用
        $identity = UserAuth::where('platform', 'local')
            ->where('identity_type', 'email')
            ->where('identifier', $email)
            ->first();
        if ($identity) {
            throw new AccountIsExistException('该邮箱已被使用');
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
            // 为用户创建邮箱登录方式
            UserAuth::create([
                'user_id' => $uid,
                'platform' => 'local',
                'identity_type' => 'email',
                'identifier' => $email,
                'credential' => $password,
                'verified' => 1
            ]);

            // 更新用户信息邮箱为已绑定
            $user->email = $email;
            $user->is_bind_email = 1;
            $user->save();

            DB::commit();

            isset($randChar) && $sendPassword && $sendPassword($randChar);
        } catch (Exception $e) {
            DB::rollBack();
            throw new BindingLoginModeException('绑定邮箱失败');
        }
    }
}
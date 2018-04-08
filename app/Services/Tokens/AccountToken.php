<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/4/8
 * Time: 15:18
 */

namespace App\Services\Tokens;


use App\Exceptions\AccountIsExistException;
use App\Exceptions\PasswordErrorException;
use App\Exceptions\RegisterException;
use App\Exceptions\UserNotFoundException;
use App\Models\User;
use Exception;
use Hash;

class AccountToken extends BaseToken
{
    private $account;
    private $password;

    public function __construct($account, $password)
    {
        $this->account = $account;
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
            ->where('identity_type', 'account')
            ->where('identifier', $this->account)
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
            throw new AccountIsExistException('该账号已存在');
        }

        DB::beginTransaction();
        try {
            // 创建用户信息
            $user = User::create([
                'nickname' => '小萌新',
                'avatar' => 'https://lwx-images.oss-cn-beijing.aliyuncs.com/avatar.jpg',
                'account' => $this->account,
                'is_bind_account' => 1
            ]);

            // 创建用户登录信息
            (new $this->model)->create([
                'user_id' => $user->id,
                'platform' => 'local',
                'identity_type' => 'account',
                'identifier' => $this->account,
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
}
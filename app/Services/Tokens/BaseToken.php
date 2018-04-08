<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/3/21
 * Time: 0:57
 */

namespace App\Services\Tokens;

use App\Exceptions\ServerException;
use App\Models\Token as TokenModel;
use App\Models\User;
use App\Models\UserAuth;
use App\Services\Permission;
use Cache;

abstract class BaseToken
{
    /**
     * 登录模型
     *
     * @var string
     */
    protected $model = UserAuth::class;

    /**
     * 获取用户身份实例，子方法中实现
     *
     * @return mixed
     */
    abstract public function identity();

    /**
     * 用三组字符串进行md5加密，返回随机字符串
     *
     * @param bool $refresh
     * @return string
     */
    public function generateToken(bool $refresh = false)
    {
        // 指定长度的随机字符串
        $randChars = getRandChar($refresh ? 64 : 32);
        // 当前时间戳
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        // 应用密钥
        $key = config('app.key');

        return md5($randChars . $timestamp . $key);
    }

    /**
     * 需要保存到token中的信息
     *
     * @param $identity
     * @return array
     */
    public function needSaveValues($identity)
    {
        $uid = $identity->user_id;
        $guard = User::findOrFail($uid)->guard;
//        $permissions = (new Permission())->getUserPermissions($uid);

        $values = [
            'uid' => $uid,
            'platform' => $identity->platform,
            'identity_type' => $identity->identity_type,
            'identifier' => $identity->identifier,
            'guard' => $guard,
//            'permissions' => $permissions
        ];

        return $values;
    }

    /**
     * 获取token
     *
     * @return array
     * @throws ServerException
     */
    public function get()
    {
        $identity = $this->identity();

        $cacheValues = $this->needSaveValues($identity);

        return $this->saveToCache($cacheValues);
    }

    /**
     * 存储token
     *
     * @param $cacheValues
     * @return array
     * @throws ServerException
     */
    public function saveToCache($cacheValues)
    {
        $value = json_encode($cacheValues);
        // 生成access_token
        $access_token = $this->generateToken();
        // 生成refresh_token
        $refresh_token = $this->generateToken(true);
        // access_token有效期
        $access_expire_in = config('setting.access_token_expire_in');
        // refresh_token有效期
        $refresh_expire_in = config('setting.refresh_token_expire_in');
        // 存储access_token
        $result = Cache::add($access_token, $value, $access_expire_in / 60);
        if (!$result) {
            throw new ServerException('服务器缓存异常');
        }
        // 存储refresh_token
        $refreshValue = json_encode([
            'info' => $cacheValues
        ]);
        $result = Cache::add('refresh:' . $refresh_token, $refreshValue, $refresh_expire_in / 60);
        if (!$result) {
            Cache::forget($access_token);
            throw new ServerException('服务器缓存异常');
        }

        // 从缓存中移除旧token
        TokenFactory::removeToken($cacheValues['uid']);

        // 将新生成的token存到数据库中
        TokenModel::updateOrCreate(
            ['user_id' => $cacheValues['uid']],
            ['access_token' => $access_token, 'refresh_token' => $refresh_token]
        );

        return [
            'access_token' => $access_token,
            'refresh_token' => $refresh_token,
            'expire_in' => $access_expire_in
        ];
    }
}
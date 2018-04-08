<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/3/30
 * Time: 0:09
 */

namespace App\Services\Tokens;


use App\Exceptions\TokenException;
use Cache;

class RefreshToken extends BaseToken
{
    private $refreshToken;

    public function __construct($refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }

    /**
     * 获取用户身份
     *
     * @return mixed
     * @throws TokenException
     */
    public function identity()
    {
        $vars = Cache::get('refresh:' . $this->refreshToken);
        if (!$vars) {
            throw new TokenException('refresh_token不存在或已过期');
        }

        if (!is_array($vars)) {
            $vars = json_decode($vars, true);
        }

        $identity = (new $this->model)->where('platform', $vars['info']['platform'])
            ->where('identity_type', $vars['info']['identity_type'])
            ->where('identifier', $vars['info']['identifier'])
            ->first();

        if (!$identity) {
            throw new TokenException('刷新失败，请重新登录');
        }

        return $identity;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/3/29
 * Time: 20:38
 */

namespace App\Services\Tokens;


use App\Exceptions\AccountIsExistException;
use App\Exceptions\BindingLoginModeException;
use App\Exceptions\RegisterException;
use App\Exceptions\TokenException;
use App\Exceptions\WeChatException;
use App\Models\User;
use Exception;
use DB;

class WeChatToken extends BaseToken
{
    private $code;
    private $appID;
    private $appSecret;
    private $loginUrl;
    private $userInfoUrl;

    /**
     * 微信开放平台open OR 微信公众平台media
     *
     * @var string
     */
    private $identityType;

    public function __construct($code, $identityType = 'open')
    {
        $this->code = $code;
        $this->identityType = $identityType;

        if ($this->identityType == 'open') {
            $this->appID = config('wxOpen.app_id');
            $this->appSecret = config('wxOpen.app_secret');
            $this->userInfoUrl = config('wxOpen.user_info_url');
            $this->loginUrl = sprintf(config('wxOpen.login_url'), $this->appID, $this->appSecret, $this->code);
        }

        if ($this->identityType == 'media') {
            $this->appID = config('wxMedia.app_id');
            $this->appSecret = config('wxMedia.app_secret');
            $this->userInfoUrl = config('wxMedia.user_info_url');
            $this->loginUrl = sprintf(config('wxMedia.login_url'), $this->appID, $this->appSecret, $this->code);
        }
    }

    /**
     * 获取用户身份
     *
     * @return WeChatToken|\Illuminate\Database\Eloquent\Model|mixed
     * @throws Exception
     * @throws WeChatException
     */
    public function identity()
    {
        // 获取access_token及openid
        $wxBaseInfo = $this->getWxResult($this->loginUrl);
        $openid = $wxBaseInfo['openid'];
        $accessToken = $wxBaseInfo['access_token'];

        // 查找用户身份
        $identity = (new $this->model)->where('platform', 'wx')
            ->where('identity_type', $this->identityType)
            ->where('identifier', $openid)
            ->first();

        // 如果身份不存在，则创建用户身份
        if (!$identity) {
            $identity = $this->createUserIdentity($openid, $accessToken);
        }

        return $identity;
    }

    /**
     * 创建用户身份
     *
     * @param $openid
     * @param $accessToken
     * @return mixed
     * @throws Exception
     * @throws RegisterException
     * @throws WeChatException
     */
    public function createUserIdentity($openid, $accessToken)
    {
        // 拼接获取用户详细信息的url
        $userInfoUrl = sprintf($this->userInfoUrl, $accessToken, $openid);
        // 获取用户详细信息
        $wxUserInfo = $this->getWxResult($userInfoUrl);

        DB::beginTransaction();
        try {
            // 添加用户信息
            $user = User::create([
                'nickname' => $wxUserInfo['nickname'],
                'avatar' => $wxUserInfo['headimgurl'],
                'sex' => $wxUserInfo['sex'],
                'is_bind_wx' . $this->identityType => 1
            ]);
            $uid = $user->id;

            // 添加用户身份信息
            $identity = (new $this->model)->create([
                'user_id' => $uid,
                'platform' => 'wx',
                'identity_type' => $this->identityType,
                'identifier' => $openid,
                'credential' => $accessToken,
                'remark' => $wxUserInfo['unionid'],
                'verified' => 1
            ]);

            DB::commit();
            return $identity;
        } catch (Exception $e) {
            DB::rollBack();
            throw new RegisterException();
        }
    }

    /**
     * 绑定微信号
     *
     * @throws AccountIsExistException
     * @throws BindingLoginModeException
     * @throws Exception
     * @throws TokenException
     * @throws WeChatException
     */
    public function bind()
    {
        $uid = TokenFactory::getCurrentUID();

        // 查看用户是否已绑定微信号
        $user = User::find($uid);
        if (($this->identityType == 'open' ? $user->is_bind_wxopen : $user->is_bind_wxmedia) == 1) {
            throw new BindingLoginModeException('该账号已绑定微信号，无法继续绑定', 0, 409);
        }

        // 获取access_token及openid
        $wxBaseInfo = $this->getWxResult($this->loginUrl);
        $openid = $wxBaseInfo['openid'];
        $accessToken = $wxBaseInfo['access_token'];

        // 查看该微信号是否已被使用
        $userAuth = (new $this->model)->where('platform', 'wx')
            ->where('identity_type', $this->identityType)
            ->where('identifier', $openid)
            ->first();
        if ($userAuth) {
            throw new AccountIsExistException('该微信号已被使用');
        }

        // 拼接获取用户详细信息的url
        $userInfoUrl = sprintf($this->userInfoUrl, $accessToken, $openid);
        // 获取用户详细信息
        $wxUserInfo = $this->getWxResult($userInfoUrl);

        DB::beginTransaction();
        try {
            // 为用户创建微信登录方式
            (new $this->model)->create([
                'user_id' => $uid,
                'platform' => 'wx',
                'identity_type' => $this->identityType,
                'identifier' => $openid,
                'credential' => $accessToken,
                'remark' => $wxUserInfo['unionid'],
                'verified' => 1
            ]);

            // 更新用户微信号为已绑定
            $this->identityType == 'open' ? $user->is_bind_wxopen = 1 : $user->is_bind_wxmedia = 1;
            $user->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new BindingLoginModeException('绑定微信号失败');
        }
    }

    /**
     * 调用微信接口
     *
     * @param $url
     * @return mixed
     * @throws Exception
     * @throws WeChatException
     */
    public function getWxResult($url)
    {
        $result = curl($url);

        $wxResult = json_decode($result, true);

        if (empty($wxResult)) {
            throw new Exception('获取用户信息失败，微信内部错误');
        }

        if (array_key_exists('errcode', $wxResult)) {
            throw new WeChatException($wxResult['errmsg'], $wxResult['errcode']);
        }

        return $wxResult;
    }
}
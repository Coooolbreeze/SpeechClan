<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/4/7
 * Time: 23:44
 */

namespace App\Services\Tokens;


use App\Exceptions\UserNotFoundException;

class VerificationCodeToken extends BaseToken
{
    private $type;
    private $identifier;

    public function __construct($type, $identifier)
    {
        $this->type = $type;
        $this->identifier = $identifier;
    }

    /**
     * 获取用户身份实例
     *
     * @return mixed
     * @throws UserNotFoundException
     */
    public function identity()
    {
        $identity = (new $this->model)->where('identity_type', $this->type)
            ->where('identifier', $this->identifier)
            ->first();

        if (!$identity) {
            throw new UserNotFoundException();
        }

        return $identity;
    }
}
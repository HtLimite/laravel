<?php

namespace App\Commont\Auth;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;

/**
 * 单例 一次请求中所有请求Jwt的地方都是一个用户 进程单例
 */
class JwtAuth
{

    /**
     * jwt token
     * @var
     */
    private $token;

    /**
     * 发行人
     * @var string
     */
    private string $issuer = 'https://lim.htwyy.cn/api';

    /**
     * 观众
     * @var string
     */
    private string $audience = 'lim';

    /**
     * 用户uid
     * claim uid
     * @var
     */
    private $uid;

    /**
     *私密密匙 用于签证加密算法 随机字符串
     * @var string
     */
    private string $secrect = '&$^%#^&*(&$%&*(**$&)(';

    /**
     * decode token
     * 客户端传上来的 token
     * @var
     */
    private $decodeToken;

    /**
     * 单例模式 JwtAuth句柄
     * @var
     */
    private static $instance;

    /**
     * 获取JwtAuth句柄
     * @return JwtAuth
     */
    public static function getInstance(): JwtAuth
    {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * 防止调用类
     * 私有化构造函数
     */
    private function __construct()
    {
    }

    /*
     * 防止调用类
     * 私有化clone函数
     */
    private function __clone()
    {
    }

    /**
     * 获取token
     * @return string
     */
    public function getToken(): string
    {
        return (string)$this->token;
    }

    /**
     * 设置 token
     * @param $token
     * @return $this
     */
    public function setToken($token): JwtAuth
    {
        $this->token = $token;

        return $this;
    }

    /**
     * uid
     * @param $uid
     * @return $this
     */
    public function setUid($uid): JwtAuth
    {
        $this->uid  = $uid;

        return $this;
    }

    /**
     * 编码 JWt
     * @return $this
     */
    public function encode(): JwtAuth
    {
        $time = time();
        $this->token = (new Builder())->setHeader('alg','HS256')
            //不敏感信息
            ->setIssuer($this->issuer)
            ->setAudience($this->audience)
            //颁发时间
            ->setIssuedAt($time)
             //过期时间 s
            ->setExpiration($time + 60)
            ->set('uid',$this->uid)
            //签名
            ->sign(new Sha256(), $this->secrect)
            ->getToken();

        return $this;
    }

    /**
     * 解码 字符串 token
     * parse string token
     * @return Token
     */
    public function decode(): Token
    {
        if (!$this->decodeToken) {
            $this->decodeToken = (new Parser())->parse((string)$this->token);
            $this->uid = $this->decodeToken->getClaim('uid');
        }

        return $this->decodeToken;
    }

    /**
     * 验证 token 签名 secret 是否被篡改
     * verify token
     * @return bool
     */
    public function verify(): bool
    {
        $result = $this->decode()->verify(new Sha256(), $this->secrect);

        return $result;
    }

    /**
     * 验证 token 是否过期  issuer audience
     * validate
     * @return bool
     */
    public function validate(): bool
    {
        $data = new ValidationData();
        $data->setIssuer($this->issuer);
        $data->setAudience($this->audience);

        return $this->decode()->validate($data);
    }
}

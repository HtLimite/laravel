<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;



use function var_dump;

class JwtAuthController extends Controller
{
    public function jwt()
    {


        $key = 'example_key';
        $exception = time() + 10;
//        $payload = [
//            'iss' => 'http://example.org',
//            'aud' => 'http://example.com',
//            'iat' => time(),
//            'nbf' => $exception
//        ];
        $payload  = [
            'iss' => 'http://www.helloweba.net', //签发者 可选
            'aud' => 'http://www.helloweba.net', //接收该JWT的一方，可选
            'iat' => time(), //签发时间
            'nbf' => time() , //(Not Before)：某个时间点后才能访问，比如设置time+30，表示当前时间30秒后才能使用
            'exp' => $exception, //过期时间
            'data' => [ //自定义信息，不要定义敏感信息
                'userid' => 1,
                'username' => '李小龙'
            ]
        ];

        /**
         * IMPORTANT:
         * You must specify supported algorithms for your application. See
         * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
         * for a list of spec-compliant algorithms.
         */
        $jwt = JWT::encode($payload, $key, 'HS256');

        print_r($jwt);
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));


        /*
         NOTE: This will now be an object instead of an associative array. To get
         an associative array, you will need to cast it as such:
        */

//        $decoded_array = (array) $decoded;

        /**
         * You can add a leeway to account for when there is a clock skew times between
         * the signing and verifying servers. It is recommended that this leeway should
         * not be bigger than a few minutes.
         *
         * Source: http://self-issued.info/docs/draft-ietf-oauth-json-web-token.html#nbfDef
         */
//        JWT::$leeway = 60; // $leeway in seconds
//        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    }
    public function reg()
    {
        return response([
            'msg' => 'success'
        ],201);
    }
}

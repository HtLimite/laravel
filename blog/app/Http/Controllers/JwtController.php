<?php

namespace App\Http\Controllers;

use App\Commont\Auth\JwtAuth;
use Illuminate\Http\Request;

class JwtController extends Controller
{
    public function login(Request $request)
    {

        $username = $request->input('username');
        $password = $request->input('password');

        // 去数据库或者缓存中验证该用户  uid 用户信息的uid

        // 验证成功 返回JWT token
        // 获取jwt一个句柄
        $jwtAuth = JwtAuth::getInstance();
        //编码
        $token = $jwtAuth->setUid(1)->encode()->getToken();

        return $token;
    }
    public function logined()
    {
        return response([
            'msg' => 'success'
        ],201);
    }
}

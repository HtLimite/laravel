<?php

namespace App\Http\Middleware\JWT;

use App\Commont\Auth\JwtAuth;
use Closure;

class JwtVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $token = $request->input('token');

        if ($token){
            $jwtAuth = JwtAuth::getInstance();
            $jwtAuth->setToken($token);

            if ($jwtAuth->validate() && $jwtAuth->verify()){
                return $next($request);
            }else{
                return response([
                    'msg' => '身份过期'
                ],401);
            }
        }else{

            return response([
                'msg' => '参数错误'
            ],401);
        }
    }
}

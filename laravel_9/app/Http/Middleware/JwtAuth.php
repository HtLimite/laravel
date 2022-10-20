<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class JwtAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $key = 'example_key';
        $jwt = $request->input('token');
        if( empty($jwt)){
            return response([
                'msg' => '无效的访问'
            ],401);
        }
//        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        try {

//            JWT::$leeway = 60;//当前时间减去60，把时间留点余地
            $decoded = JWT::decode($jwt,  new Key($key, 'HS256')); //HS256方式，这里要和签发的时候对应
            return $next($request);

//            print_r($arr);
        } catch(\Firebase\JWT\SignatureInvalidException $e) {
            //签名不正确
            return response([
                'msg' => '签名不正确'
            ],401);
        }catch(\Firebase\JWT\BeforeValidException $e) {
            // 签名在某个时间点之后才能用
            echo $e->getMessage();
        }catch(\Firebase\JWT\ExpiredException $e) {
            // token过期
            return response([
                'msg' => '签名过期'
            ],401);
        }catch(Exception $e) {
            //其他错误
            return response([
                'msg' => '未知错误'
            ],401);
        }
        //Firebase定义了多个 throw new，我们可以捕获多个catch来定义问题，catch加入自己的业务，比如token过期可以用当前Token刷新一个新Token


    }
}

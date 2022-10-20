<?php

namespace App\Http\Middleware;

use Closure;

class adminLogin
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
        //判断是否登录
//        if(session('lenovoAdminInfo')){
            return $next($request);
//        }else{
//            return redirect('admin/login');
//        }
    }
}

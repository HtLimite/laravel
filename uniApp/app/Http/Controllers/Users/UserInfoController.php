<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\WxUser;
use Illuminate\Http\Request;

class UserInfoController extends Controller
{
    //GET|HEAD  | api/auth/userInfo                       | userInfo.index
    //获取个人信息
    public function index(){
        //openid jwt 获取
        $openid =  auth()->user()->openid;
        $user = WxUser::where('openid',$openid)->get(['limid','nickname','avatar','gender','updated_at','created_at','location']);
        $user = $user[0];
//        $location = explode(' ',$user->location);
//        $location = str_replace(["省","市"]," ",$location[0]);
        $location = str_replace(["省","市"]," ",$user->location);
        $user->location = trim($location);
//        dd($user->updated_at);
        return $user;
    }
}

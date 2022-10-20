<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//后台广告控制器
class AdsController extends Controller
{
    //后台首页
    public function index(){

        //加载页面
        return view('admin.sys.ads.index');
    }
}

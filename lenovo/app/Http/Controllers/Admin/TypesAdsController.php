<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//后台分类广告控制器
class TypesAdsController extends Controller
{
    //分类广告首页
    public function index(){

        //加载页面
        return view('admin.sys.types.index');
    }
}

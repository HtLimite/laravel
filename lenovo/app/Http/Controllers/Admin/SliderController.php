<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//后台轮播图控制器
class SliderController extends Controller
{
    //轮播图首页
    public function index(){

        //加载页面
        return view('admin.sys.slider.index');
        }
}

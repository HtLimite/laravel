<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//前台页面控制器
class IndexController extends Controller
{
    //前台首页
    public function index(){
        //加载页面
        return view('home.index');
    }
}

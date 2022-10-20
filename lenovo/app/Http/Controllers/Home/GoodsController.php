<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//商品控制器
class GoodsController extends Controller
{
    //商品页
    public function index(){

        //加载页面
        return view('home.goods');
    }
}

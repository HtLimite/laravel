<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//后台系统管理控制器
class ConfigsController extends Controller
{
    //系统首页
    public function index()
    {
        //加载页面
        return view('admin.sys.config.index');
    }

    //更新配置的方法
    public function store(Request $request){

        //获取数据
        $arr = $request->except('_token');

        //数组转化字符串
        $str1 = var_export($arr,true);
        $str = "<?php
        return ".$str1." ?>";

        //写入文件到指定位置
        file_put_contents('../config/web.php',$str);

        return back();

    }
}

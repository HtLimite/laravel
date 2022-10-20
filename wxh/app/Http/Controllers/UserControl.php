<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Http\Controllers;

class UserControl extends Controller
{
    public function regedit(Request $request)
    {
        echo "<script>alert('注册成功，点击登录');location='log';</script>";

    }
    public function login(Request $request){
        echo "<script>alert('登录成功！');</script>";
    }
}

<?php

namespace App\Http\Controllers;

use App\UserReiki;
use App\Hello;
//使用模型，关联表
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
//    public function hello ($id = null)
//    {
//        return view('user.hello',['id' => $id]);
//    }

    public function index(){
        echo "123";
    }


    public function login(){
        return view('login');
    }

    public function regedit(){
        return view('regedit');
    }


    public function reg(Request $request){
        $insert = DB::table('test')->get();
        return response([
            'data' => $insert
        ],201);
        $user_num = $request->input('name');
        $password = $request->input('pass');
        $table = DB::table('admin')->insert([
                'name' => $user_num,
                'pass' => $password
            ]
        );
        $insert = DB::table('admin')->get();
        return response([
            'data' => $insert
        ],201);
        if($insert){
            echo "<script>alert('注册成功！返回登录');location = 'login';</script>";
        }else{
            echo "<script>alert('注册失败！');</script>";
            return redirect()->back();
        }
    }


    /**
     * @param Request $request
     * @return void
     */
    public function log(Request $request)
    {

        //三种数据库操作：
        $users = UserReiki::all();
        $db = DB::table('users')->get();
        $comments = DB::select('select * from comments');
        //
        $liuyan = Hello::all();
        dd($comments,$users,$db,$liuyan);

        $table = DB::table('users');
        $result = $table->where(['user_num' => $request->input('user_num')])->first();
        if(!isset($result)){
            echo "<script>alert('账号不存在！');history.go(-1);</script>";
        }elseif ($result->password == $request->input('password')){
            echo "<script>alert('登录成功！');local='admin';</script>";
        }else{
            echo "<script>alert('密码错误！');history.go(-1);</script>";
        }
    }
}

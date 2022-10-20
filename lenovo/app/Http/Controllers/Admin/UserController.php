<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//后台管理员管理控制器
class UserController extends Controller
{
    //管理员页面
    public function index(Request $request){

        $search = $request->input('search');

        if($search) {
            //从数据库读取数据
            $data = \DB::table('user')->where('tel','=',$search)->paginate(5);

            //获取总数居
            $tot = \DB::table('user')->where('tel','=',$search)->count();
        }else {

            //从数据库读取数据
            $data = \DB::table('user')->paginate(5);

            //获取总数居
            $tot = \DB::table('user')->count();
        }

        return view('admin.user.index')->with('data',$data)->with('tot',$tot);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//后台管理员管理控制器
class AdminController extends Controller
{
    //管理员页面
    public function index(){

        //总数居
        $tot = \DB::table('admin')->count();

        //分页展示数据
        $admin = \DB::table('admin')->orderBy('id','desc')->paginate(7);


        return view('admin.admin.index')->with('data',$admin)->with('tot',$tot);
    }

    //添加页面  admin/admin/create  GET
//    public function create(){
//        return view('admin.admin.add');
//    }

    //插入操作  admin/admin  POST
    public function store(Request $request){
        //字符串变成数组
        parse_str($_POST['str'],$arr);

        //表单验证规则
        $rules = [
            'name' => 'required|unique:admin|between:6,12',
            'pass' => 'required|same:repass|between:6,12',
        ];

        //表单验证提示信息
        $message = [
            'name.required' => '请输入用户名',
            'name.unique' => '用户名已存在',
            'pass.required' => '请输入密码',
            'pass.same' => '两次密码不一致',
            'pass,between' => '密码长度不在6-12位之间',
        ];

        //使用laravel表单验证
        $validator = \Validator::make($arr,$rules,$message);

        //开始验证
        if($validator->passes()){

            //验证通过添加数据库
            unset($arr['repass']);

            $arr['time'] = time();

            //加密密码
            $arr['pass'] = \Crypt::encrypt($arr['pass']);

            //插入数据库
            if(\DB::table('admin')->insert($arr)){
                return 1;
            }else{
                return 0;
            }
        }else{

            //具体查看laravel核心类
            return $validator->getMessageBag()->getMessages();
        }
    }

    //更新操作  admin/admin/{admin}  put
    public function update(Request $request){
        //字符串变成数组
        parse_str($_POST['str'],$arr);

        //表单验证规则
        $rules = [
            'pass' => 'required|same:repass|between:6,12',
        ];

        //表单验证提示信息
        $message = [
            'pass.required' => '请输入密码',
            'pass.same' => '两次密码不一致',
            'pass,between' => '密码长度不在6-12位之间',
        ];

        //使用laravel表单验证
        $validator = \Validator::make($arr,$rules,$message);

        //开始验证
        if($validator->passes()){

            //验证通过添加数据库
            unset($arr['repass']);
            //加密密码
            $arr['pass'] = \Crypt::encrypt($arr['pass']);

            //更新数据库
            if(\DB::table('admin')->where('id',$arr['id'])->update($arr)){
                return 1;
            }else{
                return 0;
            }
        }else{

            //具体查看laravel核心类
            return $validator->getMessageBag()->getMessages();
        }
    }

    //修改页面  admin/admin/{admin}/edit get
    public function edit($id){
        //查询数据库
        $data = \DB::table('admin')->find($id);

        //解密
        $data->pass =  \Crypt::decrypt($data->pass);

        //分配数据
        return view('admin.admin.edit')->with("data",$data);

    }

    //删除操作   admin/admin/{admin}  delete
    public function destroy($id){

        //删除数据
        if(\DB::table('admin')->delete($id)){
            return 1;
        }else{
            return 0;
        }
    }

    //修改状态的方法
    public function ajaxStatus(Request $request){

        //剔除数据
        $arr = $request->except('_token');


        if(\DB::table("admin")->where('id',$arr['id'])->update($arr)){
            return 1;
        }else{
            return 0;
        }
    }

}

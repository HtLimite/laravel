<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//后台分类控制器
class TypesController extends Controller
{
    //方法二.数据格式化处理方法 多次调用方法查询数据库 (不推荐)
    public function data($pid=0){
        //数据库查询
        $data = \DB::table('types')->where('pid',$pid)->get();

        //查询下一级分类
        foreach($data as $key => $value){
            $value->zi = $this->data($value->id);
        }
        return $data;
    }
    //方法三.数据格式化 最优方法(推荐)
    public function data1($data,$pid=0){

        $newArr = array();

        //获取顶级分类
        foreach ($data as $key => $value){
            if($value->pid == $pid){

                $newArr[$value->id] = $value;

                $newArr[$value->id]->zi = $this->data1($data,$value->id);
            }
        }
        return $newArr;
    }

    //分类页面
    public function index(){

        //一.使用面向对象方式实现(淘汰)

            //遍历出所有的顶级分类
            $one = \DB::table('types')->where('pid',0)->get();

            //遍历one的孩子
            foreach($one as $key => $value){
                $value->zi = \DB::table('types')->where('pid',$value->id)->get();
            }

            //遍历三级分类
            foreach($one as $key => $value){

                foreach($value->zi as $v){
                    $v->zi = \DB::table('types')->where('pid',$v->id)->get();
                }
            }

        //二.使用递归实现数据格式化
//            $arr = $this->data();

        //三.使用递归实现数据格式化
            $data = \DB::table('types')->get();

            $arr = $this->data1($data,$pid=0);

        //四.实现树形解构
            $data = \DB::select("select types.*,concat(path,id) p from types order by p");

//            echo "<pre>";
//            print_r($data);
//        exit;

        //查询数据
//        $data = \DB::table('types')->orderBy("sort",'desc')->get();

        //返回页面
        return view('admin.types.index')->with('data',$data);
    }

    //添加页面  admin/types/create  GET
    public function create(){
        return view('admin.types.add');
    }

    //插入操作  admin/types  POST
    public function store(Request $request){

        //处理数据
        $arr =  $request->except("_token");
//        dd($arr);
        //插入数据
        if(\DB::table('types')->insert($arr)){
            //插入成功,跳转展示页面
            return redirect('admin/types');
        }else{
            //添加失败回到上一个页面
            return back();
        }
    }

    //修改页面  admin/types/{admin}/edit get
    public function edit(){
        return view('admin.types.edit');
    }

    //更新操作  admin/types/{admin}  put
    public function update(){

    }

    //删除操作   admin/types/{admin}  delete
    public function destroy($id){
        //删除操作
        if(\DB::delete("delete from types where id=$id or path like '%,$id,%'")){
            return 1;
        }else{
            return 0;
        }
    }
}
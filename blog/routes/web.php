<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

 Route::get('/', function () {
     return view('welcome');
 });


include __DIR__.'/admin/web.php';


////路由调用控制器UserController类，helllo方法，返回视图，规定传值参数
//    Route::get('user/hello{id?}','UserController@hello')->where('id','[0-9]+');
////路由返回视图，显示id
//    Route::get('user/{id?}',function ($id = null)
//    {
//        return $id;
//    })->where(['id' => '[0-9]+']);
use Illuminate\Support\Facades\Route;
Route::post('check','UserController@reg');

Route::get('login','UserController@login');

Route::get('regdit','UserController@regedit');

Route::post('checkreg','UserController@reg');

Route::post('checklog','UserController@log');

Route::resource('admin','UserController');


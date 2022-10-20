<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;



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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/',function(){
    return view('user.regedit');
});
Route::post('user/reg','App\Http\Controllers\UserControl@regedit');

Route::get('user/log',function(){
    return view('user.log');
});
Route::post('user/login',[UserControl::class,'login']);


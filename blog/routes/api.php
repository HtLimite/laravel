<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('check','UserController@reg');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//jwt 封装测试
Route::group(['middleware' => 'jwt_auth'],function (){
    Route::post('jwt','JwtController@logined');
});
Route::post('jwtLogin','JwtController@login');

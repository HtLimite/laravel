<?php

use App\Http\Controllers\NewYearController;
use App\Http\Controllers\ShowEnglish\ShowController;
use App\Http\Controllers\Users\UserInfoController;
use App\Http\Controllers\Users\UserMessageController;
use App\Http\Controllers\Words\WordJsonController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Language\LanguageResController;
use App\Http\Controllers\LanguageList\LanguageListController;
use App\Http\Controllers\Auth\WxAuthorizationsController;
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
//language
Route::resource('/language',LanguageResController::class);
//首页列表
Route::resource('/languageList',LanguageListController::class);

// 单词库添加 api
Route::post('/wordAddLibrary',[WordJsonController::class,'get']);
//添加书的json库
Route::post('/add',[WordJsonController::class,'add']);
//获取书列表
Route::post('/get',[WordJsonController::class,'book']);
//单词库索引
Route::post('wordsSort',[WordJsonController::class, 'sort']);
//单词查询
Route::post('/searchWord',[WordJsonController::class, 'searchWord']);

// 单词库json api
Route::post('/words',[WordJsonController::class,'words']);
//微信登陆
Route::post('/auth',[WxAuthorizationsController::class,'WxLogin']);
//登出
Route::middleware('jwt-auth')->post('/auth/logout',[WxAuthorizationsController::class,'WxLogout']);

//微信用户api
Route::middleware('jwt-auth')->prefix('auth')->group(function (){
    Route::get('/jwt', [WxAuthorizationsController::class,'jwt']);
    //用户个人信息
    Route::resource('/userInfo', UserInfoController::class);
    //用户单词 增删改查
    Route::resource('/userMessage', UserMessageController::class);
    //用户排行榜
    Route::post('/userMessage/rank',[UserMessageController::class, 'rank']);
    //用户反馈
    Route::post('/userMessage/help',[UserMessageController::class,'help']);
    //收藏单词
    Route::post('/userMessage/collect',[UserMessageController::class,'collect']);
});

//测试
Route::get('/jwt', [WxAuthorizationsController::class,'jwt']);


//单词信息公开展示
Route::post('/showEnglishWords',[ShowController::class,'getOne']);


//弹幕信息 2023
Route::post('newYear',[NewYearController::class,'index']);
Route::post('getLocation',[NewYearController::class,'getLocation']);

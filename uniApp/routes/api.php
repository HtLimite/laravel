<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Language\LanguageResController;
use App\Http\Controllers\LanguageList\LanguageListController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//language
Route::resource('/language',LanguageResController::class);

Route::resource('/languageList',LanguageListController::class);

// 单词库添加 api
Route::get('/wordAddLibrary',[LanguageListController::class,'get']);
Route::get('/add',[LanguageListController::class,'add']);

// 单词库json api
Route::post('/words',[LanguageListController::class,'words']);

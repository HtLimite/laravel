<?php
Route::group(['prefix' => 'admin'],function (){
    Route::get('log','UserController@log');
});

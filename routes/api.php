<?php

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

Route::namespace('Api')->group(function () {
    // 用户注册
    Route::post('/register', 'TokenController@register')->name('tokens.register');
    // 用户登录
    Route::post('/login', 'TokenController@login')->name('tokens.login');
    // 刷新token
    Route::post('/refresh', 'TokenController@refresh')->name('tokens.refresh');
    // 获取自己的资料
    Route::get('/users/self', 'UserController@self')->name('users.self');
    // 分帮查询
    Route::apiResource('/clubs', 'ClubController')
        ->only(['index', 'show']);
    // 学员展示查询
    Route::apiResource('/students', 'StudentController')
        ->only(['index']);


    // 需要超级管理员权限的路由
    Route::middleware('guard:super')->group(function () {
        // 上传图片
        Route::post('/images', 'ImageController@store')->name('images.store');
        // 上传视频
        Route::post('/videos', 'VideoController@store')->name('videos.store');
        // 分帮增删改
        Route::apiResource('/clubs', 'ClubController')
            ->only(['store', 'update', 'destroy']);
        // 学员展示增删
        Route::apiResource('/students', 'StudentController')
            ->only(['store', 'destroy']);
    });
});
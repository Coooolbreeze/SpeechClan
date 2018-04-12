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
    // 课程查询
    Route::apiResource('/courses', 'CourseController')
        ->only(['index', 'show']);
    // 学员展示查询
    Route::apiResource('/students', 'StudentController')
        ->only(['index', 'show']);
    // 教学视频展示
    Route::apiResource('/teaches', 'TeachController')
        ->only(['index', 'show']);
    // 报名预约
    Route::apiResource('/orders', 'OrderController')
        ->only(['store']);
    // 关于我们展示
    Route::apiResource('/about_us', 'AboutUsController')
        ->only(['show']);


    // 需要超级管理员权限的路由
    Route::middleware('guard:super')->group(function () {
        // 上传图片
        Route::post('/images', 'ImageController@store')->name('images.store');
        // 上传视频
        Route::post('/videos', 'VideoController@store')->name('videos.store');
        // 分帮增删改
        Route::apiResource('/clubs', 'ClubController')
            ->only(['store', 'update', 'destroy']);
        // 课程增删改
        Route::apiResource('/courses', 'CourseController')
            ->only(['store', 'update', 'destroy']);
        // 学员展示增删改
        Route::apiResource('/students', 'StudentController')
            ->only(['store', 'update', 'destroy']);
        // 教学视频增删改
        Route::apiResource('/teaches', 'TeachController')
            ->only(['store', 'update', 'destroy']);
        // 预约人数查改删
        Route::apiResource('/orders', 'OrderController')
            ->only(['index', 'update', 'destroy']);
        // 关于我们编辑
        Route::apiResource('/about_us', 'AboutUsController')
            ->only(['update']);
    });
});
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

//Route::get('/rediss','index\RegisterController@rediss');//测试redis
Route::get('/user/register','index\RegisterController@register');//注册视图页面
Route::get('/user/store','index\RegisterController@store');//注册页面执行添加
Route::get('/login/login','index\RegisterController@login');//登陆页面的视图
Route::get('/login/logindo','index\RegisterController@logindo');//登陆页面的视图
Route::get('/index/index','index\RegisterController@index');//登陆成功跳转到首页
Route::prefix('user/center')->middleware('login')->group(function() {
        Route::get('/', 'index\RegisterController@center');// 用户中心
});
Route::get('user/exit','index\RegisterController@exit');//退出




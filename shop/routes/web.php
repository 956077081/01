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
Route::get('admin/test','Admin\CategoryController@test');
Route::group(['middleware'=>'auth.member'],function (){
    Route::get('test',function(){
        return "sucess";
    });

});
//Route::resource('photos','PhotoController');  //自动创建资源控制器
Auth::routes();
Route::group(['middleware'=>'auth'],function (){ //设置一分组  方便统一管理 加一个登录组件
    Route::get("admin","Admin\\DefaultController@index");
    Route::get("admin/category","Admin\\CategoryController@index");
    //新增分类页面
    Route::get("admin/category/create",'Admin\CategoryController@create');
    //执行新增数据库
    Route::post("admin/category/create",'Admin\CategoryController@create');
    //修改分类页面
    Route::get('admin/category/update/{id}','Admin\CategoryController@update')->where(['id'=>'[0-9]+']);
    //执行修改数据库
    Route::post('admin/category/update/{id}','Admin\CategoryController@doupdate')->where(['id'=>'[0-9]+']);
    //删除分类
    Route::post('admin/category/delete','Admin\CategoryController@delete')->where(['id'=>'[0-9]+']);
    Route::post("admin/category/docreate",'Admin\CategoryController@docreate');

    //资源路由
    Route::resource('admin/product','Admin\ProductController');
});




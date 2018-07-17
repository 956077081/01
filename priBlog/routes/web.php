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
Auth::routes();
Route::group(['middleware'=>'auth'],function (){
    Route::get('/admin/defaul','Defaul\HomeController@index');
    Route::resource('admin/category','Admin\CategoryController');
    Route::resource('admin/article','Admin\ArticleController');
});
Route::get('/home', 'HomeController@index')->name('home');

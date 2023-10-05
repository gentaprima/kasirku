<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

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

Route::get('/','LoginController@index');
Route::post('/auth','LoginController@auth');

Route::group(['middleware' => 'check.login','prefix' => '/'],function(){

    //dashboard
    Route::get('/beranda','DashboardController@index');

    // produk
    Route::get('/data-produk','DashboardController@getProduct');
    Route::get('/show-product','ProductController@getProductJson');
    Route::post('/add-produk','ProductController@store');
    Route::post('/update-produk/{id}','ProductController@update');
    Route::get('/delete-produk/{id}','ProductController@destroy');

    // karyawan
    Route::get('/data-karyawan','DashboardController@getKaryawan');
    Route::get('/show-karyawan','UsersController@showKaryawan');
    Route::post('/add-karyawan','UsersController@store');
    Route::post('/update-karyawan/{id}','UsersController@update');
    Route::get('/delete-karyawan/{id}','UsersController@destroy');


    // topping
    Route::post('/add-topping','ToppingController@store');
    Route::get('/get-data-topping/{id}','ToppingController@getData');

    Route::get('/logout', function () {
        Session::flush();
        return redirect('/');
    });
});

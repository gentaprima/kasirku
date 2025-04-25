<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/auth','UsersController@auth');
// product
Route::post('/get-product','ProductController@getProduct');
Route::get('/get-stock','ProductController@getStock');
Route::post('/update-stock','ProductController@updateStockApi');
// cart
Route::post('/get-cart','CartController@getCart');
Route::post('/add-cart','CartController@addCart');
Route::post('/min-cart','CartController@minCart');
// topping
Route::get('/get-topping/{id}','ToppingController@getData');
// transaction
Route::post('/add-transaction','TransactionController@storeNew');
Route::post('/get-income','TransactionController@getIncome');
Route::post('/get-exit-item','TransactionController@getExitItem');
Route::get('/send-wa','TransactionController@sendWhatsAppMessage');
Route::get('/send-wa-stock','TransactionController@sendWhatsAppMessageStockLitle');
Route::get('/test','TransactionController@sendGroup');
Route::get('/send-report-stock','TransactionController@sendReportStock');
Route::get('/send-report-low-stock','TransactionController@sendReportLowStock');
Route::get('/clear-cache','DashboardController@clearCache');
Route::post('/login','LoginController@loginApi');
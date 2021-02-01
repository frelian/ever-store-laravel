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

Auth::routes();

Route::get('/home/{search?}/{page?}', 'HomeController@index')->name('home');
Route::get('/', 'ProductController@index')->name('products.list');
Route::get('/client/order/{idproduct}', 'OrderController@create')->name('order.create');
Route::post('/client/order', 'OrderController@store')->name('order.store');
Route::get('/client/order/resume/{idorder}', 'OrderController@show')->name('order.show');
Route::get('/client/order/pay/{idorder}', 'OrderController@pay')->name('order.pay');
Route::post('/client/order/pay/{idorder}', 'OrderController@pay')->name('order.pay');
Route::get('/response/{id}', 'OrderController@responsePay')->name('order.pay.response');

Route::get('/order/status/{idorder}/{checked?}', 'OrderController@statusOrder')->name('order.pay.status');

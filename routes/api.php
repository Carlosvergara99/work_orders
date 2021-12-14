<?php

use Illuminate\Http\Request;

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

Route::group(['prefix' => 'operator'], function () {
    Route::post('get', 'OperatorController@get');
    Route::post('create', 'OperatorController@store');
    Route::post('update', 'OperatorController@update');
    Route::post('delete', 'OperatorController@destroy');
});


Route::group(['prefix' => 'order'], function () {

    Route::post('save/types/work', 'OrderController@SaveTypesWork');
    Route::post('save/work', 'OrderController@SaveOrderWork');
    Route::post('save/work/assignment', 'OrderController@SaveWorkOrderAssignment');
    Route::post('update', 'OrderController@UpdateOrder');

    Route::get('get/supplement', 'OrderController@Getsupplement');
    Route::post('get', 'OrderController@getOrder');
    Route::post('get/date/order_work', 'OrderController@GetDateOrderWork');
});
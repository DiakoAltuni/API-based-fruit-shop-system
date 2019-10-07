<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {

    return $request->user();
});

Route::group([
    'prefix'=>'v1',
    'namespace'=>'Api\Product',
//    'middleware'=>'auth:api'
],function (){
    Route::apiResource('product' , 'ProductController')
        ->only([ 'index','store' , 'update' , 'destroy' ])
        ->parameter('product' , 'id');
});


Route::group([
    'prefix'=>'v1',
    'namespace'=>'Api\Sold',
//    'middleware'=>'auth:api'

],function (){
    Route::apiResource('sold' , 'SoldController')
        ->only([ 'index','store' , 'update' , 'destroy' ])
        ->parameter('sold' , 'id');
});

Route::group([
    'prefix'=>'v1',
    'namespace'=>'Api\Auth',

],function (){
    Route::get('get-token','AuthController@index')->name('get-token');
    Route::post('register','AuthController@store')->name('register');
});

//Auth::routes();












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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'v1', 'namespace' => 'Api'], function(){
	Route::get('governorates', 'MainController@governorates');
	Route::get('cities', 'MainController@cities');
    Route::post('contacts', 'MainController@contacts');
    Route::get('categories', 'MainController@categories');
    Route::get('blood_types', 'MainController@bloodTypes');
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('new-password', 'AuthController@newPassword');
    Route::post('reset-password', 'AuthController@resetPassword');
    Route::get('notifications', 'MainController@notifications');


    Route::group(['middleware' => 'auth:api'], function (){
        Route::get('posts', 'MainController@posts');
        Route::get('posts/{id}', 'MainController@postsByCategory');
        Route::get('settings', 'MainController@settings');
        Route::post('not-settings', 'AuthController@notificationSettings');
        Route::post('profile/{id}', 'AuthController@profile');
        Route::post('make-favorite','Maincontroller@makeFavorite');
        Route::post('favorites','Maincontroller@favorites');
        Route::get('all-order', 'MainController@allOrder');
        Route::post('create-order', 'MainController@createOrder');
        Route::post('register-token', 'AuthController@registerToken');
        Route::post('remove-token', 'AuthController@removeToken');
    });
});

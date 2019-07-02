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


/* 
Route::resource('client', 'ClientController');
Route::resource('bloodtype', 'BloodTypeController');
Route::resource('post', 'PostController');
Route::resource('clientpost', 'ClientPostController');
Route::resource('governorate', 'GovernorateController');
Route::resource('city', 'CityController');
Route::resource('category', 'CategoryController');
Route::resource('order', 'OrderController');
Route::resource('notification', 'NotificationController');
Route::resource('clientnotification', 'ClientNotificationController');
Route::resource('bloodtypeclient', 'BloodTypeClientController');
Route::resource('clientgovernorate', 'ClientGovernorateController');
Route::resource('setting', 'SettingController');
Route::resource('contact', 'ContactController');
Route::resource('favorite', 'FavoriteController');
Route::resource('clientfavorite', 'ClientFavoriteController');
*/
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

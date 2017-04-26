<?php

use App\Services\Identify\Identify;
use Star\Weather\WeatherClient;

// 手机验证用
Route::post('signup', 'Api\AuthController@signup');
Route::post('getsms', 'Api\SmsController@authcode');

Route::get('weather', function () {
	return WeatherClient::get();
});

Route::group([
	'middleware' => ['auth:api'],
	'namespace' => 'Api',
], function () {

	Route::group(['prefix' => 'home', 'middleware' => 'auth.user'], function () {
		Route::get('statistics', 'Homecontroller@index');
		Route::get('/', 'HomeController@info');
		Route::post('/', 'HomeController@update');
		Route::get('menu', 'HomeController@menu');
		Route::post('avatar', 'HomeController@avatar');
		Route::post('changePassword', 'HomeController@changePassword');
		Route::post('changeMobile', 'HomeController@changeMobile');
		Route::post('updateMeInfo', 'HomeController@updateMeInfo');
		Route::get('timeline', 'HomeController@timeline');
		// Notifications
		Route::get('notification', 'NotificationController@index');
		Route::post('notification/clear', 'NotificationController@clear');
		Route::post('notification/mark', 'NotificationController@mark');
		Route::post('notification/delete', 'NotificationController@delete');
	});

	Route::get('weather', function () {
		return WeatherClient::get();
	});
	Route::get('checkPid', function () {
		return Identify::parse(request('pid'));
	});

	// Export Excel
	Route::post('users/excel', 'UserController@export');

	// User
	Route::resource('user', 'UserController', ['except' => ['create', 'show']]);
	Route::resource('unit', 'UnitController', ['except' => ['create', 'show']]);
	Route::resource('permission', 'PermissionController', ['except' => ['create', 'show']]);

	// 流动人口管理
	Route::resource('pop', 'PopController', ['except' => ['create', 'show']]);

	Route::post('lis', 'LisController@index');
});
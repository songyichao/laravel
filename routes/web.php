<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
	return view('welcome');
});

Route::get('/hello', function () {
	echo 'hello word';
});

Route::get('/get/{name}/{id}', function ($name = 'James', $id) {
	return view('greeting', ['name' => $name, 'id' => $id]);
})->where(['name' => '[A-Za-z]+', 'id' => '[0-9]+']);

Route::group(['prefix' => 'admin'], function () {
	Route::get('add/', 'AdminController@add');
	Route::post('save/', 'AdminController@save');
	Route::get('user/{id}', 'AdminController@user');
	Route::get('set/', 'AdminController@set');
	Route::get('get/', 'AdminController@get');
	Route::get('admin', function () {
		echo 'wqewqeq';
	});
	Route::get('route', function () {
		return response('hello word', 200)
			->header('Content-Type', 'text/plain');
	});
	Route::get('showuser', 'AdminController@showuser');
});

Route::group(['prefix' => 'student'], function () {
	Route::get('list/', 'StudentController@list');
	Route::get('add/{name}/{age}/{sex}', 'StudentController@add');
	Route::get('update/{name}/{age}/{sex}/{id}', 'StudentController@update');
	Route::get('delete/{id}', 'StudentController@delete');
});

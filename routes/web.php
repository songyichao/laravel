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

use app\Http\Controllers\TenxunyunController;

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
	Route::get('request1', 'AdminController@request1');
	Route::get('session1', 'AdminController@session1');
	Route::get('session2', [
		'as' => 'session2',
		'uses' => 'AdminController@session2'
	]);
	Route::get('response', 'AdminController@response');
});
//中间件
Route::get('activity0', ['uses' => 'AdminController@activity0']);
Route::get('date', ['uses' => 'AdminController@date']);
Route::group(['middleware' => ['activity']], function () {
	Route::get('activity1', ['uses' => 'AdminController@activity1']);
	Route::get('activity2', ['uses' => 'AdminController@activity2']);
});
//学生
Route::group(['prefix' => 'student'], function () {
	Route::get('list/', 'StudentController@list');
	Route::get('add/{name}/{age}/{sex}', 'StudentController@add');
	Route::get('update/{name}/{age}/{sex}/{id}', 'StudentController@update');
	Route::get('delete/{id}', 'StudentController@delete');
});
//教师
Route::group(['prefix' => 'teacher'], function () {
	Route::get('list/', 'TeacherController@list');
	Route::get('add/{name}/{age}/{class}', 'TeacherController@add');
	Route::get('update/{name}/{age}/{class}/{id}', 'TeacherController@update');
	Route::get('delete/{id}', 'TeacherController@delete');
});
Route::group(['prefix' => 'people'], function () {
	Route::get('index', ['uses' => 'PeopleController@index']);
	Route::get('create', ['uses' => 'PeopleController@create']);
	Route::any('save', ['uses' => 'PeopleController@save']);
	Route::any('update/{id}', ['uses' => 'PeopleController@update']);
	Route::get('show/{id}', ['uses' => 'PeopleController@show']);
	Route::any('delete/{id}', ['uses' => 'PeopleController@delete']);
	
});
Route::any('upload', ['uses' => 'UploadsController@upload']);
Route::any('mail', ['uses' => 'UploadsController@mail']);
Route::any('cache1', ['uses' => 'UploadsController@cache1']);
Route::any('cache2', ['uses' => 'UploadsController@cache2']);
Route::any('error', ['uses' => 'UploadsController@error']);
Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/word', ['uses' => 'UploadsController@word']);
Route::get('/yun', ['uses'=> 'TenxunyunController@account']);
Route::get('/send', ['uses'=> 'TenxunyunController@send']);
Route::get('/get-profile', ['uses'=> 'TenxunyunController@get_profile']);

//collection
Route::group(['prefix' => 'collection'], function () {
	Route::get('collection1', 'CollectionController@collection1');
	Route::get('collection2', 'CollectionController@collection2');
	Route::get('collapse', 'CollectionController@collapse');
	Route::get('combine', 'CollectionController@combine');
	Route::get('contains', 'CollectionController@contains');
	Route::get('diff', 'CollectionController@diff');
	Route::get('except', 'CollectionController@except');
	
});
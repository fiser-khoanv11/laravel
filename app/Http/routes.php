<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

use App\Employee;
Route::get('emp-test/{name}/{job}', function ($name, $job) {
	$emp = new Employee;

	$emp->emp_name = $name;
	$emp->emp_job = $job;
	// $emp->emp_phone = $_POST['phone'];
	// $emp->emp_email = $_POST['email'];
	// $emp->dep_id = $_POST['dep'];
	
	$emp->save();
});

// Route::get('emp-select2/{dep}/{name?}', 'EmpCtrl@select2');

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
    Route::get('sess', 'EmpCtrl@sess');
});

Route::get('/', 'EmpCtrl@index');

// Employee Routes

Route::get('emp', 'EmpCtrl@index');

Route::get('emp-select/{dep}/{name?}', 'EmpCtrl@select');

Route::post('emp-insert', 'EmpCtrl@insert');

Route::post('emp-update', 'EmpCtrl@update');

Route::get('emp-delete/{id}', 'EmpCtrl@delete');

Route::get('emp-select-single/{id}', 'EmpCtrl@selectSingle');

// Department Routes

Route::get('dep', 'DepCtrl@index');

Route::get('dep-select', 'DepCtrl@select');

Route::post('dep-insert', 'DepCtrl@insert');

Route::post('dep-update', 'DepCtrl@update');

Route::get('dep-delete/{id}', 'DepCtrl@delete');

Route::get('dep-select-single/{id}', 'DepCtrl@selectSingle');

Route::get('dep-select-names', 'DepCtrl@selectNames');
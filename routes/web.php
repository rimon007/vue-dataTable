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
	$limit = request('limit') ?? 10;
	$orderColumn = request('orderColumn') ?? 'created_at';
	$orderBy = request('orderBy') ?? 'desc';
	//$data = \DB::table('items')->orderBy("$orderColumn", $orderBy)->paginate($limit);
	$data = \DB::table('student_profiles')->orderBy("$orderColumn", $orderBy)->paginate($limit);
	//$data = [];
	if(request()->ajax()) {
		return $data;
	}
    return view('welcome', compact('data'));
});

Route::get('paginate', 'PaginationController@index');
Route::post('paginate', 'PaginationController@index');

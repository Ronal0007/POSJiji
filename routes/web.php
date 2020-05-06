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

use App\Pos;
use App\POS_STATUS;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('login'));
});

Route::get('test', function () {
    return view('pos-list2');

});
//Route::get('init','SettingController@init')->name('init');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home',function(){
        return redirect(\route('pos'));
    })->name('home');
    Route::get('/dashboard',function(){
        return view('dashboard');
    })->name('dashboard');
    Route::get('pos', 'PosController@index')->name('pos');
    Route::get('search', 'PosController@search')->name('search.pos');
    Route::get('pos/filter/{status}', 'PosController@filter')->name('filter.pos');
    Route::get('view/pos/{id}', 'PosController@show')->name('view.pos');
    Route::post('add/pos', 'PosController@add')->name('add.pos');
    Route::post('get/pos/{id}', 'PosController@getPos')->name('get.pos');
    Route::put('update/pos/{id}', 'PosController@update')->name('update.pos');
    Route::post('handover/pos/{id}', 'PosController@handover')->name('handover.pos');
    Route::post('return/pos/{id}', 'PosController@returnPos')->name('return.pos');
    Route::post('damaged/pos}', 'PosController@damaged')->name('damaged.pos');
    Route::get('pos/report','PosController@report')->name('report.pos');
    Route::get('export','PosController@export')->name('export.pos');


    //Settings
    Route::get('settings','SettingController@setting')->name('setting');
    Route::post('add/tool','SettingController@addTool')->name('add.tool');
    Route::put('edit/tool/{tool}','SettingController@updateTool')->name('edit.tool');
    Route::delete('delete/tool/{tool}','SettingController@deleteTool')->name('delete.tool');


    //User
    Route::get('user', 'UserController@index')->name('users');
});


/*
 * Authentication routes
 */
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);


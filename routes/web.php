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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('tag','HomeController@tag');
Route::get('/ask','HomeController@ask');
Route::get('/ask/create','HomeController@create');
Route::post('/ask/store','HomeController@store');
Route::get('/ask/tag/{id}','HomeController@tagShow');

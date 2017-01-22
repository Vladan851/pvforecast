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

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/search', 'HomeController@search')->name('search');
Route::get('/send', 'EmailController@send');

Auth::routes();

Route::get('/home', 'HomeController@index');

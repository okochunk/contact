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

// Landing Page
Route::get('/', function () {
    return redirect('groups');
});

Route::resource('contacts', 'ContactController');
Route::resource('groups', 'GroupController');

Route::get('get-state-collection','ContactController@getStateCollection');
Route::get('get-city-collection','ContactController@getCityCollection');

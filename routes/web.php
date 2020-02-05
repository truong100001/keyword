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

Route::get('/','HomeController@index')->middleware('login');

Route::get('/login','LoginController@index');

Route::get('/test','CheckController@check_similar');

Route::get('/logout','LoginController@logout');

Route::post('/login','LoginController@login');

Route::get('/add-domain','HomeController@addDomain')->middleware('login');

Route::post('/add-domain','HomeController@postAddDomain')->middleware('login');

Route::post('/add-keyword','HomeController@addkeyWord')->middleware('login');

Route::get('/check-domain','HomeController@check_domain')->middleware('login');

Route::post('/history-keyword','HomeController@history_keyword')->middleware('login');

Route::get('/delete-keyword/{id}','HomeController@delete_keyword')->middleware('login');

Route::post('/filter-keyword','HomeController@filter_keyword')->middleware('login');

Route::post('/refresh','HomeController@refresh')->middleware('login');

Route::get('/delete-domain/{id}','HomeController@delele_domain')->middleware('login');







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


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/friends', 'FriendController@index')->middleware('auth')->name('friends');
Route::post('/friend', 'FriendController@store')->middleware('auth');
Route::delete('/friend/{friend}', 'FriendController@destroy')->middleware('auth');

Route::get('/friendbreak', 'FriendBreakController@index'); // this is only because I need to manually view it
Route::get('/friendbreak/search', 'FriendBreakController@search');



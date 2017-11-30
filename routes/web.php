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

Route::get('/', 'HomeController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/friends', 'FriendController@index')->middleware('auth')->name('friends');
Route::delete('/friend/{friend}', 'FriendController@destroy')->middleware('auth');

Route::get('/search', 'SearchFriendController@index')->middleware('auth')->name('search');
Route::get('/search/results', 'SearchFriendController@search')->middleware('auth');
Route::post('/search/add/{user}', 'SearchFriendController@add')->middleware('auth');

Route::get('/requests', 'FriendRequestController@index')->middleware('auth')->name('requests');
Route::post('/requests/accept/{user}', 'FriendRequestController@accept')->middleware('auth');
Route::delete('/requests/decline/{user}', 'FriendRequestController@decline')->middleware('auth');

Route::get('/friendbreak', 'FriendBreakController@index'); // this is only because I need to manually view it
Route::post('/friendbreak/search', 'FriendBreakController@search');

Route::get('/coursemanager', 'CourseManagerController@index')->middleware('auth')->name('coursemanager');
Route::delete('/coursemanager/{course}', 'CourseManagerController@drop')->middleware('auth');

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



Auth::routes();
Route::redirect('/', '/home');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/reports', 'HomeController@report')->name('report');
Route::get('/projects', 'ProjectController@index');
Route::get('/dashboard', 'ProjectController@dashboard')->name('dashboard');
Route::get('/breakdown', 'ProjectController@breakdown');
Route::get('/updateDuration', 'ProjectController@updateDuration');
Route::get('/markasComplete/{id}', 'ProjectController@markAsComplete');
Route::post('/projects', 'ProjectController@store');
Route::get('/complete', 'ProjectController@completedAndNot');
Route::post('/projects/{id}/timers/stop', 'TimerController@stopRunning');
Route::post('/projects/{id}/timers', 'TimerController@store');
Route::get('/project/timers/active', 'TimerController@running');
Route::delete('/projects/{id}/delete', 'TimerController@destroy');
Route::put('/projects/{id}/update', 'TimerController@update');
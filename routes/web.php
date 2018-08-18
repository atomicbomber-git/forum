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

Route::middleware(['auth'])->group(function() {
    Route::get('/thread/all', 'ThreadController@index')->name('thread.index');
    Route::post('/thread/create', 'ThreadController@create')->name('thread.create');
    Route::get('/thread/{thread}', 'ThreadController@detail')->name('thread.detail');

    Route::post('/thread/{thread}/comment/create', 'CommentController@create')->name('comment.create');
    Route::post('/comment/{comment}/delete', 'CommentController@delete')->name('comment.delete');
});

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
    Route::prefix('/thread')->group(function() {
        Route::get('/all', 'ThreadController@index')->name('thread.index');
        Route::post('/create', 'ThreadController@create')->name('thread.create');
        Route::get('/{thread}/detail', 'ThreadController@detail')->name('thread.detail');
        Route::post('/{thread}/comment/create', 'CommentController@create')->name('comment.create');
    });    

    Route::prefix('/comment')->group(function() {
        Route::post('/{comment}/delete', 'CommentController@delete')->name('comment.delete');
        Route::post('/{comment}/upvote', 'CommentController@upvote')->name('comment.upvote');
        Route::post('/{comment}/downvote', 'CommentController@upvote')->name('comment.downvote');
    });
});

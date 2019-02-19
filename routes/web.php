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
    return view('chat');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post("/messages", function(){
    //App\Events\Message::dispatch(request()->input('body'));
    broadcast(new App\Events\Message(request()->input('body')))->toOthers();
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/rooms', 'ChatController@rooms');
Route::get('/room/{room}', 'ChatController@room');
Route::post('/private', 'ChatController@save');
Route::post('/room/{room}/messages', 'ChatController@roomMessages');
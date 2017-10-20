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
Route::get('/profile', 'UserController@profile');
Route::post('/profile', 'UserController@update_avatar');
Route::get('/', 'ChatsController@index');
Route::get('messages', 'ChatsController@fetchMessages');
Route::post('messages', 'ChatsController@sendMessage');

Route::post('message', function(Request $request) {

    $user = Auth::user();

    $message = ChatMessage::create([
        'user_id' => $user->id,
        'message' => $request->input('message')
    ]);

    event(new ChatMessageWasReceived($message, $user));


});

// add verify email controller
Route::get('verifyEmailFirst', 'Auth\RegisterController@verifyEmailFirst')->name('verifyEmailFirst');
Route::get('verify/{email}/{verifyToken}', 'Auth\RegisterController@sendEmail')->name('sendEmailDone');



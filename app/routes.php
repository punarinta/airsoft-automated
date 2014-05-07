<?php

Route::get('/', array('as' => 'home', function ()
{
    return View::make('home');
}));


Route::get('/organizer', array('as' => 'organizer-dashboard', function ()
{
    return View::make('organizer.dash');
}))->before('auth');

Route::get('/player', array('as' => 'player-dashboard', function ()
{
    return View::make('player.dash');
}))->before('auth');


// User management
Route::get('user/login', array('uses' => 'UserController@loginForm', 'as' => 'login'))->before('guest');
Route::post('user/login', array('uses' => 'UserController@loginEndpoint', 'as' => 'login'));
Route::get('user/logout', array('uses' => 'UserController@logout', 'as' => 'logout'))->before('auth');
Route::get('user/profile', array('uses' => 'UserController@profileForm', 'as' => 'user-profile'))->before('auth');
Route::get('user/remind-password', array('uses' => 'UserController@remindForm', 'as' => 'user-remind-password'))->before('guest');
Route::post('user/remind-password', array('uses' => 'UserController@remindFormEndpoint', 'as' => 'user-remind-password'))->before('guest');
Route::get('user/reset-password/{token}', array('uses' => 'UserController@resetForm', 'as' => 'user-reset-password'))->before('guest');
Route::post('user/reset-password', array('uses' => 'UserController@resetFormEndpoint', 'as' => 'user-reset-password'))->before('guest');

Route::get('api/user/register/{email}', array('uses' => 'ApiUserController@register'))->before('guest');

// Team management

// Game management
Route::post('game/check-in', array('uses' => 'GameController@checkIn', 'as' => 'check-in'));

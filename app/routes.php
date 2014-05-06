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
Route::get('user/login',    array('uses' => 'UserController@loginForm',      'as' => 'login'))->before('guest');
Route::post('user/login',   array('uses' => 'UserController@loginEndpoint',  'as' => 'login'));
Route::get('user/logout',   array('uses' => 'UserController@logout',         'as' => 'logout'))->before('auth');
Route::get('user/profile',  array('uses' => 'UserController@profileForm',    'as' => 'user-profile'))->before('auth');

// Team management

// Game management
Route::post('game/check-in', array('uses' => 'GameController@checkIn', 'as' => 'check-in'));

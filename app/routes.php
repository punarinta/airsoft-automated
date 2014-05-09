<?php

// simple pages
Route::get('/', array('as' => 'home', function ()
{
    return View::make('home');
}));
Route::get('/about', array('as' => 'about', function ()
{
    return View::make('about');
}));


Route::get('games', array('uses' => 'GamesController@index', 'as' => 'games'));
Route::get('game/edit/{game_id?}', array('uses' => 'GameController@editForm', 'as' => 'game-edit'))->before('auth');
Route::get('game/book/{game_id}', array('uses' => 'GameController@bookForm', 'as' => 'game-book'))->before('auth');
Route::get('game/briefing/{game_id}', array('uses' => 'GameController@briefingForm', 'as' => 'game-briefing'))->before('auth');
Route::get('player', array('uses' => 'DashboardController@playerForm', 'as' => 'player-dashboard'))->before('auth');
Route::get('organizer', array('uses' => 'DashboardController@organizerForm', 'as' => 'organizer-dashboard'))->before('auth');


// User management
Route::get('user/login', array('uses' => 'UserController@loginForm', 'as' => 'login'))->before('guest');
Route::post('user/login', array('uses' => 'UserController@loginEndpoint', 'as' => 'login'));
Route::get('user/logout', array('uses' => 'UserController@logout', 'as' => 'logout'))->before('auth');
Route::get('user/profile', array('uses' => 'UserController@profileForm', 'as' => 'user-profile'))->before('auth');
Route::get('user/restore-password', array('uses' => 'UserController@restoreForm', 'as' => 'user-restore-password'))->before('guest');
Route::post('user/restore-password', array('uses' => 'UserController@restoreFormEndpoint', 'as' => 'user-restore-password'))->before('guest');
Route::get('user/reset-password/{token}', array('uses' => 'UserController@resetForm', 'as' => 'user-reset-password'))->before('guest');
Route::post('user/reset-password', array('uses' => 'UserController@resetFormEndpoint', 'as' => 'user-reset-password'))->before('guest');

Route::post('api/user', array('uses' => 'ApiUserController@create'))->before('guest');
Route::put('api/user/{user_id}', array('uses' => 'ApiUserController@update'))->before('auth');
Route::get('api/country/{country_id?}', array('uses' => 'ApiCountryController@index'));
Route::get('api/region/by-country/{country_id}', array('uses' => 'ApiRegionController@findByCountry'));
Route::get('api/team/by-region/{region_id}', array('uses' => 'ApiTeamController@findByRegion'));

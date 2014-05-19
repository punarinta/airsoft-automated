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


// Forms

Route::get('games', array('uses' => 'GamesController@index', 'as' => 'games'));
Route::get('game/edit/{game_id?}', array('uses' => 'GameController@editForm', 'as' => 'game-edit'))->before('auth');
Route::get('game/book/{game_id}', array('uses' => 'GameController@bookForm', 'as' => 'game-book'))->before('auth');
Route::get('game/briefing/{game_id}', array('uses' => 'GameController@briefingForm', 'as' => 'game-briefing'))->before('auth');
Route::get('player', array('uses' => 'DashboardController@playerForm', 'as' => 'player-dashboard'))->before('auth');
Route::get('organizer', array('uses' => 'DashboardController@organizerForm', 'as' => 'organizer-dashboard'))->before('auth');
Route::post('game/booked', array('uses' => 'PaymentController@bookingDoneForm', 'as' => 'booking-done'))->before('auth');
Route::post('game/pay-booked', array('uses' => 'PaymentController@payForm', 'as' => 'pay-booked'))->before('auth');


// User management

Route::get('user/login', array('uses' => 'UserController@loginForm', 'as' => 'login'))->before('guest');
Route::post('user/login', array('uses' => 'UserController@loginEndpoint', 'as' => 'login'));
Route::get('user/logout', array('uses' => 'UserController@logout', 'as' => 'logout'))->before('auth');
Route::get('user/profile', array('uses' => 'UserController@profileForm', 'as' => 'user-profile'))->before('auth');
Route::get('user/restore-password', array('uses' => 'UserController@restoreForm', 'as' => 'user-restore-password'))->before('guest');
Route::post('user/restore-password', array('uses' => 'UserController@restoreFormEndpoint', 'as' => 'user-restore-password'))->before('guest');
Route::get('user/reset-password/{token}', array('uses' => 'UserController@resetForm', 'as' => 'user-reset-password'))->before('guest');
Route::post('user/reset-password', array('uses' => 'UserController@resetFormEndpoint', 'as' => 'user-reset-password'))->before('guest');

// Non-REST API

Route::post('api/user', array('uses' => 'ApiUserController@create'))->before('guest');
Route::put('api/user/{user_id}', array('uses' => 'ApiUserController@update'))->before('auth');
Route::get('api/region/by-country/{country_id}', array('uses' => 'ApiRegionController@findByCountry'));
Route::get('api/team/by-region/{region_id}', array('uses' => 'ApiTeamController@findByRegion'));
Route::get('api/game/ticket/{game_id}', array('after' => 'image', 'uses' => 'ApiGameController@generateTicket'));


// REST API

Route::resource('api/country', 'ApiCountryController');
Route::resource('api/team', 'ApiTeamController');
Route::resource('api/game', 'ApiGameController');
Route::resource('api/game-party', 'ApiGamePartyController');
Route::resource('api/ticket-template', 'ApiTicketTemplateController');
Route::resource('api/ticket', 'ApiTicketController');

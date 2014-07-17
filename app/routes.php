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
Route::get('/about/tos', array('as' => 'about-tos', function ()
{
    return View::make('about-tos');
}));


// Forms

Route::get('games', array('uses' => 'GamesController@index', 'as' => 'games'));
Route::get('game/edit/{game_id?}', array('uses' => 'GameController@editForm', 'as' => 'game-edit'))->before('auth');
Route::get('game/book/{game_id}', array('uses' => 'GameController@bookForm', 'as' => 'game-book'))->before('auth');
Route::get('game/briefing/{game_id}', array('uses' => 'GameController@briefingForm', 'as' => 'game-briefing'))->before('auth');
Route::get('game/card/{game_id}', array('uses' => 'GameController@cardForm', 'as' => 'game-card'));
Route::get('game/check-in/{game_id}', array('uses' => 'GameController@checkInForm', 'as' => 'game-check-in'))->before('auth');
Route::get('player', array('uses' => 'DashboardController@playerForm', 'as' => 'player-dashboard'))->before('auth');
Route::get('organizer', array('uses' => 'DashboardController@organizerForm', 'as' => 'organizer-dashboard'))->before('auth');
Route::post('game/booked', array('uses' => 'PaymentController@bookingDoneForm', 'as' => 'booking-done'))->before('auth');
Route::get('game/bank-booked', array('uses' => 'PaymentController@bankTransferForm', 'as' => 'bank-booking-done'))->before('auth');
Route::post('game/pay-booked', array('uses' => 'PaymentController@payForm', 'as' => 'pay-booked'))->before('auth');


// User management

Route::get('user/login', array('uses' => 'UserController@loginForm', 'as' => 'login'))->before('guest');
Route::get('user/register', array('uses' => 'UserController@registerForm', 'as' => 'user-register'))->before('guest');
Route::post('user/login', array('uses' => 'UserController@loginEndpoint', 'as' => 'login'));
Route::get('user/logout', array('uses' => 'UserController@logout', 'as' => 'logout'))->before('auth');
Route::get('user/profile', array('uses' => 'UserController@profileForm', 'as' => 'user-profile'))->before('auth');
Route::get('user/restore-password', array('uses' => 'UserController@restoreForm', 'as' => 'user-restore-password'))->before('guest');
Route::post('user/restore-password', array('uses' => 'UserController@restoreFormEndpoint', 'as' => 'user-restore-password'))->before('guest');
Route::get('user/reset-password/{token}', array('uses' => 'UserController@resetForm', 'as' => 'user-reset-password'))->before('guest');
Route::post('user/reset-password', array('uses' => 'UserController@resetFormEndpoint', 'as' => 'user-reset-password'))->before('guest');
Route::get('user/confirm-email/{token}', array('uses' => 'UserController@confirmEmailForm', 'as' => 'confirm-email'));

// Non-REST API

Route::put('api/session', array('uses' => 'ApiSessionController@update'));
Route::post('api/user', array('uses' => 'ApiUserController@create'))->before('guest');
Route::put('api/user/{user_id}', array('uses' => 'ApiUserController@update'))->before('auth');
Route::get('api/user/incarnate/{user_id}', array('uses' => 'ApiUserController@incarnate'))->before('auth');
Route::get('api/region/by-country/{country_id}', array('uses' => 'ApiRegionController@findByCountry'));
Route::get('api/team/by-region/{region_id}', array('uses' => 'ApiTeamController@findByRegion'));
Route::get('api/game/ticket/{game_id}', array('after' => 'image', 'uses' => 'ApiGameController@generateTicket', 'as' => 'game-ticket'))->before('auth');
Route::get('api/game/xls/{game_id}', array('uses' => 'ApiGameController@exportXls', 'as' => 'game-xls'))->before('auth');
Route::get('api/ticket/validate/{barcode}', array('uses' => 'ApiTicketController@validate', 'as' => 'ticket-validate'))->before('auth');
Route::get('api/ticket/check-in/{barcode}', array('uses' => 'ApiTicketController@checkIn', 'as' => 'ticket-check-in'))->before('auth');
Route::get('testing/populate', array('uses' => 'TestingController@populate'));


// REST API

Route::resource('api/country', 'ApiCountryController');
Route::resource('api/team', 'ApiTeamController');
Route::resource('api/game', 'ApiGameController');
Route::resource('api/game-party', 'ApiGamePartyController');
Route::resource('api/ticket-template', 'ApiTicketTemplateController');
Route::resource('api/ticket', 'ApiTicketController');


App::error(function(\Exception $e, $code)
{
    if (!Config::get('app.debug'))
    {
        return View::make('error', array
        (
            'exception' => $e,
            'code'      => $code,
        ));
    }
});
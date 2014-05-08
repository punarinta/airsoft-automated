<?php

class GamesController extends BaseController
{
    public function index()
    {
        $games = Game::orderBy('starts_at', 'DESC')->get();
        return View::make('games', array('games' => $games));
    }
}
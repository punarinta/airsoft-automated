<?php

class GameController extends BaseController
{
    public function editForm($game_id = 0)
    {
        return View::make('game.edit');
    }

    public function briefingForm($game_id = 0)
    {
        return View::make('game.briefing');
    }
}
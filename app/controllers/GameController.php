<?php

class GameController extends BaseController
{
    public function editForm()
    {
        return View::make('game.edit');
    }
}
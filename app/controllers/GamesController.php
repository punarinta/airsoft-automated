<?php

class GamesController extends BaseController
{
    public function index()
    {
        return View::make('games');
    }
}
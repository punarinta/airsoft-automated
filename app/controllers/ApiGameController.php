<?php

class ApiGameController extends BaseController
{
    public function index()
    {
        return $this->execute(function()
        {
            $gamesData = [];
            $games = Game::all();

            foreach ($games as $game)
            {
                $gamesData[] = $game->toArray();
            }

            return $gamesData;
        });
    }

    public function show($game_id = 0)
    {
        return $this->execute(function() use ($game_id)
        {
            return Game::find($game_id)->toArray();
        });
    }

    public function store()
    {
        return $this->execute(function()
        {
            $game = new Game;
            $game->save();
        });
    }

    public function update($game_id = 0)
    {
        return $this->execute(function() use ($game_id)
        {
            $game = Game::find($game_id);
            $game->save();
        });
    }

    public function destroy($game_id = 0)
    {
        return $this->execute(function() use ($game_id)
        {
            $game = Game::find($game_id);
            $game->delete();
        });
    }
}
<?php

class ApiGameController extends BaseController
{
    /**
     * Lists all the games
     *
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Shows a Game by its ID
     *
     * @param int $game_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($game_id = 0)
    {
        return $this->execute(function() use ($game_id)
        {
            // all games are public for now, so no security problems here
            return Game::find($game_id)->toArray();
        });
    }

    /**
     * Creates a new Game
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        return $this->execute(function()
        {
            $game = new Game;
            $game->setOwnerId(Auth::user()->getId());
            $game->setRegionId(Input::json('region_id'));
            $game->setStartsAt(Input::json('starts_at'));
            $game->setEndsAt(Input::json('ends_at'));
            $game->setIsVisible(Input::json('is_visible'));
            $game->save();

            return $game;
        });
    }

    /**
     * Updates a Game by its ID
     *
     * @param int $game_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($game_id = 0)
    {
        return $this->execute(function() use ($game_id)
        {
            $game = Game::find($game_id);

            if (Auth::user()->getId() != $game->owner_id)
            {
                throw new \Exception('Access denied.');
            }

            $game->setRegionId(Input::json('region_id'));
            $game->setStartsAt(Input::json('starts_at'));
            $game->setEndsAt(Input::json('ends_at'));
            $game->setIsVisible(Input::json('is_visible'));
            $game->save();
        });
    }

    /**
     * Deletes a Game by its ID
     *
     * @param int $game_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($game_id = 0)
    {
        return $this->execute(function() use ($game_id)
        {
            $game = Game::find($game_id);

            if (Auth::user()->getId() != $game->owner_id)
            {
                throw new \Exception('Access denied.');
            }

            $game->delete();
        });
    }
}
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
     * Lists all the games in the given Region
     *
     * @param int $region_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function findByRegion($region_id = 0)
    {
        return $this->execute(function() use ($region_id)
        {
            $gamesData = [];
            $games = Game::where('region', '=', $region_id)->get();

            foreach ($games as $game)
            {
                $gamesData[] = $game->toArray();
            }

            return $gamesData;
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

            if (Auth::user()->getId() != $game->getOwnerId())
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

            if (Auth::user()->getId() != $game->getOwnerId())
            {
                throw new \Exception('Access denied.');
            }

            $game->delete();
        });
    }

    /**
     * Outputs a ticket PNG
     *
     * @param int $game_id
     * @return string
     */
    public function generateTicket($game_id = 0)
    {
        // http://image.intervention.io/getting_started/laravel

        // create/resize image from file
        $image = Image::make('app/data/ticket-1.png')->resize(300, 200);

        // create response and add formated image
        $response = Response::make($image->encode('png'));

        // set content-type
        $response->header('Content-Type', 'image/png');

        // et voila
        return $response;
    }
}
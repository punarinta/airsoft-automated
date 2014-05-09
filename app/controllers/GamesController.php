<?php

class GamesController extends BaseController
{
    /**
     * Draws a game calendar
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $gameData = [];
        $games = Game::orderBy('starts_at', 'DESC')->get();

        // enrich it
        foreach ($games as $game)
        {
            // get game text geo-data
            $geo = DB::table('region')
                ->join('country', 'country.id', '=', 'region.country_id')
                ->select(array('region.name AS region_name', 'country.name AS country_name'))
                ->where('region.id', '=', $game->getRegionId())
                ->first();

            $gameData[$game->getId()] = $game;
            $gameData[$game->getId()]->region_name = $geo->region_name;
            $gameData[$game->getId()]->country_name = $geo->country_name;
            $gameData[$game->getId()]->bookable = true;
            $gameData[$game->getId()]->editable = ($game->getOwnerId() == Auth::user()->getId());
        }

        return View::make('games', array('games' => $gameData));
    }
}
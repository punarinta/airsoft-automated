<?php

class DashboardController extends BaseController
{
    /**
     * @return \Illuminate\View\View
     */
    public function playerForm()
    {
        return View::make('dashboard.player');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function organizerForm()
    {
        if (!Auth::user()->getIsValidated())
        {
            // you are not validated by admins
            return View::make('user.validation-required');
        }

        $gameData = [];
        $games = Game::where('owner_id', '=', Auth::user()->getId())->get();

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
            $gameData[$game->getId()]->total_booked = 0;
            $gameData[$game->getId()]->total_earned = 0;
        }

        return View::make('dashboard.organizer', array('games' => $gameData));
    }
}
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
            $gameData[$game->getId()]->editable = (Auth::user() && $game->getOwnerId() == Auth::user()->getId());

            // check tickets
            $ticket_templates = TicketTemplate::where('game_id', '=', $game->getId())->get();
            $gameData[$game->getId()]->ticket_templates = $ticket_templates;
            $gameData[$game->getId()]->bookable = !$ticket_templates->isEmpty();
        }

        return View::make('games', array('games' => $gameData));
    }
}
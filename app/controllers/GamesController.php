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

            // check if the game hasn't yet started
            if (strtotime($game->getStartsAt()) < time())
            {
                continue;
            }

            $gameData[$game->getId()] = $game;
            $gameData[$game->getId()]->region_name = $geo->region_name;
            $gameData[$game->getId()]->country_name = $geo->country_name;
            $gameData[$game->getId()]->editable = (Auth::user() && $game->getOwnerId() == Auth::user()->getId());

            // check tickets
            $ticket_templates = TicketTemplate::where('game_id', '=', $game->getId())->get();
            $gameData[$game->getId()]->ticket_templates = $ticket_templates;
            $gameData[$game->getId()]->bookable = !$ticket_templates->isEmpty();

            if (Auth::check())
            {
                // Check that ticket is not yet booked for you
                $ticket = DB::table('ticket AS t')
                    ->select(array('t.id'))
                    ->join('ticket_template AS tt', 'tt.id', '=', 't.ticket_template_id')
                    ->where('tt.game_id', '=', $game->getId())
                    ->where('t.user_id', '=', Auth::user()->getId())
                    ->first();

                $gameData[$game->getId()]->is_booked = !empty ($ticket);
            }
        }

        return View::make('games', array('games' => $gameData));
    }
}
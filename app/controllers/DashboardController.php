<?php

class DashboardController extends BaseController
{
    /**
     * @return \Illuminate\View\View
     */
    public function playerForm()
    {
        $gameData = [];
        $gameObj = new Game;

        $tickets = DB::table('ticket')
            ->join('ticket_template', 'ticket_template.id', '=', 'ticket.ticket_template_id')
            ->join('game', 'game.id', '=', 'ticket_template.game_id')
            ->join('region', 'region.id', '=', 'game.region_id')
            ->join('country', 'country.id', '=', 'region.country_id')
            ->select(array
            (
                'region.name AS region_name',
                'country.name AS country_name',
                'game.id AS game_id',
                'game.name AS game_name',
                'game.starts_at AS game_starts_at',
            ))
            ->where('ticket.user_id', '=', Auth::user()->getId())
            ->get();

        if (empty ($tickets))
        {
            return View::make('dashboard.player-empty');
        }

        foreach ($tickets as $ticket)
        {
            $gameObj->setId($ticket->game_id);
            $gameObj->setName($ticket->game_name);
            $gameObj->setStartsAt($ticket->game_starts_at);
            $gameObj->region_name = $ticket->region_name;
            $gameObj->country_name = $ticket->country_name;
            $gameData[] = $gameObj;
        }

        return View::make('dashboard.player', array('games' => $gameData));
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

        if ($games->isEmpty())
        {
            return View::make('dashboard.organizer-empty');
        }

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
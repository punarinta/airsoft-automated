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
                'region.id AS region_id',
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
            $gameObj->region_id = $ticket->region_id;
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

        // get charging scheme
        $settings = Auth::user()->getSettingsArray();
        if (isset ($settings['charges']))
        {
            $transactionCoeffA  = $settings['charges']['transaction_a'];
            $transactionCoeffB  = $settings['charges']['transaction_a'];
            $ticketCoeffA       = $settings['charges']['ticket_a'];
            $ticketCoeffB       = $settings['charges']['ticket_b'];
        }
        else
        {
            $transactionCoeffA  = 2.95;      // percents
            $transactionCoeffB  = 300;       // monetary units
            $ticketCoeffA       = 0;         // percents
            $ticketCoeffB       = 0;         // monetary units
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

            // count tickets and income
            $bruttoIncome = 0;
            $nettoIncome = 0;

            $ticketsData = DB::table('ticket AS t')
                ->join('ticket_template AS tt', 'tt.id', '=', 't.ticket_template_id')
                ->join('game AS g', 'g.id', '=', 'tt.game_id')
                ->select(array('tt.price AS price'))
                ->where('g.id', '=', $game->getId())
                ->get();

            foreach ($ticketsData as $ticketData)
            {
                $bruttoIncome += $ticketData->price;
                $nettoIncome += $bruttoIncome * (100 - $transactionCoeffA) * (100 - $ticketCoeffA) / 10000 - $transactionCoeffB - $ticketCoeffB;
            }

            $gameData[$game->getId()] = $game;
            $gameData[$game->getId()]->region_name = $geo->region_name;
            $gameData[$game->getId()]->country_name = $geo->country_name;
            $gameData[$game->getId()]->total_booked = count($ticketsData);
            $gameData[$game->getId()]->total_brutto = number_format($bruttoIncome / 100, 2);
            $gameData[$game->getId()]->total_netto  = number_format($nettoIncome / 100, 2);
        }

        return View::make('dashboard.organizer', array('games' => $gameData));
    }
}
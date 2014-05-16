<?php

class GameController extends BaseController
{
    /**
     * Create a game or edit an existing one
     *
     * @param int $game_id
     * @return \Illuminate\View\View
     * @throws Exception
     */
    public function editForm($game_id = 0)
    {
        if (!Auth::user()->getIsValidated())
        {
            // you are not validated by admins
            return View::make('user.validation-required');
        }

        if ($game_id)
        {
            $game = Game::find($game_id);

            if (Auth::user()->getId() != $game->owner_id)
            {
                throw new \Exception('Access denied.');
            }

            $geo = DB::table('region')
                ->join('country', 'country.id', '=', 'region.country_id')
                ->select(array('region.id AS region_id', 'country.id AS country_id'))
                ->where('region.id', '=', $game->getRegionId())
                ->first();

            $game->country_id = $geo->country_id;
            $game->region_id = $geo->region_id;

            $game->parties = GameParty::where('game_id', '=', $game_id)->get();
            $game->ticket_templates = TicketTemplate::where('game_id', '=', $game_id)->get();

            // enrich ticket templates with names
            foreach ($game->ticket_templates as $k => $v)
            {
                $x = GameParty::find($v->getGamePartyId());

                $game->ticket_templates[$k]->name = date('M d', strtotime($v->getPriceDateStart())) . ' – ' . date('M d', strtotime($v->getPriceDateEnd())) . ', ' . ($x?($x->name):'all parties');
            }
        }
        else
        {
            $game = new Game;
            $game->is_visible = 1;
            $game->country_id = 1;
            $game->region_id = 0;

            $game->parties = [];
            $game->ticket_templates = [];
        }

        return View::make('game.edit', array('game' => $game));
    }

    /**
     * Book a ticket for the game
     *
     * @param int $game_id
     * @return \Illuminate\View\View
     */
    public function bookForm($game_id = 0)
    {
        // https://github.com/paymill/paymill-paybutton-examples

        $game = Game::find($game_id);

        if (empty ($game))
        {
            return Redirect::route('games')->with('flash_error', 'Game does not exist');
        }

        $game->parties = GameParty::where('game_id', '=', $game_id)->get();
        $game->ticket_templates = TicketTemplate::where('game_id', '=', $game_id)->get();

        if ($game->ticket_templates->isEmpty())
        {
            return Redirect::route('games')->with('flash_error', 'Organizer has not issued any tickets yet');
        }

        // enrich ticket templates with names
        foreach ($game->ticket_templates as $k => $v)
        {
            $x = GameParty::find($v->getGamePartyId());

            $game->ticket_templates[$k]->name = date('M d', strtotime($v->getPriceDateStart())) . ' – ' . date('M d', strtotime($v->getPriceDateEnd())) . ', ' . ($x?($x->name):'all parties');
        }

        return View::make('game.book', array
        (
            'game' => $game,
            'is_organizer' => $game->getOwnerId() == Auth::user()->getId(),
        ));
    }

    /**
     * Shows game information for the participant. Both public and private.
     *
     * @param int $game_id
     * @return \Illuminate\View\View
     */
    public function briefingForm($game_id = 0)
    {
        // check if ticket is present
        $data = DB::table('game AS g')
            ->join('ticket_template AS tt', 'tt.game_id', '=', 'g.id')
            ->join('ticket AS t', 't.ticket_template_id', '=', 'tt.id')
            ->select(array('g.id'))
            ->where('g.id', '=', $game_id)
            ->where('t.user_id', '=', Auth::user()->getId())
            ->first();

        // TODO: check payment if tt.is_cash = 0

        if (empty ($data))
        {
            return View::make('game.not-booked', array());
        }

        return View::make('game.briefing', array());
    }

    /**
     * Shows a message after the game ticket is booked (and/or paid)
     *
     * @param int $game_id
     * @return \Illuminate\View\View
     */
    public function bookingDoneForm($game_id = 0)
    {
        return View::make('game.booked', array());
    }
}
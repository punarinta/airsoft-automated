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

            $game->country_id = isset ($geo->country_id) ? $geo->country_id : 0;
            $game->region_id = isset ($geo->region_id) ? $geo->region_id : 0;
            $game->settings_array = $game->getSettingsArray();

            $game->parties = GameParty::where('game_id', '=', $game_id)->get();
            $game->ticket_templates = TicketTemplate::where('game_id', '=', $game_id)->get();

            // enrich ticket templates with names
            foreach ($game->ticket_templates as $k => $v)
            {
                $x = GameParty::find($v->getGamePartyId());

                $game->ticket_templates[$k]->name = date('M d', strtotime($v->getPriceDateStart())) . ' – ' . date('M d', strtotime($v->getPriceDateEnd())) . ', ' . ($x?($x->name):'all parties');
                $game->ticket_templates[$k]->price = number_format($game->ticket_templates[$k]->price / 100, 0, '.', '');
            }
        }
        else
        {
            $game = new Game;
            $game->is_visible = 1;
            $game->country_id = 1;
            $game->region_id = 0;
            $game->settings_array = [];
            $game->starts_at = '+30 days noon';
            $game->ends_at = '+31 days noon';

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
        $game = Game::find($game_id);

        if (empty ($game))
        {
            return Redirect::route('games')->with('flash_error', 'Game does not exist');
        }

        if (!Auth::user()->getIsEmailValidated())
        {
            return View::make('user.email-validation-required');
        }

        $game->parties = GameParty::where('game_id', '=', $game_id)->get();
        $game->ticket_templates = TicketTemplate::where('game_id', '=', $game_id)->get();

        $org = DB::table('user AS u')
            ->leftJoin('team AS t', 't.id', '=', 'u.team_id')
            ->select(array('t.name AS team_name', 'u.nick AS user_nick'))
            ->where('u.id', '=', $game->getOwnerId())
            ->first();
        $game->organizer = isset ($org->user_nick) ? $org->user_nick : '&mdash;';

        if ($game->ticket_templates->isEmpty())
        {
            return Redirect::route('games')->with('flash_error', 'Organizer has not issued any tickets yet');
        }

        // enrich ticket templates with names
        foreach ($game->ticket_templates as $k => $v)
        {
            // remove outdated tickets
            if (strtotime($v->getPriceDateStart()) > time() || strtotime($v->getPriceDateEnd()) < time())
            {
                unset ($game->ticket_templates[$k]);
                continue;
            }

            $x = GameParty::find($v->getGamePartyId());

            $game->ticket_templates[$k]->name = date('M d', strtotime($v->getPriceDateStart())) . ' – ' . date('M d', strtotime($v->getPriceDateEnd())) . ', ' . ($x?($x->name):'all parties');
        }

        $profile = Auth::user()->getProfileArray();

        $requirements = [];
        $requirementsOk = true;

        if ($game->getSetting('req.nick') == 1)
        {
            if (!strlen(Auth::user()->getNick()))
            {
                $requirementsOk = false;
            }
            $requirements[] = array('Nickname', strlen(Auth::user()->getNick()) > 0);
        }
        if ($game->getSetting('req.phone') == 1)
        {
            if (!@strlen($profile['phone'][0]))
            {
                $requirementsOk = false;
            }
            $requirements[] = array('Phone', isset ($profile['phone'][0]) && strlen($profile['phone'][0]) > 0);
        }
        if ($game->getSetting('req.age') == 1)
        {
            if (!strlen(Auth::user()->getBirthDate()))
            {
                $requirementsOk = false;
            }
            $requirements[] = array('Age', strlen(Auth::user()->getBirthDate()) > 0);
        }

        return View::make('game.book', array
        (
            'game'            => $game,
            'is_organizer'    => $game->getOwnerId() == Auth::user()->getId(),
            'requirements'    => $requirements,
            'requirements_ok' => $requirementsOk,
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
            ->select(array
            (
                'g.id AS game_id',
                't.id AS ticket_id',
                't.status AS status',
                'tt.price AS price',
                'g.settings AS settings'
            ))
            ->where('g.id', '=', $game_id)
            ->where('t.user_id', '=', Auth::user()->getId())
            ->where('t.status', '|', Ticket::STATUS_BOOKED | Ticket::STATUS_PAID)
            ->first();

        $data->ticket_code = strtoupper(Bit::base36_encode(Bit::swap15($data->ticket_id)));

        if (empty ($data))
        {
            return View::make('game.not-booked', array());
        }

        $settings = json_decode($data->settings, true);
        if (isset ($settings['map']['source']) && strlen ($settings['map']['source']) && $settings['map']['type'] == 1)
        {
            $mapSrc = 'https://mapsengine.google.com/map/embed?mid=' . $settings['map']['source'];
        }
        else
        {
            $mapSrc = null;
        }

        // check if there's a factor
        $tickets = Ticket::where('host_ticket_id', '=', $data->ticket_id)->get();
        $factor = 1 + count($tickets);

        $data->price = $data->price * $factor;
        $data->factor = $factor;

        return View::make('game.briefing', array
        (
            'data' => $data,
            'map'  => $mapSrc,
        ));
    }

    /**
     * Allows you to check-in players to the game
     *
     * @param int $game_id
     * @return \Illuminate\View\View
     */
    public function checkInForm($game_id = 0)
    {
        $game = Game::find($game_id);

        if (empty ($game) || $game->getOwnerId() != Auth::user()->getId())
        {
            return Redirect::route('games')->with('flash_error', 'Game does not exist or no access');
        }

        // get enriched ticket list
        $ticketsData = DB::table('ticket AS t')
            ->join('ticket_template AS tt', 'tt.id', '=', 't.ticket_template_id')
            ->leftJoin('game_party AS gp', 'gp.id', '=', 'tt.game_party_id')
            ->join('game AS g', 'g.id', '=', 'tt.game_id')
            ->join('user AS u', 'u.id', '=', 't.user_id')
            ->join('team AS tm', 'tm.id', '=', 'u.team_id', 'left outer')
            ->select(array
            (
                'u.nick AS nick',
                'u.email AS email',
                'tm.name AS team_name',
                'gp.name AS game_party_name',
                'tt.is_cash AS is_cash',
                't.status AS ticket_status',
                't.id AS id',
            ))
            ->where('g.id', '=', $game_id)
            ->get();

        return View::make('game.check-in', array
        (
            'game'    => $game,
            'tickets' => $ticketsData,
        ));
    }

    /**
     * Public game presentation
     *
     * @param int $game_id
     * @return \Illuminate\View\View
     */
    public function cardForm($game_id = 0)
    {
        $game = Game::find($game_id);

        if (empty ($game))
        {
            return Redirect::route('games')->with('flash_error', 'Game does not exist or no access');
        }

        $settings = $game->getSettingsArray();
        $game->map = null;
        $game->poster = '/gfx/dummy-posters/' . mt_rand(1, 8) . '.jpg';

        if (isset ($settings['map']['source']) && strlen ($settings['map']['source']) && $settings['map']['type'] == 1)
        {
            $game->map = 'https://mapsengine.google.com/map/embed?mid=' . $settings['map']['source'];
        }
        if (isset ($settings['poster']) && strlen ($settings['poster']))
        {
            $game->poster = $settings['poster'];
        }

        $geo = DB::table('region')
            ->join('country', 'country.id', '=', 'region.country_id')
            ->select(array('region.name AS region_name', 'country.name AS country_name'))
            ->where('region.id', '=', $game->getRegionId())
            ->first();

        $ticketsPresent = false;
        $gameParties = GameParty::where('game_id', '=', $game_id)->get();

        foreach ($gameParties as $gameParty)
        {
            // check if there are tickets
            $booking = DB::table('ticket')
                ->select(array(DB::raw('COUNT(id) as booked')))
                ->where('game_party_id', '=', $gameParty->getId())
                ->get();

            $ticketsBooked = isset ($booking->booked) ? $booking->booked : 0;

            $gameParty->ticketsBooked = $ticketsBooked;
            $gameParty->ticketsLeft = $gameParty->getPlayersLimit() - $ticketsBooked;

            if ($gameParty->getPlayersLimit())
            {
                $gameParty->callToAction = number_format($gameParty->ticketsLeft / $gameParty->getPlayersLimit(), 0);
            }
            else
            {
                $gameParty->callToAction = '0';
            }

            if ($gameParty->ticketsLeft > 0 && $game->getIsVisible())
            {
                $ticketsPresent = true;
            }
        }

        return View::make('game.card', array
        (
            'game'      => $game,
            'geo'       => $geo,
            'parties'   => $gameParties,
            'bookable'  => $ticketsPresent,
        ));
    }
}
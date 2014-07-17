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
            $game->setName(strip_tags(Input::json('name')));
            $game->setRegionId(Input::json('region_id'));
            $game->setStartsAt(Input::json('starts_at'));
            $game->setEndsAt(Input::json('ends_at'));
            $game->setIsVisible(Input::json('is_visible'));

            $settings = [];
            $settings['url'] = strip_tags(Input::json('url'));
            $settings['poster'] = strip_tags(Input::json('poster'));
            $settings['req']['nick'] = Input::json('req_nick');
            $settings['req']['phone'] = Input::json('req_phone');
            $settings['req']['age'] = Input::json('req_age');
            $game->setSettingsArray($settings);

            $game->save();

            return $game->toArray();
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

            $settings = $game->getSettingsArray();

            if (Input::json('cmd_save_map'))
            {
                if (Input::json('map_type_id') == 1)
                {
                    preg_match('/([A-Za-z0-9]{12}\.[A-Za-z0-9]{12})$/', Input::json('map_source'), $matches);
                    $mapSrc = isset ($matches[0]) ? $matches[0] : '';
                }
                else
                {
                    $mapSrc = Input::json('map_source');
                }
                $settings['map']['source'] = $mapSrc;
                $settings['map']['type'] = Input::json('map_type_id');
            }
            else
            {
                $game->setName(strip_tags(Input::json('name')));
                $game->setRegionId(Input::json('region_id'));
                $game->setStartsAt(Input::json('starts_at'));
                $game->setEndsAt(Input::json('ends_at'));
                $game->setIsVisible(Input::json('is_visible'));

                $settings = $game->getSettingsArray();
                $settings['url'] = strip_tags(Input::json('url'));
                $settings['poster'] = strip_tags(Input::json('poster'));
                $settings['req']['nick'] = Input::json('req_nick');
                $settings['req']['phone'] = Input::json('req_phone');
                $settings['req']['age'] = Input::json('req_age');
            }

            $game->setSettingsArray($settings);

            $game->save();

            return $game->toArray();
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
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    public function generateTicket($game_id = 0)
    {
        // http://image.intervention.io/getting_started/laravel

        // get my ticket for the game
        $ticketData = DB::table('ticket AS t')
            ->select(array('t.id AS id', 'g.name AS game_name', 'gp.name AS game_party_name', 'u.nick AS player_nick'))
            ->join('ticket_template AS tt', 'tt.id', '=', 't.ticket_template_id')
            ->join('game_party AS gp', 'gp.id', '=', 't.game_party_id')
            ->join('game AS g', 'g.id', '=', 'tt.game_id')
            ->join('user AS u', 'u.id', '=', 't.user_id')
            ->where('tt.game_id', '=', $game_id)
            ->where('t.user_id', '=', Auth::user()->getId())
            ->where('t.status', '|', Ticket::STATUS_BOOKED | Ticket::STATUS_PAID)
            ->first();

        if (empty ($ticketData))
        {
            throw new \Exception('No ticket exist');
        }

        $barcode = str_pad(Bit::base36_encode(Bit::swap15($ticketData->id)), 8, '0', STR_PAD_LEFT);
        $barcodeImage = (new Barcode39($barcode))->draw();

        $width  = 500;
        $height = 200;

        $rootDir = Config::get('app.live') ? '/site/public/../' : '';

        // create/resize image from file
        $image = Image::make($rootDir . 'app/data/ticket-1.png');

        $font = function($font) use ($rootDir)
        {
            $font->file($rootDir . 'app/data/deja-vu-sans.ttf');
            $font->size(16);
        };

        $team = Team::find(Auth::user()->getTeamId());
        if (empty ($team))
        {
            $teamName = '—';
        }
        else
        {
            $teamName = $team->getName();
        }

        $image->rectangle(0, 0, 0, $width - 1, $height - 1, false);

        // game/player data
        $image->text('Game: «' . $ticketData->game_name . '»', 15, 25, $font);
        $image->text('Game party: «' . $ticketData->game_party_name . '»', 15, 50, $font);
        $image->text('Player: ' . $ticketData->player_nick, 15, 75, $font);
        $image->text('Team: ' . $teamName, 15, 100, $font);

        // add barcode
        $image->insert($barcodeImage, 10, 110);

        $response = Response::make($image->encode('png'));
        $response->header('Content-Type', 'image/png');

        return $response;
    }

    /**
     * Exports a participants list into an Excel file
     *
     * @param int $game_id
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    public function exportXls($game_id = 0)
    {
        return $this->execute(function() use ($game_id)
        {
            $game = Game::find($game_id);

            if (Auth::user()->getId() != $game->getOwnerId())
            {
                throw new \Exception('Access denied.');
            }

            // get enriched ticket list
            $ticketsData = DB::table('ticket AS t')
                ->join('ticket_template AS tt', 'tt.id', '=', 't.ticket_template_id')
                ->join('game_party AS gp', 'gp.id', '=', 'tt.game_party_id')
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

            Excel::create('participants', function($excel) use ($ticketsData)
            {
                $excel->sheet('Players', function($sheet) use ($ticketsData)
                {
                    $i = 1;

                    $sheet->appendRow($i++, array
                    (
                        'Ticket',
                        'Nickname',
                        'Team',
                        'Fraction',
                        'Cash payment',
                        'Paid',
                    ));

                    $sheet->cells('A1:F1', function($cells)
                    {
                        $cells->setFontWeight('bold');
                    });

                    foreach ($ticketsData as $item)
                    {
                        $sheet->appendRow($i++, array
                        (
                            strtoupper(str_pad(Bit::base36_encode(Bit::swap15($item->id)), 8, '0', STR_PAD_LEFT)),
                            $item->nick,
                            $item->team_name,
                            $item->game_party_name,
                            $item->is_cash ? '+' : '–',
                            $item->ticket_status & Ticket::STATUS_PAID ? '+' : '–',
                        ));
                    }

                    $sheet->setPageMargin(0.05);
                    $sheet->setFont(array('size' => '10'));
                    $sheet->freezeFirstRow();
                    $sheet->setAutoSize(true);
                });
            })->download('xls');
        });
    }
}
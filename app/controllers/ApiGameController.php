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
            ->where('t.status', '=', Ticket::STATUS_READY)
            ->first();

        if (empty ($ticketData))
        {
            throw new \Exception('No ticket exist');
        }

        $barcode = str_pad(Bit::swap15($ticketData->id), 10, '0', STR_PAD_LEFT);
        $barcodeImage = (new Barcode39($barcode))->draw();

        // create/resize image from file
        $image = Image::make('app/data/ticket-1.png')->resize(500, 200);

        $font = function($font)
        {
            $font->file('app/data/deja-vu-sans.ttf');
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

        // game/player data
        $image->text('Game: «' . $ticketData->game_name . '»', 20, 20, $font);
        $image->text('Game party: «' . $ticketData->game_party_name . '»', 20, 40, $font);
        $image->text('Player: ' . $ticketData->player_nick, 20, 60, $font);
        $image->text('Team: ' . $teamName, 20, 80, $font);

        // add barcode
        $image->insert($barcodeImage, 100, 100);

        $response = Response::make($image->encode('png'));
        $response->header('Content-Type', 'image/png');

        return $response;
    }
}
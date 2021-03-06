<?php

class ApiGamePartyController extends BaseController
{
    public function index()
    {
        return $this->execute(function()
        {
            $gamePartiesData = [];
            $gameParties = GameParty::all();

            foreach ($gameParties as $gameParty)
            {
                $gamePartiesData[] = $gameParty->toArray();
            }

            return $gamePartiesData;
        });
    }

    public function show($game_party_id = 0)
    {
        return $this->execute(function() use ($game_party_id)
        {
            return GameParty::find($game_party_id)->toArray();
        });
    }

    /**
     * Creates a Game Party
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        return $this->execute(function()
        {
            $gameParty = new GameParty;
            $gameParty->setName(strip_tags(Input::json('name')));
            $gameParty->setGameId(Input::json('game_id'));
            $gameParty->setPlayersLimit(Input::json('players_limit'));
            $gameParty->setDescription(strip_tags(Input::json('description')));
            $gameParty->save();

            return $gameParty->toArray();
        });
    }

    /**
     * @param int $game_party_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($game_party_id = 0)
    {
        return $this->execute(function() use ($game_party_id)
        {
            $gameParty = GameParty::find($game_party_id);

            // check permissions
            $game = Game::find($gameParty->getGameId());
            if (Auth::user()->getId() != $game->getOwnerId())
            {
                throw new \Exception('Access denied.');
            }

            $gameParty->setName(strip_tags(Input::json('name')));
            $gameParty->setGameId(Input::json('game_id'));
            $gameParty->setPlayersLimit(Input::json('players_limit'));
            $gameParty->setDescription(strip_tags(Input::json('description')));
            $gameParty->save();
        });
    }

    /**
     * @param int $game_party_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($game_party_id = 0)
    {
        return $this->execute(function() use ($game_party_id)
        {
            $gameParty = GameParty::find($game_party_id);

            // check permissions
            $game = Game::find($gameParty->getGameId());
            if (Auth::user()->getId() != $game->getOwnerId())
            {
                throw new \Exception('Access denied.');
            }

            // check that there are no tickets fot this GP
            $tickets = Ticket::where('game_party_id', '=', $gameParty->getId())->get()->toArray();

            if (!empty ($tickets))
            {
                throw new \Exception('This party already has tickets booked, you cannot delete it.<br>Try to edit the info instead.');
            }

            $gameParty->delete();
        });
    }
}
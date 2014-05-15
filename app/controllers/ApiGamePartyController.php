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

    public function store()
    {
        return $this->execute(function()
        {
            $gameParty = new GameParty;
            $gameParty->setName(Input::json('name'));
            $gameParty->setGameId(Input::json('game_id'));
            $gameParty->setPlayersLimit(Input::json('players_limit'));
            $gameParty->save();
        });
    }

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

            $gameParty->setName(Input::json('name'));
            $gameParty->setGameId(Input::json('game_id'));
            $gameParty->setPlayersLimit(Input::json('players_limit'));
            $gameParty->save();
        });
    }

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

            $gameParty->delete();
        });
    }
}
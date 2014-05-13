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
            $gameParty->save();
        });
    }

    public function update($game_party_id = 0)
    {
        return $this->execute(function() use ($game_party_id)
        {
            $gameParty = GameParty::find($game_party_id);
            $gameParty->save();
        });
    }

    public function destroy($game_party_id = 0)
    {
        return $this->execute(function() use ($game_party_id)
        {
            $gameParty = GameParty::find($game_party_id);
            $gameParty->delete();
        });
    }
}
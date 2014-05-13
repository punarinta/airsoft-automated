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

    }

    public function update()
    {

    }

    public function destroy()
    {

    }
}
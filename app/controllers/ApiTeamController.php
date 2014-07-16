<?php

class ApiTeamController extends BaseController
{
    /**
     * @param $region_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function findByRegion($region_id = 0)
    {
        return $this->execute(function() use ($region_id)
        {
            $teamsData = [];
            $teams = Team::where('region_id', '=', $region_id)->get(array('id', 'name'));

            foreach ($teams as $team)
            {
                $teamsData[] = $team->toArray();
            }

            return $teamsData;
        });
    }

    /**
     * Creates a new team
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        return $this->execute(function()
        {
            if (!strlen($name = trim(Input::json('name'))))
            {
                throw new \Exception('Team name cannot be empty');
            }

            // disable team creation for unvalidated users
            if (!Auth::user()->getIsEmailValidated())
            {
                throw new \Exception('Validate your email first');
            }

            $team = new Team;
            $team->setName(strip_tags($name));
            $team->setRegionId(Input::json('region_id'));
            $team->setOwnerId(Auth::user()->getId());
            $team->setUrl(strip_tags(Input::json('url')));
            $team->save();

            $user = Auth::getUser();
            $user->setTeamId($team->getId());
            $user->save();
        });
    }

    /**
     * @param int $team_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($team_id = 0)
    {
        return $this->execute(function() use ($team_id)
        {
            if (!strlen($name = trim(Input::json('name'))))
            {
                throw new \Exception('Team name cannot be empty');
            }

            if (!$team = Team::find($team_id))
            {
                throw new \Exception('Team does not exist');
            }

            // your team can be updated only by you
            if (Auth::user()->getId() != $team->getOwnerId())
            {
                throw new \Exception('Access denied.');
            }

            // disable team creation for unvalidated users
            if (!Auth::user()->getIsEmailValidated())
            {
                throw new \Exception('Validate your email first');
            }

            $team->setName(strip_tags($name));
            $team->setUrl(strip_tags(Input::json('url')));
            $team->setRegionId(Input::json('region_id'));
            $team->save();
        });
    }
}
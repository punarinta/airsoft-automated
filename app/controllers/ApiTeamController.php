<?php

class ApiTeamController extends BaseController
{
    /**
     * @param $region_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function findByRegion($region_id = 0)
    {
        try
        {
            $teamsData = [];
            $teams = Team::where('region_id', '=', $region_id)->get(array('id', 'name'));

            foreach ($teams as $team)
            {
                $teamsData[] = $team->toArray();
            }

            $json = array
            (
                'data' => $teamsData,
            );
        }
        catch (\Exception $e)
        {
            $json = array
            (
                'errMsg' => $e->getMessage(),
            );
        }

        return Response::json($json);
    }
}
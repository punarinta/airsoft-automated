<?php

class ApiRegionController extends BaseController
{
    /**
     * @param $country_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function findByCountry($country_id = 0)
    {
        return $this->execute(function() use ($country_id)
        {
            $regionsData = [];
            $regions = Region::where('country_id', '=', $country_id)->get(array('id', 'name'));

            foreach ($regions as $region)
            {
                $regionsData[] = $region->toArray();
            }

            return $regionsData;
        });
    }
}
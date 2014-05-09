<?php

class ApiRegionController extends BaseController
{
    /**
     * @param $country_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function byCountry($country_id = 0)
    {
        try
        {
            $regionsData = [];
            $regions = Region::where('country_id', '=', $country_id)->get(array('id', 'name'));

            foreach ($regions as $region)
            {
                $regionsData[] = $region->toArray();
            }

            $json = array
            (
                'data' => $regionsData,
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
<?php

class ApiCountryController extends BaseController
{
    /**
     * @param int $country_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($country_id = 0)
    {
        try
        {
            $countriesData = [];

            if ($country_id)
            {
                $countries = Country::where('id', '=', $country_id)->get(array('id', 'name'));
            }
            else
            {
                $countries = Country::all(array('id', 'name'));
            }

            foreach ($countries as $country)
            {
                $countriesData[] = $country->toArray();
            }

            $json = array
            (
                'data' => $countriesData,
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
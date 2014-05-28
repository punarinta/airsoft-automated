<?php

class ApiCountryController extends BaseController
{
    /**
     * Lists all the Country objects
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->execute(function()
        {
            $countriesData = [];
            $countries = Country::all();

            foreach ($countries as $country)
            {
                $countriesData[] = $country->toArray();
            }

            return $countriesData;
        });
    }

    /**
     * Shows a Country by its ID
     *
     * @param int $country_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($country_id = 0)
    {
        return $this->execute(function() use ($country_id)
        {
            return Country::find($country_id)->toArray();
        });
    }
}
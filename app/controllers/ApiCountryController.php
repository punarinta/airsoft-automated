<?php

class ApiCountryController extends BaseController
{
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

    public function show($country_id = 0)
    {
        return $this->execute(function() use ($country_id)
        {
            return Country::find($country_id)->toArray();
        });
    }
}
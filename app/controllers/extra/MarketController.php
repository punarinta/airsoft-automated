<?php

namespace Extra;

class MarketController extends \BaseController
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return \View::make('market.index');
    }
}
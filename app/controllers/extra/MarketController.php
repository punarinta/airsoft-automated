<?php

namespace Extra;

class MarketController extends \BaseController
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $shops = \Shop::all();

        return \View::make('market.index', array
        (
            'shops' => $shops,
        ));
    }
}
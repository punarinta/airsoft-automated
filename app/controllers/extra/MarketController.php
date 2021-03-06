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

        foreach ($shops as $k => $shop)
        {
            $votes = \DB::table('shop_vote')
                ->select(array(\DB::raw('SUM(vote) as rating')))
                ->where('shop_id', '=', $shop->getId())
                ->get();

            $shops[$k]->rating = (int) $votes[0]->rating;
        }

        return \View::make('market.index', array
        (
            'shops' => $shops,
        ));
    }

    /**
     * Upvote the shop
     *
     * @param int $shop_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function vote($shop_id = 0)
    {
        return $this->execute(function() use ($shop_id)
        {
            if (!$shop_id)
            {
                return false;
            }

            $userId = \Auth::user()->getId();

            $vote = \ShopVote::where('shop_id', '=', $shop_id)->where('user_id', '=', $userId)->get();

            if ($vote->count())
            {
                return false;
            }

            $shopVote = new \ShopVote;
            $shopVote->setShopId($shop_id);
            $shopVote->setUserId($userId);
            $shopVote->setVote(1);
            $shopVote->save();

            return true;
        });
    }
}
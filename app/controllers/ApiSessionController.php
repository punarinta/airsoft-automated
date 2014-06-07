<?php

class ApiSessionController extends BaseController
{
    /**
     * Save arbitrary params to PHP session
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update()
    {
        return $this->execute(function()
        {
            if ($loc = Input::json('locale'))
            {
                // save locale to session
                Session::put('locale', $loc);

                if (Auth::check())
                {
                    // save locale to user
                    $user = User::find(Auth::user()->id);
                    $settings = $user->getSettingsArray();
                    $settings['locale'] = $loc;
                    $user->setSettingsArray($settings);
                    $user->save();
                }
            }
        });
    }
}
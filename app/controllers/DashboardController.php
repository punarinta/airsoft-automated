<?php

class DashboardController extends BaseController
{
    /**
     * @return \Illuminate\View\View
     */
    public function playerForm()
    {
        return View::make('dashboard.player');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function organizerForm()
    {
        if (!Auth::user()->getIsValidated())
        {
            // you are not validated by admins
            return View::make('user.validation-required');
        }

        return View::make('dashboard.organizer');
    }
}
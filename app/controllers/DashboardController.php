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
        return View::make('dashboard.organizer');
    }
}
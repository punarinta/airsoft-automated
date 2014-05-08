<?php

class DashboardController extends BaseController
{
    public function playerForm()
    {
        return View::make('dashboard.player');
    }

    public function organizerForm()
    {
        return View::make('dashboard.organizer');
    }
}
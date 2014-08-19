<?php

namespace Extra;

class ToolsController extends \BaseController
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function extra()
    {
        return \View::make('extra', array());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function fps()
    {
        return \View::make('tools.fps', array());
    }
}
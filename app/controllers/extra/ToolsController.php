<?php

namespace Extra;

class ToolsController extends \BaseController
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function fps()
    {
        return \View::make('tools.fps', array());
    }
}
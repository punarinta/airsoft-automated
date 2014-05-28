<?php

class BaseController extends Controller
{
	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if (!is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

    /**
     * Wrapper for a JSON API endpoint
     *
     * @param string $insetFunction
     * @return \Illuminate\Http\JsonResponse
     */
    public function execute($insetFunction = '')
    {
        try
        {
            $json = array('data' => $insetFunction());
        }
        catch (\Exception $e)
        {
            $json = array('errMsg' => $e->getMessage());
        }

        return Response::json($json);
    }
}
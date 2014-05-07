<?php

class ApiUserController extends BaseController
{
    /**
     * Quickly register user by providing his email only
     *
     * @param null $email
     * @return \Illuminate\View\View
     */
    public function register($email = null)
    {
        try
        {
            // check that email is not used

            if (User::where('email', $email)->first())
            {
                throw new \Exception('Email is in use');
            }

            $json = array
            (
                'email' => $email,
            );
        }
        catch (\Exception $e)
        {
            $json = array
            (
                'errMsg' => $e->getMessage(),
            );
        }

        return View::make('json')->with('data', $json);
    }
}
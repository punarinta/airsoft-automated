<?php

class UserController extends BaseController
{
	public function loginForm()
	{
        return View::make('user.login');
	}

	public function loginEndpoint()
	{
        $user = array
        (
            'email' => Input::get('username'),
            'password' => Input::get('password')
        );

        if (Auth::attempt($user))
        {
            return Redirect::route('home')->with('flash_notice', 'You are successfully logged in.');
        }

        // authentication failure! lets go back to the login page
        return Redirect::route('login')->with('flash_error', 'Your username/password combination was incorrect.')->withInput();
	}

    public function logout()
    {
        Auth::logout();
        return Redirect::route('home')->with('flash_notice', 'You are successfully logged out.');
    }

    public function profileForm()
    {
        return View::make('user.profile');
    }

    /**
     * Display the password reminder view.
     *
     * @return Response
     */
    public function restoreForm()
    {
        return View::make('user.restore');
    }

    /**
     * Handle a POST request to remind a user of their password.
     *
     * @return Response
     */
    public function restoreFormEndpoint()
    {
        $email = Input::input('email');
        $user = User::where('email', '=', $email)->first();

        if (empty($user))
        {
            return Redirect::back()->with('flash_notice', Lang::get('not OK'));
        }

        $token = md5(microtime(true) . 'some-stuff');

        DB::table('password_reminder')->insert(array
        (
            'email'      => $email,
            'token'      => $token,
            'created_at' => \Carbon\Carbon::now(),
        ));


        if (Config::get('mail.mandrill_on'))
        {
            Mandrill::request('messages/send', array
            (
                'message' => array
                (
                    'subject'       => 'Password reminder',
                    'html'          => 'Go through this link to reset your password: ' . URL::to('user/reset-password', array($token)),
                    'from_email'    => Config::get('app.emails.noreply'),
                    'to'            => array(array('email' => $email))
                )
            ));
        }

        return Redirect::back()->with('flash_notice', Lang::get('OK'));
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param  string  $token
     * @return Response
     */
    public function resetForm($token = null)
    {
        if (is_null($token))
        {
            App::abort(404);
        }

        return View::make('user.reset')->with('token', $token);
    }

    /**
     * Handle a POST request to reset a user's password.
     *
     * @return Response
     */
    public function resetFormEndpoint()
    {
        $credentials = Input::only('email', 'password', 'password_confirmation', 'token');

        $response = Password::reset($credentials, function($user, $password)
        {
            $user->password = Hash::make($password);

            $user->save();
        });

        switch ($response)
        {
            case Password::INVALID_PASSWORD:
            case Password::INVALID_TOKEN:
            case Password::INVALID_USER:
                return Redirect::back()->with('error', Lang::get($response));

            case Password::PASSWORD_RESET:
                return Redirect::to('/');
        }
    }
}

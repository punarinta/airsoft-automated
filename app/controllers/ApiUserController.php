<?php

class ApiUserController extends BaseController
{
    /**
     * Quickly register user by providing his email only
     *
     * @return \Illuminate\View\View
     */
    public function register()
    {
        try
        {
            $email = Input::json('email');

            // check that email is not used

            $validator = Validator::make(array('email' => $email), array
            (
                'email' => 'required|email|unique:user',
            ));

            if (!$validator->passes())
            {
                throw new \Exception('Email is in use');
            }

            $password = Str::random(8, 'alpha-numeric');

            $user = new User;
            $user->setEmail($email);
            $user->setPassword(Hash::make($password));
            $user->save();

            if (Config::get('mail.mandrill_on'))
            {
                Mandrill::request('messages/send', array
                (
                    'message' => array
                    (
                        'subject'       => 'Welcome to ' . Config::get('app.company.name') . '!',
                        'html'          => 'Your password is ' . $password . '. We recommend you to change it.',
                        'from_email'    => Config::get('app.emails.noreply'),
                        'to'            => array(array('email' => $email))
                    )
                ));
            }

            $json = array
            (
                'email' => $email,
                'password' => $password,
            );
        }
        catch (\Exception $e)
        {
            $json = array
            (
                'errMsg' => $e->getMessage(),
            );
        }

        return Response::json($json);
    }
}
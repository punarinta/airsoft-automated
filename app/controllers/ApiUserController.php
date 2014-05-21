<?php

class ApiUserController extends BaseController
{
    /**
     * Quickly register user by providing his email only
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return $this->execute(function()
        {
            $email = Input::json('email');

            // check that email is not used

            $validator = Validator::make(array('email' => $email), array
            (
                'email' => 'required|email|unique:user',
            ));

            if (!$validator->passes())
            {
                $messages = $validator->messages();
                throw new \Exception('Email is in use: ' . $messages->first('email'));
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

            if (Input::json('autologin'))
            {
                Auth::login($user);
            }

            return array
            (
                'email'     => $email,
                'password'  => $password,
            );
        });
    }

    /**
     * @param int $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($user_id = 0)
    {
        return $this->execute(function() use ($user_id)
        {
            // you can update only yourself for now
            if (Auth::user()->getId() != $user_id)
            {
                throw new \Exception('Access denied.');
            }

            $user = User::find($user_id);
            $user->setNick(Input::json('nick'));
            $user->setBirthDate(Input::json('birth_date'));
            $user->setTeamId(Input::json('team_id'));
            $user->save();
        });
    }
}
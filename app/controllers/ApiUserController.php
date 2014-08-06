<?php

class ApiUserController extends BaseController
{
    /**
     * Quickly registers user by providing his email only
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
            //    $messages = $validator->messages();
                throw new \Exception('Email is in use.'/* . $messages->first('email')*/);
            }

            $password = Str::random(8, 'alpha-numeric');

            $user = new User;
            $user->setEmail($email);
            $user->setPassword(Hash::make($password));
            $user->save();

            $confirmationCode = str_replace('/', '-', base64_encode(Bit::encrypt((string) $user->getId(), 'S=pi*r^2')));
            $confirmationLink = URL::route('confirm-email', array($confirmationCode));

            if (Config::get('mail.mandrill_on'))
            {
                Mandrill::request('messages/send-template', array
                (
                    'template_name'     => 'user-created',
                    'template_content'  => array(),
                    'acync'             => true,
                    'message' => array
                    (
                        'subject'       => 'Welcome to ' . Config::get('app.company.name') . '!',
                        'from_email'    => Config::get('app.emails.noreply'),
                        'from_name'     => Config::get('app.company.name'),
                        'to' => array
                        (
                            array
                            (
                                'email' => $email,
                                'name'  => $email,
                            ),
                        ),
                        'global_merge_vars' => array
                        (
                            array
                            (
                                'name'      => 'password',
                                'content'   => $password,
                            ),
                            array
                            (
                                'name'      => 'confirmation_url',
                                'content'   => $confirmationLink,
                            ),
                        ),
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
     * Updates information about User (you only)
     *
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
            $user->setNick(strip_tags(Input::json('nick')));
            $user->setBirthDate(Input::json('birth_date'));
            $user->setTeamId(Input::json('team_id'));

            $settings = $user->getSettingsArray();

            if (Input::json('locale')) $settings['locale'] = Input::json('locale');

            $user->setSettingsArray($settings);


            // extra data that might be required by organizer
            $profile = $user->getProfileArray();

            if (Input::json('first_name')) $profile['first_name'] = strip_tags(Input::json('first_name'));
            if (Input::json('last_name')) $profile['last_name'] = strip_tags(Input::json('last_name'));
            if (Input::json('ssn')) $profile['ssn'] = strip_tags(Input::json('ssn'));
            if (Input::json('phone')) $profile['phone'][0] = preg_replace('/\D+/', '', Input::json('phone'));

            if (Input::json('addr_street')) $profile['address']['street'] = strip_tags(Input::json('addr_street'));
            if (Input::json('addr_zip')) $profile['address']['zip'] = preg_replace('/\D+/', '', Input::json('addr_zip'));
            if (Input::json('addr_city')) $profile['address']['city'] = strip_tags(Input::json('addr_city'));
            if (Input::json('addr_country')) $profile['address']['country'] = strip_tags(Input::json('addr_country'));

            if (Input::json('bank_account')) $profile['bank']['account'] = strip_tags(Input::json('bank_account'));
            if (Input::json('bank_iban')) $profile['bank']['iban'] = strip_tags(Input::json('bank_iban'));
            if (Input::json('bank_swift')) $profile['bank']['swift'] = strip_tags(Input::json('bank_swift'));
            if (Input::json('bank_name')) $profile['bank']['name'] = strip_tags(Input::json('bank_name'));

            $user->setProfileArray($profile);

            $user->save();
        });
    }

    /**
     * Allows the admin to incarnate as any user
     *
     * @param int $user_id
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function incarnate($user_id = 0)
    {
        if (Auth::user()->getId() != 1)
        {
            throw new \Exception('Access denied');
        }

        $user = User::find($user_id);

        if (empty ($user))
        {
            throw new \Exception('User does not exist');
        }

        Auth::login($user);

        return Redirect::route('home')->with('flash_notice', 'You are now «' . $user->getNick() . '».');
    }
}
<?php

class PaymentController extends BaseController
{
    // https://github.com/paymill/paymill-paybutton-examples
    // https://www.paymill.com/en-gb/documentation-3/reference/testing/

    const VAT = 0.25;

    public function payForm()
    {
        $gameId = Input::get('game-id');
        $gamePartyId = Input::get('game-party-id');
        $ticketTemplateId = Input::get('ticket-template-id');
        $factor = Input::get('factor');

        // check game existence and bookability
        $game = Game::find($gameId);
        $gameParty = GameParty::find($gamePartyId);
        $ticketTemplate = TicketTemplate::find($ticketTemplateId);

        if (empty ($game))
        {
            throw new \Exception('Game does not exist');
        }
        if (empty ($gameParty))
        {
            throw new \Exception('Game party does not exist');
        }
        if (empty ($ticketTemplate))
        {
            throw new \Exception('Ticket template does not exist');
        }

        // check that the ticket for this game is not yet booked
        $ticket = DB::table('ticket AS t')
            ->select(array('t.id'))
            ->join('ticket_template AS tt', 'tt.id', '=', 't.ticket_template_id')
            ->where('tt.game_id', '=', $gameId)
            ->where('t.user_id', '=', Auth::user()->getId())
            ->first();

        if (!empty ($ticket))
        {
            throw new \Exception('Ticket is already booked');
        }

        // check that this ticket template belongs to this game party
        if ($ticketTemplate->getGameId() != $gameId)
        {
            throw new \Exception('This ticket is not from this game');
        }

        if ($ticketTemplate->getGamePartyId() != 0 && $ticketTemplate->getGamePartyId() != $gamePartyId)
        {
            throw new \Exception('This ticket is not from this game party');
        }

        $ticketTemplate->price_readable = number_format($ticketTemplate->price / 100, 2);

        Session::put('ticket-data', array
        (
            'factor'                => $factor,
            'game_id'               => $gameId,
            'game_party_id'         => $gamePartyId,
            'ticket_template_id'    => $ticketTemplateId,
            'price'                 => $ticketTemplate->getPrice(),
            'payment_description'   => 'Ticket for game «' . $game->getName() . '»',
            'game_name'             => $game->getName(),
        ));

        return View::make('payment.pay', array
        (
            'game'                  => $game,
            'game_party'            => $gameParty,
            'ticket_template'       => $ticketTemplate,
            'price_readable_total'  => number_format($factor * $ticketTemplate->price / 100, 2),
            'price_factor'          => $factor,
        ));
    }

    /**
     * Processes payment and shows a message after the game ticket is booked (and/or paid)
     *
     * @return \Illuminate\View\View
     * @throws Exception
     */
    public function bookingDoneForm()
    {
        $ticketSessionData = Session::get('ticket-data');

        // remove ticket data from the session
        Session::forget('ticket-data');

        $paymentId = 0;

        // get charging scheme of the game owner
        $game = Game::find($ticketSessionData['game_id']);
        $owner = User::find($game->getOwnerId());
        $settings = $owner->getSettingsArray();

        if (isset ($settings['charges']))
        {
            $ticketCoeffA = $settings['charges']['ticket_a'];
            $ticketCoeffB = $settings['charges']['ticket_b'];
        }
        else
        {
            $ticketCoeffA = 0;           // percents
            $ticketCoeffB = 300;         // monetary units
        }

        // depends on PP only
        $transactionCoeffA  = 2.95;      // percents
        $transactionCoeffB  = 0;         // monetary units, use with care due to currency exchange conversions

        // create a real ticket
        $ticket = new Ticket;

        if (!Input::get('is-cash'))
        {
            // process payment provider
            if (!$token = Input::get('paymillToken'))
            {
                throw new \Exception('This ticket is not from this game party');
            }

            $provider = new Paymill(Config::get('app.paymill.private_key'));
            $client = $provider->request('clients/', array());

            $payment = $provider->request
            (
                'payments/',
                array
                (
                    'token'  => $token,
                    'client' => $client['id'],
                )
            );

            $transaction = $provider->request
            (
                'transactions/',
                array
                (
                    'amount'      => $ticketSessionData['price'],
                    'currency'    => 'SEK',
                    'client'      => $client['id'],
                    'payment'     => $payment['id'],
                    'description' => $ticketSessionData['payment_description'],
                )
            );

            $isStatusClosed = isset ($transaction['status']) && $transaction['status'] == 'closed';
            $isResponseCodeSuccess = isset ($transaction['response_code']) && $transaction['response_code'] == 20000;

            if (!$isStatusClosed || !$isResponseCodeSuccess)
            {
                return View::make('payment.failed', array
                (
                    'response_text' => $transaction['data']['response_code'],
                    'game_id'       => $ticketSessionData['game_id'],
                ));
            }
            else
            {
                // create a payment
                $payment = new Payment;
                $payment->setTransactionId($transaction['id']);
                $payment->setAmount($transaction['amount']);
                $payment->setUserId(Auth::user()->getId());
                $payment->setStatus(Payment::STATUS_COMPLETED);
                $payment->setProviderId(2);     // 1 - bank transfer
                $payment->save();

                $paymentId = $payment->getId();

                $bruttoIncome = $payment->getAmount();
                $ppIncome = $bruttoIncome * $transactionCoeffA / 100 + $transactionCoeffB;
                $myIncome = $bruttoIncome * $ticketCoeffA / 100 + $ticketCoeffB;
                $nettoIncome = $bruttoIncome - $ppIncome - $myIncome * (1 + self::VAT);
            }

            $ticket->setStatus(Ticket::STATUS_BOOKED | Ticket::STATUS_PAID);
        }
        else
        {
            // that's cash, it has no transaction charging
            $bruttoIncome = $ticketSessionData['price'];
            $myIncome = $bruttoIncome * $ticketCoeffA / 100 + $ticketCoeffB;
            $nettoIncome = $bruttoIncome - $myIncome * (1 + self::VAT);

            $ticket->setStatus(Ticket::STATUS_BOOKED);
        }

        $vatPaid = $myIncome * self::VAT;

        $ticket->setUserId(Auth::user()->getId());
        $ticket->setGamePartyId($ticketSessionData['game_party_id']);
        $ticket->setTicketTemplateId($ticketSessionData['ticket_template_id']);
        $ticket->setPaymentId($paymentId);
        $ticket->setNetto($nettoIncome);                    // amount that Organizer gets
        $ticket->setBrutto($bruttoIncome);                  // amount that Player pays
        $ticket->setVat($vatPaid);                          // increment of my outgoing MOMS per this ticket
        $ticket->save();

        // inform user by email
        if (Config::get('mail.mandrill_on'))
        {
            $email = Auth::user()->getEmail();
            $name = strlen(Auth::user()->getNick()) ? Auth::user()->getNick() : $email;

            Mandrill::request('messages/send-template', array
            (
                'template_name'     => 'ticket-created',
                'template_content'  => array(),
                'acync'             => true,
                'message' => array
                (
                    'subject'       => 'Ticket for «' . $ticketSessionData['game_name'] . '»',
                    'from_email'    => Config::get('app.emails.noreply'),
                    'from_name'     => Config::get('app.company.name'),
                    'to' => array
                    (
                        array
                        (
                            'email' => $email,
                            'name'  => $name,
                        ),
                    ),
                    'global_merge_vars' => array
                    (
                        array
                        (
                            'name'      => 'nick',
                            'content'   => $name,
                        ),
                        array
                        (
                            'name'      => 'game_name',
                            'content'   => $ticketSessionData['game_name'],
                        ),
                        array
                        (
                            'name'      => 'ticket_url',
                            'content'   => URL::route('game-briefing', array($ticketSessionData['game_id'])),
                        ),
                    ),
                )
            ));
        }


        return View::make('payment.done', array
        (
            'game_id' => $ticketSessionData['game_id'],
        ));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function bankTransferForm()
    {
        $ticketSessionData = Session::get('ticket-data');

        // remove ticket data from the session
        Session::forget('ticket-data');

        $paymentId = 0;

        // get charging scheme of the game owner
        $game = Game::find($ticketSessionData['game_id']);
        $owner = User::find($game->getOwnerId());
        $settings = $owner->getSettingsArray();

        if (isset ($settings['charges']))
        {
            $ticketCoeffA = $settings['charges']['ticket_a'];
            $ticketCoeffB = $settings['charges']['ticket_b'];
        }
        else
        {
            $ticketCoeffA = 0;              // percents
            $ticketCoeffB = 0; //300;       // monetary units
        }

        $bruttoIncome = $ticketSessionData['price'];
        $myIncome = $bruttoIncome * $ticketCoeffA / 100 + $ticketCoeffB;
        $nettoIncome = $bruttoIncome - $myIncome * (1 + self::VAT);

        $vatPaid = $myIncome * self::VAT;

        $firstTicket = null;

        // create real tickets (factored amount)
        for ($i = 0; $i < $ticketSessionData['factor']; $i++)
        {
            $ticket = new Ticket;
            $ticket->setUserId(Auth::user()->getId());
            $ticket->setGamePartyId($ticketSessionData['game_party_id']);
            $ticket->setTicketTemplateId($ticketSessionData['ticket_template_id']);
            $ticket->setPaymentId($paymentId);
            $ticket->setStatus(Ticket::STATUS_BOOKED);
            $ticket->setNetto($nettoIncome);                    // amount that Organizer gets
            $ticket->setBrutto($bruttoIncome);                  // amount that Player pays
            $ticket->setVat($vatPaid);                          // increment of my outgoing MOMS per this ticket
            $ticket->save();

            // save for future reference
            if (!$firstTicket)
            {
                $firstTicket = $ticket;
            }
        }

        // avoid everyday job with activation

        return View::make('payment.bank', array
        (
            'game_id'       => $ticketSessionData['game_id'],
            'price'         => number_format($ticketSessionData['factor'] * $ticketSessionData['price'] / 100, 2),
            'ticket_code'   => strtoupper(Bit::base36_encode(Bit::swap15($firstTicket->getId()))),
        ));
    }
}
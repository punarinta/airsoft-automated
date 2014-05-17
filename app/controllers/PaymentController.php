<?php

class PaymentController extends BaseController
{
    // https://github.com/paymill/paymill-paybutton-examples
    // https://www.paymill.com/en-gb/documentation-3/reference/testing/

    public function payForm()
    {
        $gameId = Input::get('game-id');
        $gamePartyId = Input::get('game-party-id');
        $ticketTemplateId = Input::get('ticket-template-id');

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
            'game_id'               => $gameId,
            'game_party_id'         => $gamePartyId,
            'ticket_template_id'    => $ticketTemplateId,
            'price'                 => $ticketTemplate->getPrice(),
            'payment_description'   => 'Ticket for game «' . $game->getName() . '»',
        ));

        return View::make('payment.pay', array
        (
            'game'              => $game,
            'game_party'        => $gameParty,
            'ticket_template'   => $ticketTemplate,
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
                $payment->setProviderId(1);
                $payment->save();

                $paymentId = $payment->getId();
            }
        }

        // create a real ticket
        $ticket = new Ticket;
        $ticket->setUserId(Auth::user()->getId());
        $ticket->setGamePartyId($ticketSessionData['game_party_id']);
        $ticket->setTicketTemplateId($ticketSessionData['ticket_template_id']);
        $ticket->setPaymentId($paymentId);
        $ticket->save();

        return View::make('payment.done', array());
    }
}
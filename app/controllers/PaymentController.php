<?php

class PaymentController extends BaseController
{
    // https://github.com/paymill/paymill-paybutton-examples

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
            'game_party_id'         => $gamePartyId,
            'ticket_template_id'    => $ticketTemplateId,
        ));

        return View::make('payment.pay', array
        (
            'game'              => $game,
            'game_party'        => $gameParty,
            'ticket_template'   => $ticketTemplate,
        ));
    }
}
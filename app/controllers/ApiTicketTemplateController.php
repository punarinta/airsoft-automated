<?php

class ApiTicketTemplateController extends BaseController
{
    public function index()
    {
        return $this->execute(function()
        {
            $ticketTemplatesData = [];
            $ticketTemplates = TicketTemplate::all();

            foreach ($ticketTemplates as $ticketTemplate)
            {
                $ticketTemplatesData[] = $ticketTemplate->toArray();
            }

            return $ticketTemplatesData;
        });
    }

    public function show($ticket_template_id = 0)
    {
        return $this->execute(function() use ($ticket_template_id)
        {
            return TicketTemplate::find($ticket_template_id)->toArray();
        });
    }

    /**
     * Creates a Ticket Template
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        return $this->execute(function()
        {
            $ticketTemplate = new TicketTemplate;
            $ticketTemplate->setGameId(Input::json('game_id'));
            $ticketTemplate->setGamePartyId(Input::json('game_party_id'));
            $ticketTemplate->setPrice(Input::json('price') * 100);
            $ticketTemplate->setPriceDateStart(Input::json('price_date_start'));
            $ticketTemplate->setPriceDateEnd(Input::json('price_date_end'));
            $ticketTemplate->save();

            return $ticketTemplate->toArray();
        });
    }

    public function update($ticket_template_id = 0)
    {
        return $this->execute(function() use ($ticket_template_id)
        {
            $ticketTemplate = TicketTemplate::find($ticket_template_id);

            // check permissions
            $game = Game::find($ticketTemplate->getGameId());
            if (Auth::user()->getId() != $game->getOwnerId())
            {
                throw new \Exception('Access denied.');
            }

            $ticketTemplate->setGameId(Input::json('game_id'));
            $ticketTemplate->setGamePartyId(Input::json('game_party_id'));
            $ticketTemplate->setPrice(Input::json('price') * 100);
            $ticketTemplate->setPriceDateStart(Input::json('price_date_start'));
            $ticketTemplate->setPriceDateEnd(Input::json('price_date_end'));
            $ticketTemplate->save();
        });
    }

    public function destroy($ticket_template_id = 0)
    {
        return $this->execute(function() use ($ticket_template_id)
        {
            $ticketTemplate = TicketTemplate::find($ticket_template_id);

            // check permissions
            $game = Game::find($ticketTemplate->getGameId());
            if (Auth::user()->getId() != $game->getOwnerId())
            {
                throw new \Exception('Access denied.');
            }

            // check that there are no tickets fot this TT
            $tickets = Ticket::where('ticket_template_id', '=', $ticketTemplate->getId())->get()->toArray();

            if (!empty ($tickets))
            {
                throw new \Exception('Tickets of this type are already booked.<br>Try to edit the info instead.');
            }

            $ticketTemplate->delete();
        });
    }
}
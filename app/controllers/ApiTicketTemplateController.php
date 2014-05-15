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

    public function store()
    {
        return $this->execute(function()
        {
            $ticketTemplate = new TicketTemplate;
            $ticketTemplate->setGameId(Input::json('game_id'));
            $ticketTemplate->setGamePartyId(Input::json('game_party_id'));
            $ticketTemplate->setPrice(Input::json('price'));
            $ticketTemplate->setPriceDateStart(Input::json('price_date_start'));
            $ticketTemplate->setPriceDateEnd(Input::json('price_date_end'));
            $ticketTemplate->save();
        });
    }

    public function update($ticket_template_id = 0)
    {
        return $this->execute(function() use ($ticket_template_id)
        {
            $ticketTemplate = TicketTemplate::find($ticket_template_id);
            $ticketTemplate->setGameId(Input::json('game_id'));
            $ticketTemplate->setGamePartyId(Input::json('game_party_id'));
            $ticketTemplate->setPrice(Input::json('price'));
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
            $ticketTemplate->delete();
        });
    }
}
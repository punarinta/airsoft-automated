<?php

class ApiTicketController extends BaseController
{
    /**
     * Validates a ticket by its barcode
     *
     * @param int $barcode
     * @return \Illuminate\Http\JsonResponse
     */
    public function validate($barcode = 0)
    {
        return $this->execute(function() use ($barcode)
        {
            $ticket = Ticket::find(Bit::swap15(Bit::base36_decode(trim($barcode))));

            return array
            (
                'id' => $ticket->getId(),
                'exists' => !empty ($ticket),
            );
        });
    }

    /**
     * Checks-in a ticket by its barcode
     *
     * @param int $barcode
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkIn($barcode = 0)
    {
        return $this->execute(function() use ($barcode)
        {
            $ticket = Ticket::find(Bit::swap15(Bit::base36_decode(trim($barcode))));

            if (empty ($ticket))
            {
                throw new \Exception('Ticket not found');
            }

            $ticket->setStatus($ticket->getStatus() | Ticket::STATUS_CHECKED);
            $ticket->save();

            return array
            (
                'id' => $ticket->getId(),
            );
        });
    }
}
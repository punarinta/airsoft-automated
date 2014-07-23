<?php

class Ticket extends Eloquent
{
    const STATUS_NEW        = 1;
    const STATUS_BOOKED     = 2;
    const STATUS_PAID       = 4;
    const STATUS_CHECKED    = 8;

    protected $table = 'ticket';

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param string $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return string
     */
    public function getPaymentId()
    {
        return $this->payment_id;
    }

    /**
     * @param string $payment_id
     */
    public function setPaymentId($payment_id)
    {
        $this->payment_id = $payment_id;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdateAt()
    {
        return $this->update_at;
    }

    /**
     * @param mixed $update_at
     */
    public function setUpdateAt($update_at)
    {
        $this->update_at = $update_at;
    }

    /**
     * @return string
     */
    public function getTicketTemplateId()
    {
        return $this->ticket_template_id;
    }

    /**
     * @param string $ticket_template_id
     */
    public function setTicketTemplateId($ticket_template_id)
    {
        $this->ticket_template_id = $ticket_template_id;
    }

    /**
     * @return string
     */
    public function getGamePartyId()
    {
        return $this->game_party_id;
    }

    /**
     * @param string $game_party_id
     */
    public function setGamePartyId($game_party_id)
    {
        $this->game_party_id = $game_party_id;
    }

    /**
     * @return string
     */
    public function getNetto()
    {
        return $this->netto;
    }

    /**
     * @param string $netto
     */
    public function setNetto($netto)
    {
        $this->netto = $netto;
    }

    /**
     * @return string
     */
    public function getBrutto()
    {
        return $this->brutto;
    }

    /**
     * @param string $brutto
     */
    public function setBrutto($brutto)
    {
        $this->brutto = $brutto;
    }

    /**
     * @return string
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @param string $vat
     */
    public function setVat($vat)
    {
        $this->vat = $vat;
    }

    /**
     * @return string
     */
    public function getHostTicketId()
    {
        return $this->host_ticket_id;
    }

    /**
     * @param string $host_ticket_id
     */
    public function setHostTicketId($host_ticket_id)
    {
        $this->host_ticket_id = $host_ticket_id;
    }
}

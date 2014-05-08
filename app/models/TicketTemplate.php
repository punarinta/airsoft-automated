<?php

class TicketTemplate extends Eloquent
{
    protected $table = 'ticket_template';

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
    public function getGameId()
    {
        return $this->game_id;
    }

    /**
     * @param string $game_id
     */
    public function setGameId($game_id)
    {
        $this->game_id = $game_id;
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
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param string $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getPriceDateStart()
    {
        return $this->price_date_start;
    }

    /**
     * @param string $price_date_start
     */
    public function setPriceDateStart($price_date_start)
    {
        $this->price_date_start = $price_date_start;
    }

    /**
     * @return string
     */
    public function getPriceDateEnd()
    {
        return $this->price_date_end;
    }

    /**
     * @param string $price_date_end
     */
    public function setPriceDateEnd($price_date_end)
    {
        $this->price_date_end = $price_date_end;
    }

    /**
     * @return string
     */
    public function getInCash()
    {
        return $this->in_cash;
    }

    /**
     * @param string $in_cash
     */
    public function setInCash($in_cash)
    {
        $this->in_cash = $in_cash;
    }

    /**
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param string $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
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
}

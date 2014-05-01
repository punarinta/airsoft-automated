<?php

namespace AiCore\Entity;

class Striker extends Generic
{
    protected $id = 0;
    protected $nick = '';
    protected $name = '';
    protected $team_id = 0;
    protected $team_status = 0;
    protected $weapon_ids = '';
    protected $known_as = '';
    protected $city_id = 0;
    protected $has_photo = 0;
    protected $contacts = '';

    /**
     * @param int $city_id
     */
    public function setCityId($city_id)
    {
        $this->city_id = $city_id;
    }

    /**
     * @return int
     */
    public function getCityId()
    {
        return $this->city_id;
    }

    /**
     * @param string $contacts
     */
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;
    }

    /**
     * @return string
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * @param int $has_photo
     */
    public function setHasPhoto($has_photo)
    {
        $this->has_photo = $has_photo;
    }

    /**
     * @return int
     */
    public function getHasPhoto()
    {
        return $this->has_photo;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $known_as
     */
    public function setKnownAs($known_as)
    {
        $this->known_as = $known_as;
    }

    /**
     * @return string
     */
    public function getKnownAs()
    {
        return $this->known_as;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $nick
     */
    public function setNick($nick)
    {
        $this->nick = $nick;
    }

    /**
     * @return string
     */
    public function getNick()
    {
        return $this->nick;
    }

    /**
     * @param int $team_id
     */
    public function setTeamId($team_id)
    {
        $this->team_id = $team_id;
    }

    /**
     * @return int
     */
    public function getTeamId()
    {
        return $this->team_id;
    }

    /**
     * @param int $team_status
     */
    public function setTeamStatus($team_status)
    {
        $this->team_status = $team_status;
    }

    /**
     * @return int
     */
    public function getTeamStatus()
    {
        return $this->team_status;
    }

    /**
     * @param string $weapon_ids
     */
    public function setWeaponIds($weapon_ids)
    {
        $this->weapon_ids = $weapon_ids;
    }

    /**
     * @return string
     */
    public function getWeaponIds()
    {
        return $this->weapon_ids;
    }

    /**
     * @param array $weapon_ids_array
     * @throws \Exception
     */
    public function setWeaponIdsArray($weapon_ids_array = array())
    {
        if (is_array($weapon_ids_array))
        {
            $this->weapon_ids = implode(',', $weapon_ids_array);
        }
        else
        {
            throw new \Exception('Striker.setWeaponIdsArray() requires an array');
        }
    }

    /**
     * @return string
     */
    public function getWeaponIdsArray()
    {
        return explode(',', $this->weapon_ids);
    }
}

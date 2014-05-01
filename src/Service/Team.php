<?php

namespace AiCore\Service;

class Team extends Generic
{
    /**
     * Overwritten
     *
     * @param $data
     * @return mixed|void
     */
    public function create($data)
    {
        $data['created'] = date('Y-m-d H:i:s');
        return parent::create($data);
    }

    /**
     * Returns a team by its owner ID (owner is User)
     *
     * @param $userId
     * @return mixed
     */
    public function findByUserId($userId)
    {
        $arrays = $this->entitize($this->dbSelect('AS t LEFT JOIN user AS u ON u.team_id = t.id WHERE u.id = ' . (int) $userId . ' LIMIT 1'));
        return isset ($arrays[0]) ? $this->entitize($arrays[0]) : null;
    }

    /**
     * Returns a team by a striker ID
     *
     * @param $strikerId
     * @return mixed
     */
    public function findByStrikerId($strikerId)
    {
        $arrays = $this->entitize($this->dbSelect('AS t LEFT JOIN striker AS s ON s.team_id = t.id WHERE s.id = ' . (int) $strikerId . ' LIMIT 1'));
        return isset ($arrays[0]) ? $this->entitize($arrays[0]) : null;
    }

    /**
     * Returns all the teams in a Region
     *
     * @param $regionId
     * @return array
     */
    public function findByRegionId($regionId)
    {
        return $this->entitizeArray($this->dbSelect('WHERE region_id = ' . (int) $regionId));
    }

    /**
     * Returns all the teams in a Country
     *
     * @param $countryId
     * @return array
     */
    public function findByCountryId($countryId)
    {
        return $this->entitizeArray($this->dbSelect('AS t LEFT JOIN region AS r ON t.region_id = r.id WHERE r.country_id = ' . (int) $countryId));
    }
}

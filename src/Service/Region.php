<?php

namespace AiCore\Service;

class Region extends Generic
{
    /**
     * Returns all the regions in a Country
     *
     * @param $countryId
     * @return array
     */
    public function findByCountryId($countryId)
    {
        return $this->entitizeArray($this->dbSelect('WHERE country_id = ' . (int) $countryId));
    }

}

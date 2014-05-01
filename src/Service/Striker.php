<?php

namespace AiCore\Service;

class Striker extends Generic
{
    /**
     * Returns all the strikers from the team
     *
     * @param $teamId
     * @return array
     */
    public function findByTeamId($teamId)
    {
        return $this->entitizeArray($this->dbSelect('WHERE team_id = ' . (int) $teamId));
    }

    /**
     * Performs a search by a nickname or its part
     *
     * @param $nick
     * @return array
     */
    public function findByNick($nick)
    {
        // sanitize a bit
        $nick = str_replace("'", '"', $nick);

        // search
        return $this->entitizeArray($this->dbSelect('WHERE nick LIKE \'' . $nick . '\''));
    }

    /**
     * Return N random strikers
     *
     * @param int $count
     * @return array
     */
    public function findRandom($count = 40)
    {
        return $this->entitizeArray($this->dbSelect('AS r1 JOIN (SELECT (RAND() * (SELECT MAX(id) FROM striker)) AS id) AS r2 WHERE r1.id >= r2.id ORDER BY r1.id ASC LIMIT ' . (int) $count));
    }

    /**
     * Returns a relative URL to the striker photo
     *
     * @param $strikerId
     * @param int $formFactor
     * @return null|string
     */
    public function getPhotoUrlByStrikerId($strikerId, $formFactor = 0)
    {
        if ($striker = $this->find($strikerId))
        {
            if ($striker->hasPhoto())
            {
                return $this->getConfig('urls/faces' . (($formFactor) ? '_small' : '')) . $formFactor . '/' . (int) $striker->getId() . '.jpg';
            }
        }

        return null;
    }
}

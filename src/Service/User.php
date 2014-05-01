<?php

namespace AiCore\Service;

use AiCore\Loader;
use AiCore\Transport\Email;
use AiCore\Transport\Sms;

class User extends Generic
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
     * Returns a user by the team that he governs
     *
     * @param $teamId
     * @return mixed
     */
    public function findByTeamId($teamId)
    {
        $arrays = $this->entitize($this->dbSelect('WHERE team_id = ' . (int) $teamId . ' LIMIT 1'));
        return isset ($arrays[0]) ? $this->entitize($arrays[0]) : null;
    }

    /**
     * Returns a user by a striker associated with him
     *
     * @param $strikerId
     * @return mixed
     */
    public function findByStrikerId($strikerId)
    {
        $arrays = $this->entitize($this->dbSelect('WHERE striker_id = ' . (int) $strikerId . ' LIMIT 1'));
        return isset ($arrays[0]) ? $this->entitize($arrays[0]) : null;
    }

    /**
     * Returns users that are bound to the same team via their strikers
     *
     * @param $userId
     * @return array
     */
    public function findTeammatesByUserId($userId)
    {
        $result = array();

        // get team ID (via striker!)
        $arrays = $this->entitize($this->db->sql('SELECT s.team_id FROM striker AS s LEFT_JOIN user AS u ON s.id = u.striker_id WHERE u.id = ' . (int) $userId . ' LIMIT 1'));

        if (!isset ($arrays[0]))
        {
            // no striker associated, it happens
            return $result;
        }
        else
        {
            $teamId = $arrays[0];
        }

        // fetch users (via strikers!)
        return $this->entitizeArray($this->dbSelect('AS u LEFT_JOIN striker AS s ON u.striker_id = s.id WHERE s.team_id = ' . (int) $teamId));
    }

    /*
     *  Functions not returning a User follow below
     */

    /**
     * Notify a user via a transport channel
     *
     * @param $userId
     * @param $transportType
     * @param $message
     * @param null $header
     * @return bool|void
     */
    public function notifyByUserId($userId, $transportType, $message, $header = null)
    {
        $user = $this->find($userId);

        if ($transportType == 'email')
        {
            Loader::load('Transport\Email');
            $transport = new Email($user->getEmail());
        }

        if ($transportType == 'sms')
        {
            Loader::load('Transport\Sms');
            $transport = new Sms($user->getPhone());
        }

        if ($transportType == 'pm')
        {
            // TODO: notify user via PM in a social network
        }

        if ($transportType == 'wall')
        {
            // TODO: notify user via Wall in a social network
        }

        if (isset ($transport))
        {
            return $transport->deliver($message, $header);
        }

        return false;
    }

    /**
     * Returns a relative URL to the user avatar (i.e. striker photo)
     *
     * @param $userId
     * @param int $formFactor
     * @return null|string
     */
    public function getAvatarUrlByUserId($userId, $formFactor = 0)
    {
        // fetch striker data
        $arrays = $this->entitize($this->db->sql('SELECT s.has_photo, s.id FROM striker AS s LEFT JOIN user AS u ON s.id = u.striker_id WHERE u.id = ' . (int) $userId . ' LIMIT 1'));

        if (isset ($arrays[0]) && $arrays[0][0])
        {
            // here striker photo is present

            return $this->getConfig('urls/faces' . (($formFactor) ? '_small' : '')) . $formFactor . '/' . $arrays[0][1] . '.jpg';
        }
        else
        {
            return null;
        }
    }
}

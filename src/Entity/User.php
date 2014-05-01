<?php

namespace AiCore\Entity;

class User extends Generic
{
    const ROLE_USER  = 0b001;
    const ROLE_ADMIN = 0b010;

    protected $id = 0;
    protected $striker_id = 0;
    protected $team_id = 0;
    protected $username = '';
    protected $username_clean = '';
    protected $password = '';
    protected $email = '';
    protected $created = 0;
    protected $last_login = 0;
    protected $role = 0;
    protected $money = 0;
    protected $phone = '';
    protected $real_name = '';

    // social
    protected $status = '';
    protected $follows = '';
    protected $settings = 0;
    protected $pm_count = 0;

    /**
     * @param int $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return int
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $follows
     */
    public function setFollows($follows)
    {
        $this->follows = $follows;
    }

    /**
     * @return string
     */
    public function getFollows()
    {
        return $this->follows;
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
     * @param int $last_login
     */
    public function setLastLogin($last_login)
    {
        $this->last_login = $last_login;
    }

    /**
     * @return int
     */
    public function getLastLogin()
    {
        return $this->last_login;
    }

    /**
     * @param int $money
     */
    public function setMoney($money)
    {
        $this->money = $money;
    }

    /**
     * @return int
     */
    public function getMoney()
    {
        return $this->money;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param int $pm_count
     */
    public function setPmCount($pm_count)
    {
        $this->pm_count = $pm_count;
    }

    /**
     * @return int
     */
    public function getPmCount()
    {
        return $this->pm_count;
    }

    /**
     * @param string $real_name
     */
    public function setRealName($real_name)
    {
        $this->real_name = $real_name;
    }

    /**
     * @return string
     */
    public function getRealName()
    {
        return $this->real_name;
    }

    /**
     * @param int $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return int
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param int $settings
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
    }

    /**
     * @return int
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $striker_id
     */
    public function setStrikerId($striker_id)
    {
        $this->striker_id = $striker_id;
    }

    /**
     * @return int
     */
    public function getStrikerId()
    {
        return $this->striker_id;
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
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username_clean
     */
    public function setUsernameClean($username_clean)
    {
        $this->username_clean = $username_clean;
    }

    /**
     * @return string
     */
    public function getUsernameClean()
    {
        return $this->username_clean;
    }
}

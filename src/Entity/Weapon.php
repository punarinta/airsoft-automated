<?php

namespace AiCore\Entity;

class Weapon extends Generic
{
    const WEAPON_HANDGUN    = 0b0000001;
    const WEAPON_SMG        = 0b0000010;
    const WEAPON_MG         = 0b0000100;
    const WEAPON_RIFLE      = 0b0001000;
    const WEAPON_MARKSMAN   = 0b0010000;
    const WEAPON_BOLT       = 0b0100000;

    protected $id = 0;
    protected $name = '';
    protected $type = 0;

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
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }
}

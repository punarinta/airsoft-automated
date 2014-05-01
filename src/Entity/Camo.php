<?php

namespace AiCore\Entity;

class Camo extends Generic
{
    protected $id = 0;
    protected $name = 0;
    protected $known_as = 0;
    protected $description = 0;

    /**
     * @param int $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getDescription()
    {
        return $this->description;
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
     * @param int $known_as
     */
    public function setKnownAs($known_as)
    {
        $this->known_as = $known_as;
    }

    /**
     * @return int
     */
    public function getKnownAs()
    {
        return $this->known_as;
    }

    /**
     * @param int $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getName()
    {
        return $this->name;
    }
}

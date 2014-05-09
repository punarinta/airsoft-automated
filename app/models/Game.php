<?php

class Game extends Eloquent
{
    protected $table = 'game';

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
    public function getName()
    {
        return $this->name;
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
    public function getOwnerId()
    {
        return $this->owner_id;
    }

    /**
     * @param string $owner_id
     */
    public function setOwnerId($owner_id)
    {
        $this->owner_id = $owner_id;
    }

    /**
     * @return string
     */
    public function getRegionId()
    {
        return $this->region_id;
    }

    /**
     * @param string $region_id
     */
    public function setRegionId($region_id)
    {
        $this->region_id = $region_id;
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
    public function getStartsAt()
    {
        return $this->starts_at;
    }

    /**
     * @param string $starts_at
     */
    public function setStartsAt($starts_at)
    {
        $this->starts_at = $starts_at;
    }

    /**
     * @return string
     */
    public function getEndsAt()
    {
        return $this->ends_at;
    }

    /**
     * @param string $ends_at
     */
    public function setEndsAt($ends_at)
    {
        $this->ends_at = $ends_at;
    }

    /**
     * @return string
     */
    public function getIsVisible()
    {
        return $this->is_visible;
    }

    /**
     * @param string $is_visible
     */
    public function setIsVisible($is_visible)
    {
        $this->is_visible = $is_visible;
    }
}

<?php

namespace AiCore\Entity;

class Team extends Generic
{
    protected $id = 0;
    protected $name = '';
    protected $full_name = '';
    protected $union_id = 0;
    protected $staff_count = 0;
    protected $recruit_count = 0;
    protected $created = 0;
    protected $weapon_ids = '';
    protected $model = '';
    protected $leader_nick = '';
    protected $url = '';
    protected $emblem = '';
    protected $region_id = 0;
    protected $camo_ids = '';
    protected $rating = 0;
    protected $recruiting = 0;

    /**
     * @param string $camo_ids
     */
    public function setCamoIds($camo_ids)
    {
        $this->camo_ids = $camo_ids;
    }

    /**
     * @return string
     */
    public function getCamoIds()
    {
        return $this->camo_ids;
    }

    /**
     * @param array $camo_ids_array
     * @throws \Exception
     */
    public function setCamoIdsArray($camo_ids_array = array())
    {
        if (is_array($camo_ids_array))
        {
            $this->camo_ids = implode(',', $camo_ids_array);
        }
        else
        {
            throw new \Exception('Striker.setWeaponIdsArray() requires an array');
        }
    }

    /**
     * @return string
     */
    public function getCamoIdsArray()
    {
        return explode(',', $this->camo_ids);
    }

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
     * @param string $emblem
     */
    public function setEmblem($emblem)
    {
        $this->emblem = $emblem;
    }

    /**
     * @return string
     */
    public function getEmblem()
    {
        return $this->emblem;
    }

    /**
     * @param string $full_name
     */
    public function setFullName($full_name)
    {
        $this->full_name = $full_name;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->full_name;
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
     * @param string $leader_nick
     */
    public function setLeaderNick($leader_nick)
    {
        $this->leader_nick = $leader_nick;
    }

    /**
     * @return string
     */
    public function getLeaderNick()
    {
        return $this->leader_nick;
    }

    /**
     * @param string $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
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
     * @param int $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param int $recruiting
     */
    public function setRecruiting($recruiting)
    {
        $this->recruiting = $recruiting;
    }

    /**
     * @return int
     */
    public function getRecruiting()
    {
        return $this->recruiting;
    }

    /**
     * @param int $union_id
     */
    public function setUnionId($union_id)
    {
        $this->union_id = $union_id;
    }

    /**
     * @return int
     */
    public function getUnionId()
    {
        return $this->union_id;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
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

    /**
     * @return int
     */
    public function getStaffCount()
    {
        return $this->staff_count;
    }

    /**
     * @param int $staff_count
     */
    public function setStaffCount($staff_count)
    {
        $this->staff_count = $staff_count;
    }

    /**
     * @return int
     */
    public function getRecruitCount()
    {
        return $this->recruit_count;
    }

    /**
     * @param int $recruit_count
     */
    public function setRecruitCount($recruit_count)
    {
        $this->recruit_count = $recruit_count;
    }

    /**
     * @return int
     */
    public function getRegionId()
    {
        return $this->region_id;
    }

    /**
     * @param int $region_id
     */
    public function setRegionId($region_id)
    {
        $this->region_id = $region_id;
    }
}

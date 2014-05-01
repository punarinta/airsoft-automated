<?php

namespace AiCore\Service;

use AiCore\Loader;
use AiCore\Model\String;

class Generic
{
    protected $db;
    protected $hasId = true;
    protected $needsEntity = true;
    protected $ClassName;
    protected $class_name;

    public function __construct()
    {
        Loader::load('Model\String');

        $var = explode("\\", get_class($this));
        $this->ClassName = end($var);
        $this->class_name = String::fromCamelCase($this->ClassName);

        if ($this->needsEntity)
        {
            Loader::load(array
            (
                'Entity\Generic',
                'Entity\\' . $this->ClassName,
            ));
        }
    }

    /**
     * Creates an entity, dumps it to DB (if enabled) and returns it
     *
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        $entity = $this->entitize($data);

        if ($this->db)
        {
            // get the full scheme
            $this->db->dump($entity->toArray(), $this->class_name);
        }

        // memorize ID
        $entity->setId($this->db->getLastId());

        return $entity;
    }

    /**
     * Updates an entity by saving into DB
     *
     * @param $entity
     */
    public function update($entity)
    {
        return $this->db->dump($entity->toArray(), $this->class_name);
    }

    /**
     * Deletes entity. May accept ID instead of the whole object.
     *
     * @param $entityId
     * @return mixed
     */
    public function delete($entityId)
    {
        if (is_object($entityId))
        {
            $entityId = $entityId->getId();
        }

        return $this->db->execute('DELETE FROM ' . $this->class_name . ' WHERE id = ' . (int) $entityId . ' LIMIT 1');
    }

    /**
     * Wrapper for DB selector, substituting class name
     *
     * @param string $sql
     * @return mixed
     */
    public function dbSelect($sql = '')
    {
        return $this->db->select($this->class_name, $sql);
    }

    /**
     * Finds an entity by ID
     *
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        $arrays = $this->db->select($this->class_name, 'WHERE id = ' . (int) $id);
        return isset ($arrays[0]) ? $this->entitize($arrays[0]) : null;
    }

    /**
     * A version findById() that saves an SQL request in case the argument is already an entity
     *
     * @param $incoming
     * @return mixed|string
     */
    public function find($incoming)
    {
        $entityName = '\AiCore\Entity\\' . $this->ClassName;

        if ($incoming instanceof $entityName)
        {
            return $incoming;
        }
        else
        {
            return $this->findById($incoming);
        }
    }

    /**
     * Validates that an incoming is an ID
     *
     * @param $incoming
     * @return mixed|string
     */
    public function findId($incoming)
    {
        $entityName = '\AiCore\Entity\\' . $this->ClassName;

        if ($incoming instanceof $entityName)
        {
            return $incoming->getId();
        }
        else
        {
            return $incoming;
        }
    }

    /**
     * Find all the entities. Use with care.
     *
     * @return mixed
     */
    public function findAll()
    {
        return $this->entitizeArray($this->db->select($this->class_name));
    }

    /**
     * Finds all the entities that correspond to the passed filter.
     *
     * @param $filter
     * @param $logic
     * @return mixed
     * @throws \Exception
     */
    public function findByFilter($filter, $logic = 'AND')
    {
        $logic = strtoupper($logic);
        if (!in_array($logic, array('AND', 'OR')))
        {
            throw new \Exception('Unknown filter logic');
        }

        $sql = array();
        $logic = " $logic ";

        foreach ($filter as $key => $value)
        {
            $sql[] = "$key = '$value'";
        }

        $array = $this->db->select($this->class_name, 'WHERE ' . implode($logic, $sql));
        return $this->entitize($array);
    }



    /*
     *  === Service shit goes below ===
     */

    /**
     * Sets internal DB hook
     *
     * @param $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * Creates a new entity and fills it with array if necessary
     *
     * @param null $array
     * @return mixed
     */
    protected function entitize($array = null)
    {
        // create object
        $entityClassName = 'AiCore\Entity\\' . $this->ClassName;
        $entity = new $entityClassName;

        if ($array)
        {
            $entity->fromArray($array);
        }
        return $entity;
    }

    /**
     * entitize() an array
     *
     * @param array $arrays
     * @return array
     */
    protected function entitizeArray($arrays = null)
    {
        $result = array();

        if (!$arrays)
        {
            return $result;
        }

        foreach ($arrays as $array)
        {
            $result[] = $this->entitize($array);
        }

        return $result;
    }

    /**
     * Returns a service via the last initialized AiCore (normally there should be only one core)
     *
     * @param $serviceName
     * @return mixed
     */
    public function getService($serviceName)
    {
        global $globalCore;
        return $globalCore->getService($serviceName);
    }

    /**
     * Returns a config via the last initialized AiCore (normally there should be only one core)
     *
     * @param string $varPath
     * @param bool $skipNulls
     * @param null $root
     * @return mixed
     */
    public function getConfig($varPath = '', $skipNulls = true, $root = null)
    {
        global $globalCore;
        return $globalCore->getConfig($varPath, $skipNulls, $root);
    }
}

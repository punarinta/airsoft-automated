<?php

namespace AiCore\Model;

class Db
{
    /**
     * @var \PDO
     */
    protected $link;

    /**
     * Constructor. Establishes connection with the DB.
     */
    public function __construct($config)
    {
        try
        {
            $this->link = new \PDO($config['dsn'], $config['user'], $config['password']);
        }
        catch (\PDOException $e)
        {
            throw new \Exception('Cannot connect to DB');
        }
    }

    /**
     * Simply executes SQL and return a PDOStatement
     *
     * @param $query
     * @return \PDOStatement
     */
    public function execute($query)
    {
        return $this->link->query($query);
    }

    /**
     * Saves an array to a table
     *
     * @param $array
     * @param $table
     * @return \PDOStatement
     */
    public function dump($array, $table)
    {
        if (isset ($array['id']) && $array['id'] > 0)
        {
            // this is updating

            $sql = '';
            foreach ($array as $key => $value)
            {
                $sql .= "$key = '$value',";
            }

            // cleanup
            $sql = rtrim($sql, ',');
            $id = (int) $array['id'];

            return $this->execute('UPDATE ' . $table . ' SET ' . $sql . ' WHERE id = ' . $id . ' LIMIT 1');
        }
        else
        {
            // this is creation
            $keys = implode(',', array_keys($array));
            $values = implode("','", array_values($array));
            return $this->execute('INSERT INTO ' . $table . ' (' . $keys . ") VALUES ('" . $values . "')");
        }
    }

    /**
     * Simplifies the most frequently used operation
     *
     * @param $table
     * @param $sql
     * @return \PDOStatement
     */
    public function select($table, $sql = '')
    {
        if (!$result = $this->execute('SELECT * FROM ' . $table . ' ' . $sql))
        {
            return null;
        }

        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Combines execute and fetch
     *
     * @param $sql
     * @return \PDOStatement
     */
    public function sql($sql = '')
    {
        $result = $this->execute($sql);
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Gets last insert operation auto-incremented ID.
     * Use with care!
     *
     * @return string
     */
    public function getLastId()
    {
        return $this->link->lastInsertId();
    }
}

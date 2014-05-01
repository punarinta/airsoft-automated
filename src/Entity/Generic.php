<?php

namespace AiCore\Entity;

use AiCore\Loader;
use AiCore\Model\String;

class Generic
{
    /**
     * Converts an array to entity
     *
     * @param $array
     */
    public function fromArray($array)
    {
        // fill object with data
        foreach ($array as $key => $element)
        {
            Loader::load('Model\String');
            $methodName = 'set' . String::toCamelCase($key, true);
            call_user_func_array(array($this, $methodName), array($element));
        }
    }

    /**
     * Converts entity to array
     *
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}

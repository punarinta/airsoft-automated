<?php

namespace AiCore;

class Loader
{
    /**
     * Loads a PHP file by a class name
     *
     * @param $ClassNames
     */
    static public function load($ClassNames)
    {
        $search  = array('\\', 'AiCore');
        $replace = array('/', '');

        if (!is_array($ClassNames))
        {
            $ClassNames = array ($ClassNames);
        }

        foreach ($ClassNames as $ClassName)
        {
            require_once (__DIR__ . '/' . str_replace($search, $replace, $ClassName) . '.php');
        }
    }
}

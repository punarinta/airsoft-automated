<?php

namespace AiCore\Model;

class String
{
    /**
     * Converts 'some_text' to 'someText' or 'SomeText'
     *
     * @param $str
     * @param bool $capitaliseFirst
     * @return mixed
     */
    static public function toCamelCase($str, $capitaliseFirst = false)
    {
        if ($capitaliseFirst)
        {
            $str[0] = strtoupper($str[0]);
        }
        $func = create_function('$c', 'return strtoupper($c[1]);');
        return preg_replace_callback('/_([a-z])/', $func, $str);
    }

    /**
     * Converts 'someText' and 'SomeText' to 'some_text'
     *
     * @param $str
     * @return mixed
     */
    static public function fromCamelCase($str)
    {
        $str[0] = strtolower($str[0]);
        $func = create_function('$c', 'return "_" . strtolower($c[1]);');
        return preg_replace_callback('/([A-Z])/', $func, $str);
    }
}

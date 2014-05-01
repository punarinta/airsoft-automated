<?php

namespace AiCore\View;

class Html
{
    protected $contents = '';

    /**
     * Operator '='
     *
     * @param $contents
     */
    public function load($contents)
    {
        $this->contents = $contents;
    }

    public function loadFromTemplate($path)
    {
        $this->contents = file_get_contents($path);
    }

    /**
     * Operator '+'
     *
     * @param $contents
     */
    public function push($contents)
    {
        $this->contents .= $contents;
    }

    /**
     * Performs a generic stdout flush of own contents
     */
    public function flush()
    {
        echo $this->contents;
    }
}

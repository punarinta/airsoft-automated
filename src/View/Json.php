<?php

namespace AiCore\View;

class Json
{
    protected $contents = array();
    protected $hasPadding = false;

    /**
     * @param array $contents
     * @param bool $hasPadding
     * @throws \Exception
     */
    public function __construct($contents = array(), $hasPadding = false)
    {
        if (!is_array($contents))
        {
            throw new \Exception('q');
        }

        $this->$hasPadding = $hasPadding;
        $this->contents = $contents;
    }

    /**
     * Push data to the top level
     *
     * @param $contents
     * @param null $key
     */
    public function push($contents, $key = null)
    {
        if ($key)
        {
            $this->contents[] = $contents;
        }
        else
        {
            $this->contents[$key] = $contents;
        }
    }

    /**
     * Outputs the JSON content
     */
    public function flush()
    {
        if ($this->$hasPadding)
        {
            echo '_jqjsp(';
        }

        echo json_encode($this->contents);

        if ($this->$hasPadding)
        {
            echo ');';
        }
    }
}

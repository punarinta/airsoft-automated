<?php

namespace AiCore\Service;

class Partial extends Generic
{
    protected $needsEntity = false;

    /**
     * Renders a pre-defined partial and populates it with data
     *
     * @param $partialName
     * @param array $data
     * @return string
     */
    public function render($partialName, $data = array())
    {
        include (__DIR__ . '/../View/Partial/' . $partialName . '.phtml');
    }
}

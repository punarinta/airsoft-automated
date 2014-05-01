<?php

namespace AiCore\Transport;

class Generic
{
    protected $transportRoute = '';

    public function deliver($message, $header = null)
    {
        throw new \Exception('Generic transport called. Header:' . $header . '; message: ' . $message);
    }
}

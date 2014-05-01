<?php

namespace AiCore\Transport;

class Sms extends Generic
{
    public function __construct($phone)
    {
        $this->transportRoute = $phone;
    }
}

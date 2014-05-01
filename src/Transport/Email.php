<?php

namespace AiCore\Transport;

class Email extends Generic
{
    public function __construct($email)
    {
        $this->transportRoute = $email;
    }

    /**
     * Sends an email
     *
     * @param $message
     * @param null $header
     */
    public function deliver($message, $header)
    {
        // generate mail header to support UTF
        $mailHeader = 'MIME-Version: 1.0' . "\r\n".
        'Content-type: text/plain; charset=utf-8' . "\r\n".
        'From: robot@airsoftinfo.ru' . "\r\n".
        'Reply-To: robot@airsoftinfo.ru' . "\r\n".
        'X-Mailer: PHP/' . phpversion();

        mail($this->transportRoute, $header, $message, $mailHeader);
    }
}

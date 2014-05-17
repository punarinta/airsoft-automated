<?php

class Paymill
{
    private $privateKey;

    public function __construct($privateKey)
    {
        $this->privateKey = $privateKey;
    }

    /**
     * Perform HTTP request to REST endpoint
     *
     * @param string $action
     * @param array  $params
     *
     * @return array
     */
    public function requestApi($action = '', $params = array())
    {
        $curlOpts = array
        (
            CURLOPT_URL            => "https://api.paymill.com/v2/" . $action,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_USERAGENT      => 'Paymill-php/0.0.2',
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_CAINFO         => realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'Paymill.crt',
        );

        $curlOpts[CURLOPT_POSTFIELDS] = http_build_query($params, null, '&');
        $curlOpts[CURLOPT_USERPWD] = $this->privateKey . ':';

        $curl = curl_init();
        curl_setopt_array($curl, $curlOpts);
        $responseBody = curl_exec($curl);
        $responseInfo = curl_getinfo($curl);
        if ($responseBody === false)
        {
            $responseBody = array('error' => curl_error($curl));
        }
        curl_close($curl);

        if ('application/json' === $responseInfo['content_type'])
        {
            $responseBody = json_decode($responseBody, true);
        }

        return array
        (
            'header' => array
            (
                'status' => $responseInfo['http_code'],
                'reason' => null,
            ),
            'body'   => $responseBody
        );
    }

    /**
     * Perform API and handle exceptions
     *
     * @param        $action
     * @param array  $params
     *
     * @return mixed
     */
    public function request($action, $params = array())
    {
        if (!is_array($params))
        {
            $params = array();
        }

        $responseArray = $this->requestApi($action, $params, $this->privateKey);
        $httpStatusCode = $responseArray['header']['status'];
        if ($httpStatusCode != 200)
        {
            $errorMessage = 'Client returned HTTP status code ' . $httpStatusCode;
            if (isset($responseArray['body']['error'])) {
                $errorMessage = $responseArray['body']['error'];
            }
            $responseCode = '';
            if (isset($responseArray['body']['data']['response_code'])) {
                $responseCode = $responseArray['body']['data']['response_code'];
            }

            return array("data" => array
            (
                "error"            => $errorMessage,
                "response_code"    => $responseCode,
                "http_status_code" => $httpStatusCode
            ));
        }

        return $responseArray['body']['data'];
    }
}
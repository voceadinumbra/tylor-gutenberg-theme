<?php

class showthemes_recaptcha
{
    const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';
    private $siteKey;
    private $secretKey;
    protected $errorCodes = array();

    public function __construct($siteKey, $secretKey)
    {
        $this->siteKey   = $siteKey;
        $this->secretKey = $secretKey;
    }

    public function validate($response)
    {
        if (empty($response))
            return false;

        $params = array(
            'secret'    => $this->secretKey,
            'response'  => $response
        );

        $url = self::VERIFY_URL . '?' . http_build_query($params);

        if (function_exists('curl_version'))
        {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
        }
        else {
            $response = file_get_contents($url);
        }

        if (empty($response) || is_null($response))
            return false;

        $json = json_decode($response, true);
        return $json['success'];
    }

}
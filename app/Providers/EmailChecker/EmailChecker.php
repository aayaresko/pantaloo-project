<?php


namespace App\Providers\EmailChecker;


class EmailChecker
{
    public $timeout = 1;
    public $fast = false;

    public function __construct()
    {
    }

    public function isInvalidEmail($email)
    {
        $url = $this->prepareUrl($email);

        return true;
    }

    private function prepareUrl($email){

        // Create parameters array.
        $parameters = array(
            'timeout' => $this->timeout,
            'fast' => $this->fast
        );

        $key = env('IPQUALITYSCORE_API_KEY');

        $formatted_parameters = http_build_query($parameters);

        // Create our API URL.
        $url = sprintf(
            'https://www.ipqualityscore.com/api/json/email/%s/%s?%s',
            $key,
            $email,
            $formatted_parameters
        );

        return $url;
    }
}
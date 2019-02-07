<?php

namespace App\Modules;

use GuzzleHttp\Client;

class QtechGames
{
    /**
     * @var mixed
     */
    private $paramsDefault;

    /**
     * PantalloGames constructor.
     */
    public function __construct()
    {
        $this->paramsDefault = config('qtechGames.forRequest');
    }

    /**
     * @param $params
     * @return string
     * @throws \Exception
     */
    protected function client($params)
    {
        //to edit this and add ssl certificate
        try {
            $params = array_merge($params, $this->paramsDefault);
            $url = $params['url'];
            $ssl = $params['ssl'];
            unset($params['url']);
            unset($params['ssl']);
            $body = $params;
            //$clientParams = [];
            $connectTimeout = $this->paramsDefault['connectTimeout'];
            $clientParams = [
                'verify' => false,
                'connect_timeout' => $connectTimeout
            ];
            $client = new Client($clientParams);
            $response = $client->post($url, [
                'form_params' => $body
            ]);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $errorLine = $e->getLine();
            throw new \Exception($errorMessage . '.' . $errorLine);
        }
        $responseBody = $response->getBody()->getContents();
        return $responseBody;
    }

    public function retrieveAccessToken()
    {
        $client = new Client([
            'verify' => false,
        ]);
        //https://api-int.qtplatform.com/v1/auth/token?grant_type=password&response_type=token&username=api_casinobit&password=BfRN18uA
        $response = $client->post('https://api-int.qtplatform.com/v1/auth/token', [
            'form_params' => []
        ]);
    }

}
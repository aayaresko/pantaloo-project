<?php

namespace App\Modules;

use GuzzleHttp\Client;

class PantalloGames
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
        $this->paramsDefault = config('pantalloGames.forRequest');
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

    /**
     * @param $name
     * @param $arguments
     * @return mixed|string
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        $params = [];
        $params['method'] = $name;
        $paramsUser = isset($arguments[0]) ? $arguments[0] : [];
        $handler = isset($arguments[1]) ? $arguments[1] : false;
        $params = array_merge($paramsUser, $params);
        if ($handler) {
            return json_decode($this->client($params));
        } else {
            return $this->client($params);
        }
    }
}
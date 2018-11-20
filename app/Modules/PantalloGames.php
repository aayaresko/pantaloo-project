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
        $this->paramsDefault = config('pantalloGames');
    }

    /**
     * @param $params
     * @return string
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
            $client = new Client(['verify' => false]);
            $response = $client->post($url, [
                'form_params' => $body
            ]);
        } catch (\Exception $e) {
            dd($e);
        }
        $responseBody = $response->getBody()->getContents();
        return $responseBody;
    }

    /**
     * @param $name
     * @param $arguments
     * @return string
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
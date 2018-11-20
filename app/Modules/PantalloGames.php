<?php

namespace App\Modules;

use GuzzleHttp\Client;

class PantalloGames
{
    private $paramsDefault;

    public function __construct()
    {
        $this->paramsDefault = config('pantalloGames');
    }

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
        $responseBody = json_decode($response->getBody()->getContents());
        return $responseBody;
    }

    public function getGameList()
    {
        $params['method'] = __FUNCTION__;
        return $this->client($params);
    }


}
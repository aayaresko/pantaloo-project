<?php

namespace App\Providers\EmailChecker;

use GuzzleHttp\Client;
use Helpers\GeneralHelper;
use GuzzleHttp\Exception\RequestException;

class EmailChecker
{
    public $timeout = 1;

    public $fast = false;

    public function isInvalidEmail($email, $default = false)
    {
        if (GeneralHelper::isTestMode()) {
            return false;
        }
        if (! empty($email)) {
            $url = $this->prepareUrl($email);

            $client = new Client();

            try {
                $response = $client->request('GET', $url, [
                    'timeout' => $this->timeout + 2,
                ]);

                if ($response->getStatusCode() == 200) {
                    $result = json_decode($response->getBody());

                    return ! $result->valid;
                }
            } catch (RequestException $e) {
                // Timeout
                return $default;
            }
        }
        // Empty email
        return $default;
    }

    private function prepareUrl($email)
    {

        // Create parameters array.
        $parameters = [
            'timeout' => $this->timeout,
            'fast' => $this->fast,
        ];

        $key = config('appAdditional.ipQualityScore');

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

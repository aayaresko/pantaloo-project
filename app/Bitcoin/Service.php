<?php
namespace App\Bitcoin;

use Illuminate\Support\Facades\Config;
use League\Flysystem\Exception;
use Nbobtc\Command\Command;
use Nbobtc\Http\Client;
use Nbobtc\Http\Message\Response;

// ./bitcoind -conf=/var/www/html/bitcoin-0.12.1/bin/bitcoin.conf -daemon -prune=1024

class Service
{
    private $client;

    public function __construct()
    {
        $this->connect();
    }

    public function isValidAddress($address)
    {
        try {
            $response = $this->client->sendCommand(new Command('validateaddress', $address));

            $data = static::getResponse($response);

            return $data['isvalid'];
        }
        catch (\Exception $e)
        {
            return false;
        }
    }

    public function test()
    {
        $response = $this->client->sendCommand(new Command('getaddressesbyaccount', 'common'));

        print_r($response);
        exit;

        return static::getResponse($response);
    }

    public function connect()
    {
        $connection_string = 'http://' . getenv('BITCOIN_USERNAME') . ':' . getenv('BITCOIN_PASSWORD') . '@' . getenv('BITCOIN_HOST') . ':' .  getenv('BITCOIN_PORT');
        
        $this->client = new Client($connection_string);
    }

    public function send($to, $sum)
    {
        $response = $this->client->sendCommand(new Command('sendtoaddress', [$to, $sum]));

        return static::getResponse($response);
    }

    public function info()
    {
        $response = $this->client->sendCommand(new Command('getinfo'));

        return static::getResponse($response);
    }

    public function getNewAddress()
    {
        $response = $this->client->sendCommand(new Command('getnewaddress', uniqid()));

        return static::getResponse($response);
    }

    public function getTransactions($count, $offset = 0)
    {
        $response = $this->client->sendCommand(new Command('listtransactions', ['*', $count, $offset]));

        return static::getResponse($response);
    }

    public function getTransaction($transaction_id)
    {
        try {
            $response = $this->client->sendCommand(new Command('gettransaction', $transaction_id));

            $res = static::getResponse($response);
            return $res;
        } catch (\Exception $e) {
            if ($e->getMessage() == 'Invalid or non-wallet transaction id') return false;
            else throw $e;
        }
    }

    static function getResponse($response)
    {
        $result = json_decode($response->getBody()->getContents(), true);

        if(!is_array($result)) throw new \Exception('Invalid json');

        if(isset($result['error'])) throw new \Exception($result['error']['message']);

        if(!isset($result['result'])) throw new \Exception('Result not found');

        return $result['result'];
    }
}
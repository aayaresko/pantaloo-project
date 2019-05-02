<?php


namespace App\Providers\Intercom;

use App\Providers\IntercomEventsResolver;
use App\User;
use Intercom\IntercomClient;

class Intercom
{
    protected $client;

    public function __construct()
    {
        $config = config('intercom');
        $token = $config['intercom_token'];

        $this->client = new IntercomClient($token);
    }

    public function create_or_update_user(User $user){

        $result = $this->client->users->create(UserDataResolver::getData($user));
        dump($result);
    }

    public function send_event(User $user, $event_name, $timestamp){
        $data = IntercomEventsResolver::getEventsData($user, $event_name, $timestamp);
        dump($data);
        $result = $this->client->events->create($data);
        dump($result);

    }

}
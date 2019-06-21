<?php

namespace App\Providers\Intercom;

use App\User;
use Helpers\IntercomHelper;
use Illuminate\Support\Facades\DB;
use Intercom\IntercomClient;
use App\Providers\Intercom\IntercomEventsResolver;

class Intercom
{
    protected $_client;

    public function create_or_update_user(User $user)
    {
        $result = $this->client($user)->users->create(UserDataResolver::getData($user));

        return $result;
    }

    public function send_event($data)
    {
        $user = User::where('email', $data['email'])->first();

        $result = $this->client($user)->events->create($data);

        return $result;
    }

    private function client($user)
    {

        $intercom = IntercomHelper::getIntercomConfigByUser($user);

        return new IntercomClient($intercom->token);
    }

}

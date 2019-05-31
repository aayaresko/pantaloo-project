<?php

namespace App\Slots;

use App\Slot;
use App\Token;
use Exception;
use Jenssegers\Agent\Agent;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class Casino
{
    public $api_version = '1.2';

    public $operator_id;

    public $private_key;

    public $currency = 'EUR';

    public $request;

    public $response;

    public $error;

    public $agent;

    public static $mobile_debug = false;

    //public $rate = 0.2;

    public function __construct($operator_id, $private_key)
    {
        $this->operator_id = $operator_id;
        $this->private_key = $private_key;
    }

    public static function GetUser()
    {
        if (! Auth::check()) {
            throw new Exception('Auth required');
        }

        $data = [
            'user_id' => Auth::user()->id,
            'session_id' => uniqid().rand(),
            'login' => Auth::user()->id,
            'password' => '000000',
        ];

        return $data;
    }

    public function CheckSignature($request)
    {
        switch ($request['request']) {
            case 'getaccount': $params = ['apiversion', 'loginname', 'password', 'request', 'device', 'sessionid'];

                break;
            case 'getbalance': $params = ['apiversion', 'loginname', 'password', 'request', 'device', 'accountid', 'gamemodel', 'gamesessionid', 'gametype', 'gpgameid', 'gpid', 'nogsgameid', 'product'];

                break;
            case 'wager': $params = ['apiversion', 'loginname', 'password', 'request', 'device', 'accountid', 'betamount', 'gamemodel', 'gamesessionid', 'gametype', 'gpgameid', 'gpid', 'nogsgameid', 'product', 'roundid', 'transactionid'];

                break;
            case 'result': $params = ['apiversion', 'loginname', 'password', 'request', 'device', 'accountid', 'gamemodel', 'gamesessionid', 'gamestatus', 'gametype', 'gpgameid', 'gpid', 'nogsgameid', 'product', 'result', 'roundid', 'transactionid'];

                break;
            case 'rollback': $params = ['apiversion', 'loginname', 'password', 'request', 'device', 'accountid', 'gamemodel', 'gamesessionid', 'gametype', 'gpgameid', 'gpid', 'nogsgameid', 'product', 'rollbackamount', 'roundid', 'transactionid'];

                break;
            default: throw new Exception('No such method');
        }

        $data = [];

        foreach ($params as $key) {
            $data[$key] = $request->input($key);
        }

        $signature = strtoupper(md5(http_build_query($data).$this->private_key));

        if ($signature != $request['signature']) {
            throw new Exception('Invalid signature');
        }
    }

    public function Handle($request)
    {
        $this->request = $request;

        try {
            $this->CheckSignature();

            switch ($request['request']) {
                case 'getaccount': $this->GetAccount();

                    break;
                case 'getbalance': $this->GetBalance();

                    break;
                case 'wager': $this->Wager();

                    break;
                case 'result': $this->Result();

                    break;
                case 'rollback': $this->Rollback();

                    break;
                default: throw new Exception('No such method');
            }
        } catch (Exception $e) {
            $code = $e->getCode();
            if (empty($code)) {
                $code = 1;
            }

            $this->error = ['code' => $code, 'msg' => $e->getMessage()];
        }

        return $this->Response();
    }

    public function Response($method, $data, $error = ['msg' => '', 'code' => 0])
    {
        if (! isset($error['code'])) {
            $error['code'] = 0;
        }

        $data['APIVERSION'] = $this->api_version;

        $msg = '';
        if ($error['msg']) {
            $msg = ' msg="'.$error['msg'].'"';
        }

        $xml = '<?xml version="1.0" encoding="UTF-8" ?>'."\n".'<RSP request="'.$method.'" rc="'.$error['code'].'"'.$msg.'>'."\n";

        foreach ($data as $key => $value) {
            $xml = $xml.'<'.strtoupper($key).'>'.$value.'</'.strtoupper($key).'>'."\n";
        }

        $xml = $xml.'</RSP>'."\n";

        return $xml;
    }

    public function GetHash($query)
    {
        return strtoupper(md5($this->private_key.http_build_query($query)));
    }

    public static function IsMobile()
    {
        if (self::$mobile_debug) {
            return true;
        }

        $agent = new Agent();

        if ($agent->isMobile() or $agent->isTablet()) {
            return true;
        } else {
            return false;
        }
    }

    public function NetEnt($data)
    {
        //print_r($data);

        $url = 'https://24techpro.com/api/dispatcher/deal/openNetEntGame';

        $post = 'accessPassword='.$this->GetHash($data).'&'.http_build_query($data);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $page = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($page, true);

        //print_r($data);

        if (! isset($data['game']['url'])) {
            throw new Exception('Problem with start_url');
        }

        return $data['game']['url'];
    }

    public function RegToken($data)
    {
        $url = 'http://mobile.casinobit.co/live/dispatcher/spinner/registerCasinoToken?';

        $post = 'accessPassword='.$this->GetHash($data).'&'.http_build_query($data);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_VERBOSE, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        $page = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($page, true);

        if ($data['errorCode'] != 0) {
            throw new Exception('Unknown error');
        }

        return str_replace('&amp;', '&', $data['url']);
    }

    public function SlotStartURL(Slot $slot, $type = 'slots')
    {
        if (! Auth::check()) {
            throw new Exception('Auth required');
        }

        $token = new Token();
        $token->generate();
        $token->user()->associate(Auth::user());
        $token->slot()->associate($slot);
        $token->save();

        if ($slot->category) {
            $cat_id = $slot->category->id;
        } else {
            $cat_id = 0;
        }

        $result = [
            'url' => false,
            'object' => false,
        ];

        switch ($cat_id) {
            case 0:
                $result['url'] = $this->RegToken([
                    'operatorId' => $this->operator_id,
                    'username' => Auth::user()->id,
                    'password' => '000000',
                    'sessionId' => $token->token,
                ]);

                if (self::isMobile()) {
                    $result['url'] = $result['url'].'&platform=mobile';
                }

                break;
            case 1:
                $data = [
                    'operatorId' => $this->operator_id,
                    'playerId' => Auth::user()->id,
                    'sessionId' => $token->token,
                    'gameName' => $slot->name,
                    'language' => 'english',
                    'roomId' => $slot->room_id,
                    'flashSwf' => $slot->path,
                ];

                //$url = 'http://nogs-gl.EliteGamesinteractive.eu/game/?';
                $result['url'] = 'https://24techpro.com/api/launcher/casbit.html?'.http_build_query($data);
                if (self::isMobile()) {
                    //$data['mode'] = 'demo';
                    $result['url'] = 'http://mobile.casinobit.co:8080/novo-admin/open-game.html?'.http_build_query($data);
                }

                break;
            case 2:
                $data = [
                    'gameName' => $slot->name,
                    'mode' => 'external',
                    'playerName' => Auth::user()->id,
                    'operatorId' => $this->operator_id,
                    'sessionId' => $token->token,
                ];

                $result['url'] = 'https://mobile.casinobit.co:8443/amatic-admin/launcher/opengame.html?'.http_build_query($data);

                break;
            case 3:
                $result['object'] = $this->NetEnt([
                    'operatorId' => $this->operator_id,
                    'username' => Auth::user()->id,
                    'sessionId' => $token->token,
                    'gameId' => $slot->room_id,
                ]);

                break;

            case 7:
                $data = [
                    'operatorId' => $this->operator_id,
                    'username' => Auth::user()->id,
                    'password' => '000000',
                    'sessionId' => $token->token,
                ];

                $url = 'http://24techpro.com/live/dispatcher/spinner/registerCasinoToken?';

                $post = 'accessPassword='.$this->GetHash($data).'&'.http_build_query($data);

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_VERBOSE, 2);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
                $page = curl_exec($curl);
                curl_close($curl);

                $result = json_decode($page, true);

                if ($result['errorCode'] != 0) {
                    throw new Exception('Unknown error');
                }

                if (self::IsMobile()) {
                    $result['url'] = $result['url'].'&platform=mobile';
                }

            break;

            default:
                if (self::IsMobile()) {
                    $data = [
                        'gameName' => strtolower($slot->name),
                        'mode' => 'external',
                        'operatorId' => $this->operator_id,
                        'sessionId' => $token->token,
                        'userName' => Auth::user()->id,
                    ];
                    $result['url'] = 'http://mobile.casinobit.co/netent-admin/netent-mobile.html?'.http_build_query($data);
                } else {
                    $result['url'] = $this->NetEnt([
                        'operatorId' => $this->operator_id,
                        'username' => Auth::user()->id,
                        'sessionId' => $token->token,
                        'gameId' => $slot->room_id,
                    ]);

                    break;
                }
        }

        return $result;

        $post = 'accessPassword='.$this->GetHash($data).'&'.http_build_query($data);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_VERBOSE, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        $page = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($page, true);

        print_r($data);
        exit;

        if ($data['errorCode'] != 0) {
            throw new Exception('Unknown error');
        }

        if (self::IsMobile()) {
            return $data['url'].'&platform=mobile';
        } else {
            return $data['url'];
        }
    }
}

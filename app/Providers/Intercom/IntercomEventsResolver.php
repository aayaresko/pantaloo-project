<?php


namespace App\Providers\Intercom;

use App\User;
use Carbon\Carbon;

class IntercomEventsResolver
{
    const OPEN_BONUS = 'OPEN_BONUS';
    const CLOSE_BONUS = 'CLOSE_BONUS';
    const DEPOSIT = 'DEPOSIT';
    const WAGER_DONE = 'WAGER_DONE';
    const DEPOSIT_WAGER_DONE = 'DEPOSIT_WAGER_DONE';
    const BONUS_DEPOZIT_SIZE = 'BONUS_DEPOZIT_SIZE';

    public static function getEventsData(User $user, $event_name, $timestamp)
    {
        $data = self::getEventData($user, $event_name, $timestamp);

        $dt = Carbon::create();

        $response = [
            "event_name" => $data->event_name,
            "created_at" => $data->event_time,
            "email" => "example2@example.com",
            "metadata" => $data->event_data,
        ];

        return $response;
    }

    private static function eventNameFormat($event_name, $timestamp)
    {
        $dt = Carbon::createFromTimestamp($timestamp);

        Carbon::setToStringFormat('d-m-y H:i');

        $response = (string)$dt . ' ' . strtolower(str_replace('_', ' ', $event_name)) . "\n";

        return $response;
    }

    private static function getEventData(User $user, $event_name, $timestamp)
    {
        $dataMethodName = camel_case("get_{$event_name}_data");

        if (!method_exists(IntercomEventsResolver::class, $dataMethodName)) {
            throw new \Exception('Call not exists events and method');
        }

        $eventData = self::$dataMethodName($user, $event_name);


        $response = new \stdClass();

        $response->event_name = self::eventNameFormat($event_name, $timestamp) . $eventData['event_name_addon'];
        dump($eventData);
        $response->event_time = $timestamp;
        $response->event_data = $eventData['event_data'];

        return $response;
    }

    private static function getOpenBonusData(User $user, $event_name)
    {
        return [
            'event_name_addon' => 'event_name_addon',
            'event_data' => [
                'eventProp' => 'dummy'
            ]
        ];
    }

}